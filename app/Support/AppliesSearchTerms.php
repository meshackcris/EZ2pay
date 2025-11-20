<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;

trait AppliesSearchTerms
{
    /**
     * Apply grouped LIKE conditions for each whitespace-separated token.
     *
     * @param  Builder  $query    Base query builder being filtered.
     * @param  string   $search   Raw search input.
     * @param  callable $callback Receives ($subQuery, $term) and must add the OR conditions.
     */
    protected function applySearchTerms(Builder $query, ?string $search, callable $callback): void
    {
        if (blank($search)) {
            return;
        }

        $terms = preg_split('/\s+/', trim($search)) ?: [];

        foreach ($terms as $term) {
            if ($term === '') {
                continue;
            }

            $query->where(function (Builder $subQuery) use ($callback, $term) {
                $callback($subQuery, $term);
            });
        }
    }
}
