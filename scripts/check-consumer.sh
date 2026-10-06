#!/usr/bin/env bash
set -euo pipefail

repo_root="$(cd "$(dirname "$0")/.." && pwd)"
consumer_dir="$(mktemp -d /tmp/cakephp-mago-consumer.XXXXXX)"

cleanup() {
    rm -rf "$consumer_dir"
}
trap cleanup EXIT

cp -R "$repo_root/tests/consumer/." "$consumer_dir"
mkdir -p "$consumer_dir/vendor/cakephp"
ln -s "$repo_root" "$consumer_dir/vendor/cakephp/mago-rules"

mago="$repo_root/vendor/bin/mago"
"$mago" --workspace "$consumer_dir" extension validate
"$mago" --workspace "$consumer_dir" format --check
"$mago" --workspace "$consumer_dir" format --check vendor/cakephp-types.php
"$mago" --workspace "$repo_root" format --check config/static-analysis/cakephp-associations.php
"$mago" --workspace "$consumer_dir" lint
"$mago" --workspace "$consumer_dir" analyze src/AssociationTargetTypes.php --reporting-format short

# The installed preset must reject fully qualified classes without --only or
# --pedantic, then apply an exact, idempotent native fix. PHPDoc stays unchanged.
cp "$repo_root/tests/fixer/class-imports.input.php" "$consumer_dir/src/ClassImports.php"
if "$mago" --workspace "$consumer_dir" lint --reporting-format short > "$consumer_dir/class-imports.log" 2>&1; then
    echo "Expected the installed preset to reject fully qualified class references." >&2
    exit 1
fi
if ! grep -q 'error\[no-fully-qualified-global-class-like\]' "$consumer_dir/class-imports.log"; then
    cat "$consumer_dir/class-imports.log" >&2
    exit 1
fi
"$mago" --workspace "$consumer_dir" lint --fix --format-after-fix --fail-on-remaining
"$mago" --workspace "$consumer_dir" lint --fix --format-after-fix --fail-on-remaining
diff -u "$repo_root/tests/fixer/class-imports.expected.php" "$consumer_dir/src/ClassImports.php"
"$mago" --workspace "$consumer_dir" lint
