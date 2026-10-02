<?php

declare(strict_types=1);

namespace App;

/** Imported references are required in types, construction and static calls. */
final class ClassImports
{
    /** @var \Cake\I18n\Date|null */
    private ?\Cake\I18n\Date $date = null;

    /** Create a date with an explicit import. */
    public function date(): \Cake\I18n\Date
    {
        $this->date = new \Cake\I18n\Date();

        return $this->date;
    }

    /** Read the global exception class using an import. */
    public function exceptionClass(): string
    {
        return \RuntimeException::class;
    }
}
