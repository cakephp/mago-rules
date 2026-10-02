<?php

declare(strict_types=1);

namespace App;

use Cake\I18n\Date;
use RuntimeException;

/** Imported references are required in types, construction and static calls. */
final class ClassImports
{
    /** @var \Cake\I18n\Date|null */
    private ?Date $date = null;

    /** Create a date with an explicit import. */
    public function date(): Date
    {
        $this->date = new Date();

        return $this->date;
    }

    /** Read the global exception class using an import. */
    public function exceptionClass(): string
    {
        return RuntimeException::class;
    }
}
