<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait SearchableTrait
{
    public function scopeSearchData(Builder $query, ?string $term, array $columns = []): Builder
    {
        if (!$term) {
            return $query;
        }

        return $query->where(function ($q) use ($term, $columns) {
            foreach ($columns as $column) {
                $q->orWhere($column, 'like', "%$term%");
            }
        });
    }
}