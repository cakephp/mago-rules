<?php

declare(strict_types=1);

namespace MagoCakePHP\Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class RuleIntegrationTest extends TestCase
{
    /**
     * @return iterable<string, array{string, list<string>}>
     */
    public static function rules(): iterable
    {
        yield 'trait suffix' => ['mago-cakephp/trait-suffix', ['rules/TraitSuffix.php']];
        yield 'method underscore' => ['mago-cakephp/public-method-underscore', ['rules/PublicMethodUnderscore.php']];
        yield 'elseif' => ['mago-cakephp/elseif', ['rules/ElseIf.php']];
        yield 'function docblock' => [
            'mago-cakephp/function-docblock',
            ['rules/FunctionDocblock.php', 'rules/tests/FunctionDocblockTest.php'],
        ];
        yield 'docblock tag spacing' => ['mago-cakephp/docblock-tag-spacing', ['rules/DocblockTagSpacing.php']];
        yield 'throws tag' => ['mago-cakephp/throws-tag', ['rules/ThrowsTag.php']];
        yield 'inheritDoc' => ['mago-cakephp/inherit-doc', ['rules/InheritDoc.php']];
    }

    /**
     * Runs a rule through Mago's parser and extension protocol.
     *
     * @param list<string> $fixtures
     */
    #[DataProvider('rules')]
    public function testRuleFixtures(string $rule, array $fixtures): void
    {
        $root = dirname(path: __DIR__, levels: 2);
        $command = [
            $root . '/vendor/bin/mago',
            '--workspace',
            $root . '/tests/corpus',
            'lint',
            '--only',
            $rule,
            '--reporting-format',
            'short',
            ...$fixtures,
        ];
        $pipes = [];
        $process = proc_open(
            $command,
            [1 => ['pipe', 'w'], 2 => ['pipe', 'w']],
            $pipes,
            $root,
        );

        $this->assertIsResource($process);
        $output = stream_get_contents($pipes[1]) . stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        $this->assertSame(0, proc_close($process), $output);
    }
}
