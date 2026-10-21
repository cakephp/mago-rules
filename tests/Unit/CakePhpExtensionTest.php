<?php

declare(strict_types=1);

namespace MagoCakePHP\Tests\Unit;

use MagoCakePHP\CakePhpExtension;
use PHPUnit\Framework\TestCase;

final class CakePhpExtensionTest extends TestCase
{
    public function testRegistersTheCakePhpCompatibilityRules(): void
    {
        $extension = CakePhpExtension::create();

        $this->assertSame('mago-cakephp/rules', $extension->identifier);
        $this->assertSame(
            [
                'mago-cakephp/trait-suffix',
                'mago-cakephp/public-method-underscore',
                'mago-cakephp/elseif',
                'mago-cakephp/function-docblock',
                'mago-cakephp/docblock-tag-spacing',
                'mago-cakephp/throws-tag',
                'mago-cakephp/inherit-doc',
            ],
            array_map(static fn($rule): string => $rule->getDefinition()->code, $extension->linterRules),
        );
    }
}
