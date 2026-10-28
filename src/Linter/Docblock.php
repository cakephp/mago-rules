<?php

declare(strict_types=1);

namespace MagoCakePHP\Linter;

use Mago\Sdk\Span;
use Mago\Sdk\Syntax\SourceFile;
use Mago\Sdk\Syntax\Trivia;
use Mago\Sdk\Syntax\TriviaKind;

/**
 * A minimal, span-aware view of PHPDoc exposed as trivia by Mago's PHP SDK.
 */
final readonly class Docblock
{
    /**
     * @param list<DocblockTag> $tags
     */
    private function __construct(
        public Span $span,
        public string $text,
        public array $tags,
    ) {}

    /**
     * Finds the docblock immediately preceding a declaration.
     */
    public static function forDeclaration(SourceFile $file, Span $span): ?self
    {
        $closest = null;
        foreach ($file->getTrivia() as $trivia) {
            if ($trivia->kind !== TriviaKind::DocBlockComment || $trivia->span->end > $span->start) {
                continue;
            }
            if ($closest === null || $trivia->span->end > $closest->span->end) {
                $closest = $trivia;
            }
        }

        if ($closest === null) {
            return null;
        }

        $between = $file->getText(new Span($closest->span->end, $span->start));
        $between = preg_replace(pattern: '/#\[.*?\]/s', replacement: '', subject: $between) ?? $between;

        return trim(string: $between) === '' ? self::fromTrivia($file, $closest) : null;
    }

    /**
     * Returns every PHPDoc block in source order.
     *
     * @return list<self>
     */
    public static function all(SourceFile $file): array
    {
        $docblocks = [];
        foreach ($file->getTrivia() as $trivia) {
            if ($trivia->kind !== TriviaKind::DocBlockComment) {
                continue;
            }

            $docblocks[] = self::fromTrivia($file, $trivia);
        }

        return $docblocks;
    }

    /**
     * Returns the docblock content without delimiters or leading stars.
     */
    public function content(): string
    {
        $content = preg_replace(pattern: '/^\s*\/\*\*|\*\/\s*$/', replacement: '', subject: $this->text) ?? $this->text;
        $content = preg_replace(pattern: '/^\s*\*\s?/m', replacement: '', subject: $content) ?? $content;

        return trim(string: $content);
    }

    /**
     * Builds a parsed view from one Mago docblock trivia node.
     */
    private static function fromTrivia(SourceFile $file, Trivia $trivia): self
    {
        $text = $file->getText($trivia->span);
        $tags = [];
        preg_match_all(
            '/^[ \t]*\*[ \t]*(@[A-Za-z][A-Za-z0-9-]*)([ \t]*)([^\r\n]*)/m',
            $text,
            $matches,
            PREG_SET_ORDER | PREG_OFFSET_CAPTURE,
        );

        foreach ($matches as $match) {
            $nameStart = $trivia->span->start + $match[1][1];
            $whitespaceStart = $trivia->span->start + $match[2][1];
            $tags[] = new DocblockTag(
                name: $match[1][0],
                nameSpan: new Span($nameStart, $nameStart + strlen($match[1][0])),
                whitespace: $match[2][0],
                whitespaceSpan: new Span($whitespaceStart, $whitespaceStart + strlen($match[2][0])),
                value: rtrim(string: $match[3][0]),
            );
        }

        return new self($trivia->span, $text, $tags);
    }
}
