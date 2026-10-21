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
     * @method T getTarget() Gets the table instance for the target side of the association.
     */
    class BelongsTo extends Association
    {
    }

    /**
     * @template T of Table
     * @mixin T
     * @method T getTarget() Gets the table instance for the target side of the association.
     */
    class BelongsToMany extends Association
    {
    }

    /**
     * @template T of Table
     * @mixin T
     * @method T getTarget() Gets the table instance for the target side of the association.
     */
    class HasMany extends Association
    {
    }

    /**
     * @template T of Table
     * @mixin T
     * @method T getTarget() Gets the table instance for the target side of the association.
     */
    class HasOne extends Association
    {
    }
}
