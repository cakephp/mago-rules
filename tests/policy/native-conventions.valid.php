<?php
declare(strict_types=1);

namespace App;

/** Normalizes a name using permitted CakePHP runtime configuration and assertions. */
function normalizedName(string $value): string
{
    ini_set('display_errors', '1');
    assert($value !== '');

    return $value === 'cake' ? 'CakePHP' : $value;
}

/**
 * Normalizes items without shadowing the parameter.
 *
 * @param list<string> $items
 * @return list<string>
 */
function normalizeItems(array $items): array
{
    $result = [];
    foreach ($items as $item) {
        $result[] = normalizedName($item);
    }

    return $result;
}
