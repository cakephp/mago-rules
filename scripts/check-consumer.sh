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
"$mago" --workspace "$repo_root" format --check tests/policy
"$mago" --workspace "$consumer_dir" extension validate
"$mago" --workspace "$consumer_dir" format --check
"$mago" --workspace "$consumer_dir" lint

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

# Check that the installed preset rejects static assertions at error severity.
cp "$repo_root/tests/policy/assertion-style.php" "$consumer_dir/tests/AssertionStyleTest.php"
if "$mago" --workspace "$consumer_dir" lint --reporting-format short > "$consumer_dir/assertion-style.log" 2>&1; then
    echo "Expected the installed preset to reject static assertions." >&2
    exit 1
fi
if ! grep -q 'error\[assertion-style\]' "$consumer_dir/assertion-style.log"; then
    cat "$consumer_dir/assertion-style.log" >&2
    exit 1
fi
rm "$consumer_dir/tests/AssertionStyleTest.php"

# Native count rules remain active for services, while app and plugin framework
# paths are exempt. Do not use --only: it bypasses the preset's configuration.
check_count_policy() {
    local rule="$1"
    local fixture="$2"
    local prefix location target
    shift 2
    for prefix in "" "plugins/Example/"; do
        for location in "$@"; do
            target="$prefix$location/Count.php"
            mkdir -p "$consumer_dir/$(dirname "$target")"
            cp "$repo_root/tests/policy/$fixture" "$consumer_dir/$target"
            if ! "$mago" --workspace "$consumer_dir" lint --retain-code "$rule" "$target" > "$consumer_dir/count.log" 2>&1; then
                cat "$consumer_dir/count.log" >&2
                exit 1
            fi
            rm "$consumer_dir/$target"
        done
        target="${prefix}src/Service/Count.php"
        mkdir -p "$consumer_dir/$(dirname "$target")"
        cp "$repo_root/tests/policy/$fixture" "$consumer_dir/$target"
        if "$mago" --workspace "$consumer_dir" lint --retain-code "$rule" --reporting-format short "$target" > "$consumer_dir/count.log" 2>&1; then
            echo "Expected $rule to remain active for $target." >&2
            exit 1
        fi
        if ! grep -Fq "error[$rule]" "$consumer_dir/count.log"; then
            cat "$consumer_dir/count.log" >&2
            exit 1
        fi
        rm "$consumer_dir/$target"
    done
}
check_count_policy too-many-methods method-count.php src/Controller src/Model/Table src/View/Helper
check_count_policy too-many-properties property-count.php src/Model/Entity
