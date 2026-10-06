<?php
declare(strict_types=1);

// Mago source metadata only; this file must never be loaded by the application.
// CakePHP's concrete associations declare their target as T, while the
// inherited Association::getTarget() return annotation widens it to Table.
namespace Cake\ORM\Association;

use Cake\ORM\Association;
use Cake\ORM\Table;

/**
 * @template T of Table
 * @mixin T
 */
class BelongsTo extends Association
{
    /**
     * @return T
     */
    public function getTarget(): Table
    {
    }
}

/**
 * @template T of Table
 * @mixin T
 */
class BelongsToMany extends Association
{
    /**
     * @return T
     */
    public function getTarget(): Table
    {
    }
}

/**
 * @template T of Table
 * @mixin T
 */
class HasMany extends Association
{
    /**
     * @return T
     */
    public function getTarget(): Table
    {
    }
}

/**
 * @template T of Table
 * @mixin T
 */
class HasOne extends Association
{
    /**
     * @return T
     */
    public function getTarget(): Table
    {
    }
}
