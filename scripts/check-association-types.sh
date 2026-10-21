#!/usr/bin/env bash
set -euo pipefail

repo_root="$(cd "$(dirname "$0")/.." && pwd)"
mago="$repo_root/vendor/bin/mago"
diagnostics="$(mktemp /tmp/cakephp-mago-associations.XXXXXX)"

cleanup() {
    rm -f "$diagnostics"
}
trap cleanup EXIT

# Forward Mago's workspace/config options so the same assertions can exercise
# the installed preset's fixture or an actual CakePHP checkout.
"$mago" "$@" analyze --reporting-format short \
    "$repo_root/tests/consumer/src/AssociationTargetTable.php" \
    "$repo_root/tests/consumer/src/AssociationTargetTypes.php"

if "$mago" "$@" analyze --reporting-format short \
    "$repo_root/tests/consumer/src/AssociationTargetTable.php" \
    "$repo_root/tests/analyzer/association-targets.invalid.php" > "$diagnostics" 2>&1; then
    echo "Expected incompatible association target return types to fail analysis." >&2
    exit 1
fi

# Each association must resolve to the concrete target, rather than Table or
# mixed. Also reject unexpected errors that could hide a broken test setup.
errors="$(grep -Fc 'error[' "$diagnostics" || true)"
invalid_returns="$(grep -Fc 'error[invalid-return-statement]' "$diagnostics" || true)"
if [[ "$errors" -ne 4 || "$invalid_returns" -ne 4 ]]; then
    cat "$diagnostics" >&2
    exit 1
fi
