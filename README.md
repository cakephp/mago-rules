# CakePHP rules for Mago

[![Quality gates](https://github.com/cakephp/mago-rules/actions/workflows/quality.yml/badge.svg)](https://github.com/cakephp/mago-rules/actions/workflows/quality.yml)
[![License](https://img.shields.io/github/license/cakephp/mago-rules)](LICENSE)
[![Packagist downloads](https://img.shields.io/packagist/dt/cakephp/mago-rules?logo=packagist&label=downloads)](https://packagist.org/packages/cakephp/mago-rules)
[![Mago compatibility](https://img.shields.io/badge/Mago-%5E1.47-f97316)](https://mago.carthage.software/)

A coding-standard preset for CakePHP applications and plugins, combining
[Mago](https://mago.carthage.software/)'s formatter and linter with CakePHP conventions.

Requires PHP `^8.4` and Mago `^1.47`. Designed for CakePHP 6.x applications and plugins.

## Installation

```sh
composer require --dev cakephp/mago-rules carthage-software/mago
```

Import the preset from the project's `mago.toml`:

```toml
extends = "vendor/cakephp/mago-rules/cakephp.mago.toml"
```

## Usage

Check formatting and lint your project:

```sh
vendor/bin/mago format --check
vendor/bin/mago lint
```

Apply formatting and safe lint fixes with:

```sh
vendor/bin/mago format
vendor/bin/mago lint --fix --format-after-fix --fail-on-remaining
```

PHPUnit assertion-style fixes require the additional `--potentially-unsafe` flag.
Review those changes before keeping them.

## What the preset includes

The preset starts from Mago's default rules and applies CakePHP formatting and
style choices, including short arrays, imported class references, camelCase
function names, and `$this->assert…` in PHPUnit tests. It also checks for negated
ternary conditions and parameter shadowing. See [cakephp.mago.toml](cakephp.mago.toml)
for the complete configuration.

CakePHP-specific rules cover:

| Rule | Convention |
| --- | --- |
| `mago-cakephp/trait-suffix` | Trait names end in `Trait` |
| `mago-cakephp/public-method-underscore` | No underscore-prefixed methods, except magic methods and Entity accessors/mutators |
| `mago-cakephp/elseif` | Use `elseif` rather than `else if` |
| `mago-cakephp/function-docblock` | Document functions and methods outside tests |
| `mago-cakephp/docblock-tag-spacing` | One space after PHPDoc tags |
| `mago-cakephp/throws-tag` | Include an exception type in `@throws` |
| `mago-cakephp/inherit-doc` | Use CakePHP's `@inheritDoc` spelling and placement |

Method-count checks exclude conventional `src/Controller`, `src/Model/Table`, and
`src/View/Helper` paths in applications and plugins. Property-count checks exclude
`src/Model/Entity`. Both checks remain active elsewhere; custom layouts may need
additional exclusions.

## Configuration

Override individual rules in your project's `mago.toml` after the `extends` declaration:

```toml
[linter.rules]
no-parameter-shadowing = { level = "error" }
"mago-cakephp/function-docblock" = { enabled = false }
```

For an intentional local exception, use `@mago-expect lint:<rule-code>`.
PHPCS suppression comments are not recognized.

## Adopting in an existing project

Preview formatting changes with `vendor/bin/mago format --dry-run`. Mago may
report additional findings compared with CakePHP CodeSniffer. To fix existing
lint findings gradually, generate a baseline:

```sh
vendor/bin/mago lint --baseline mago-baseline.toml --generate-baseline
vendor/bin/mago lint --baseline mago-baseline.toml
```

Commit the baseline and use it in subsequent lint runs. Remove resolved entries
with `vendor/bin/mago lint --baseline mago-baseline.toml --remove-outdated-baseline-entries`.
