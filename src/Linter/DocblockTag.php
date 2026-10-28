<?php

declare(strict_types=1);

namespace MagoCakePHP\Linter;

use Mago\Sdk\Span;

/**
 * A line-oriented PHPDoc tag with exact source spans.
 */
final readonly class DocblockTag
{
    /**
     * Stores PHPDoc tag text and its editable source locations.
     */
    public function __construct(
        public string $name,
        public Span $nameSpan,
        public string $whitespace,
        public Span $whitespaceSpan,
        public string $value,
    ) {}
}
