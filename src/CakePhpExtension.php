<?php

declare(strict_types=1);

namespace MagoCakePHP;

use Mago\Sdk\Extension;
use MagoCakePHP\Linter\Rules\DocblockTagSpacingRule;
use MagoCakePHP\Linter\Rules\ElseIfDeclarationRule;
use MagoCakePHP\Linter\Rules\FunctionDocblockRule;
use MagoCakePHP\Linter\Rules\InheritDocRule;
use MagoCakePHP\Linter\Rules\PublicUnderscoreMethodRule;
use MagoCakePHP\Linter\Rules\ThrowsTagRule;
use MagoCakePHP\Linter\Rules\TraitSuffixRule;

final class CakePhpExtension
{
    /**
     * Prevents instantiation of the extension factory.
     */
    private function __construct() {}

    /**
     * Creates the CakePHP rules extension.
     */
    public static function create(): Extension
    {
        return new Extension(
            identifier: 'mago-cakephp/rules',
            name: 'CakePHP rules for Mago',
            version: '0.1.0-dev',
            linterRules: [
                new TraitSuffixRule(),
                new PublicUnderscoreMethodRule(),
                new ElseIfDeclarationRule(),
                new FunctionDocblockRule(),
                new DocblockTagSpacingRule(),
                new ThrowsTagRule(),
                new InheritDocRule(),
            ],
        );
    }
}
