<?php

declare(strict_types=1);

namespace MagoCakePHP\Tests\Unit;

use MagoCakePHP\Linter\PathMatcher;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class PathMatcherTest extends TestCase
{
    /**
     * @return iterable<string, array{string, bool}>
     */
    public static function paths(): iterable
    {
        yield 'relative test path' => ['tests/TestCase/ExampleTest.php', true];
        yield 'nested test path' => ['project/tests/TestCase/ExampleTest.php', true];
        yield 'Windows path' => ['project\\tests\\TestCase\\ExampleTest.php', true];
        yield 'source path' => ['src/Model/Example.php', false];
    }

    #[DataProvider('paths')]
    public function testMatchesTestPaths(string $path, bool $expected): void
    {
        $this->assertSame($expected, PathMatcher::matches($path, ['tests/**', '**/tests/**']));
    }
}
