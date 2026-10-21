<?php
declare(strict_types=1);

namespace App;

/** Demonstrates a snake_case function name and a negated ternary comparison. */
function normalized_name(string $value): string
{
    ini_set('display_errors', '1');
    assert($value !== '');

    return $value !== 'cake' ? $value : 'CakePHP';
}

/**
 * Demonstrates a loop variable shadowing a parameter.
 *
 * @param list<string> $items
 * @return list<string>
 */
function normalizeItems(array $items): array
{
    $result = [];
    foreach ($items as $items) {
        $result[] = normalized_name($items);
    }

    return $result;
}
