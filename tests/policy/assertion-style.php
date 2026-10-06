<?php
declare(strict_types=1);

namespace App\Test;

use PHPUnit\Framework\TestCase;

final class AssertionStyleTest extends TestCase
{
    public function testAssertionStyles(): void
    {
        self::assertSame(1, 1);
        static::assertTrue(true);
        $this->assertFalse(false);
    }
}
