<?php
declare(strict_types=1);

namespace App;

use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Association\BelongsToMany;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Association\HasOne;
use Cake\ORM\Table;

final class OtherTargetTable extends Table
{
}

final class InvalidAssociationTargetTypes
{
    /**
     * @param BelongsTo<AssociationTargetTable> $association
     */
    public function belongsTo(BelongsTo $association): OtherTargetTable
    {
        return $association->getTarget();
    }

    /**
     * @param BelongsToMany<AssociationTargetTable> $association
     */
    public function belongsToMany(BelongsToMany $association): OtherTargetTable
    {
        return $association->getTarget();
    }

    /**
     * @param HasMany<AssociationTargetTable> $association
     */
    public function hasMany(HasMany $association): OtherTargetTable
    {
        return $association->getTarget();
    }

    /**
     * @param HasOne<AssociationTargetTable> $association
     */
    public function hasOne(HasOne $association): OtherTargetTable
    {
        return $association->getTarget();
    }
}
