<?php
declare(strict_types=1);

namespace Cake\ORM {
    class Table
    {
    }

    /**
     * @mixin Table
     */
    abstract class Association
    {
        public function getTarget(): Table
        {
            return new Table();
        }
    }
}

namespace Cake\ORM\Association {
    use Cake\ORM\Association;
    use Cake\ORM\Table;

    /**
     * @template T of Table
     * @mixin T
     */
    class BelongsTo extends Association
    {
    }

    /**
     * @template T of Table
     * @mixin T
     */
    class BelongsToMany extends Association
    {
    }

    /**
     * @template T of Table
     * @mixin T
     */
    class HasMany extends Association
    {
    }

    /**
     * @template T of Table
     * @mixin T
     */
    class HasOne extends Association
    {
    }
}
