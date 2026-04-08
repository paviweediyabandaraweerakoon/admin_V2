<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait SearchableTrait
 *
 * Provides a reusable scope for searching across multiple columns and relationships in Eloquent models.
 */
trait SearchableTrait
{
    public function scopeSearchData(Builder $query, ?string $term, array $columns = [], array $relations = []): Builder
    {
        if (!$term) {
            return $query;
        }

        return $query->where(function ($q) use ($term, $columns, $relations) {
            // Direct columns search
            foreach ($columns as $column) {
                $q->orWhere($column, 'like', "%$term%");
            }

            // Related models search
            foreach ($relations as $relation => $relColumns) {
                $q->orWhereHas($relation, function ($relQuery) use ($term, $relColumns) {
                    $relQuery->where(function ($subQ) use ($term, $relColumns) {
                        foreach ($relColumns as $relCol) {
                            $subQ->orWhere($relCol, 'like', "%$term%");
                        }
                    });
                });
            }
        });
    }
}