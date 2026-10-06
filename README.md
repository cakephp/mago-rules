# CakePHP rules for Mago

[![Quality gates](https://github.com/cakephp/mago-rules/actions/workflows/quality.yml/badge.svg)](https://github.com/cakephp/mago-rules/actions/workflows/quality.yml)
[![License](https://img.shields.io/github/license/cakephp/mago-rules)](LICENSE)
[![Packagist downloads](https://img.shields.io/packagist/dt/cakephp/mago-rules?logo=packagist&label=downloads)](https://packagist.org/packages/cakephp/mago-rules)
[![Mago compatibility](https://img.shields.io/badge/Mago-%5E1.47-f97316)](https://mago.carthage.software/)

A Mago-first coding-standard preset for CakePHP applications and plugins. Mago
owns generic formatting, linting, code-quality checks, and fixes; this package
adds CakePHP formatter preferences and the few framework conventions that Mago
cannot express natively.

## Installation

```sh
composer require --dev cakephp/mago-rules carthage-software/mago
```

Import the preset from the project's `mago.toml`:

```toml
extends = "vendor/cakephp/mago-rules/cakephp.mago.toml"
```

The imported configuration starts the package-owned extension worker. No
copied bootstrap or manual rule registration is needed.

## Usage

Use Mago's normal commands:

```sh
mago format --check
mago lint
```

Apply formatting and safe lint fixes with:

```sh
mago format
mago lint --fix --format-after-fix --fail-on-remaining
```

Adopting the preset is intentionally a code-quality migration. Existing
CakePHP repositories are expected to have formatter changes and native Mago
findings on their first run. Review the formatting as a dedicated change, then
either address lint findings incrementally or establish a baseline:

```sh
mago format --dry-run
mago lint --stats
mago lint --baseline mago-baseline.toml --generate-baseline
mago lint --baseline mago-baseline.toml
mago lint --baseline mago-baseline.toml --remove-outdated-baseline-entries
```

Commit the baseline and remove outdated entries as findings are fixed. New
findings remain visible, so the baseline is a migration tool rather than a
weaker permanent ruleset.

The preset starts from Mago's default rule catalogue, then disables language
preferences that conflict with established CakePHP code. Projects can override
native or CakePHP rules after the `extends` declaration:

```toml
[linter.rules]
cyclomatic-complexity = { enabled = false }
"mago-cakephp/function-docblock" = { enabled = false }
```

Class references must use imports rather than fully qualified names in executable
code. The native `no-fully-qualified-global-class-like` rule is enabled at error
severity; `mago lint --fix` adds imports and shortens references. PHPDoc names
are unaffected.

## Responsibility map

| Concern | Owner |
| --- | --- |
| PSR-12 layout, braces, imports, quotes, commas, casts and return spacing | Mago formatter with CakePHP settings |
| Imported class references | Native `no-fully-qualified-global-class-like` rule |
| Short arrays, braced blocks, short tags, silenced errors, assignments in conditions and redundant syntax | Native Mago rules |
| Conflicting `empty()`/`isset()`/comparison/named-argument preferences | Disabled by the CakePHP preset |
| PHPUnit assertions use `$this->assert…` | Native `assertion-style` rule at error severity |
| Method counts in controllers, tables, and helpers | Native `too-many-methods`, with conventional CakePHP path exclusions |
| Declared entity field counts | Native `too-many-properties`, with conventional entity path exclusions |
| Trait `Trait` suffix | `mago-cakephp/trait-suffix` |
| Method underscore prefixes, with Entity accessor/mutator exceptions | `mago-cakephp/public-method-underscore` |
| `elseif` spelling | `mago-cakephp/elseif` |
| Function and method docblocks outside tests | `mago-cakephp/function-docblock` |
| One space after PHPDoc tags | `mago-cakephp/docblock-tag-spacing` |
| Exception type on `@throws` | `mago-cakephp/throws-tag` |
| CakePHP `@inheritDoc` spelling and placement | `mago-cakephp/inherit-doc` |

Use `@mago-expect lint:<rule-code>` for an intentional local exception, or a
Mago baseline when migrating an existing project. PHPCS suppression comments
are not interpreted by this package.

## Framework maintainability exceptions

PHPUnit instance test methods use `$this->assert…`; inconsistent styles are
errors. Mago classifies assertion-style conversions as potentially unsafe, so
applying these fixes requires `mago lint --fix --potentially-unsafe`. Review the
result before keeping it.

The preset excludes `**/src/Controller/**`, `**/src/Model/Table/**`, and
`**/src/View/Helper/**` from the native method-count rule. These classes expose
framework actions, finders, and helper APIs. It excludes `**/src/Model/Entity/**`
from the property-count rule because persisted entities can declare many fields,
including CakePHP 6 property hooks. The property rule counts declared properties;
PHPDoc association and entity annotations do not contribute to that count.

These are path-based exceptions for conventional application and plugin layouts,
not inheritance checks. Both native rules keep their default thresholds elsewhere,
including services. Projects with custom layouts can add their own exclusions.

`no-boolean-flag-parameter` remains enabled. Mago supports file exclusions but
cannot exempt specific inherited method signatures through configuration. Use a
local `@mago-expect lint:no-boolean-flag-parameter` for a reported boolean flag
that a CakePHP hook or overridden API requires. A shared signature-aware exemption
would need native Mago support; disabling this rule across controllers or tables
would also hide avoidable flags in application methods.

Complexity thresholds, parameter limits, literal-password policy, and stricter
`isset()` preferences remain project choices rather than CakePHP conventions.

## Relationship to CakePHP CodeSniffer

This is not a PHPCS emulator or a promise of sniff-for-sniff parity. Generic
PHPCS and Slevomat concerns belong to Mago's formatter and native linter, even
when diagnostics or fixes differ. Mago may report additional quality or
security findings because its defaults remain enabled, and formatter output is
not expected to be byte-for-byte identical to PHPCBF.

Rules are added here only when they encode CakePHP-specific policy that native
Mago cannot express. A difference from CakePHP CodeSniffer alone is not a reason
to recreate a sniff. Conversely, a clean CakePHP CodeSniffer run does not imply
a clean Mago run: Mago's maintainability, correctness, and security diagnostics
are part of the value of adopting this preset.

Mago internally understands PHPDoc, but version 1.47's PHP extension SDK exposes
docblocks as source trivia rather than a parsed PHPDoc tree. The extension
therefore implements only the small line-oriented PHPDoc policies listed above.

## Compatibility and development

| Package requirement | Supported version |
| --- | --- |
| PHP | `^8.4` |
| Mago | `^1.47` |
| Primary target | CakePHP 6.x applications and plugins |

Run `composer run cs-check`, `composer test`, `composer run lint-corpus`,
`composer run test-fixes`, `composer run test-formatter`, and
`composer run test-consumer`. To check the CakePHP-specific extension rules for
false positives against CakePHP itself, pass an existing shallow checkout with
`scripts/check-cakephp-6.sh --workspace /path/to/cakephp`; without an argument
the script creates a temporary shallow clone of CakePHP 6.x. Add
`--migration-audit` to run the complete native Mago preset and see the
refactoring backlog. That audit is expected to exit unsuccessfully until the
target codebase has completed its migration. A bare positional path remains
supported as shorthand.
