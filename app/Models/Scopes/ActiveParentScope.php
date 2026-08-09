<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ActiveParentScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $builder->whereHas('parent', fn ($q) => $q->where('is_active', true));
    }
}
