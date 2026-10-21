<?php
declare(strict_types=1);

namespace App;

use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Association\BelongsToMany;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Association\HasOne;

final class AssociationTargetTypes
{
    /**
     * @param BelongsTo<AssociationTargetTable> $association
     */
    public function belongsTo(BelongsTo $association): AssociationTargetTable
    {
        return $association->getTarget();
    }

    /**
     * @param BelongsToMany<AssociationTargetTable> $association
     */
    public function belongsToMany(BelongsToMany $association): AssociationTargetTable
    {
        return $association->getTarget();
    }

    /**
     * @param HasMany<AssociationTargetTable> $association
     */
    public function hasMany(HasMany $association): AssociationTargetTable
    {
        return $association->getTarget();
    }

    /**
     * @param HasOne<AssociationTargetTable> $association
     */
    public function hasOne(HasOne $association): AssociationTargetTable
    {
        return $association->getTarget();
    }
}
