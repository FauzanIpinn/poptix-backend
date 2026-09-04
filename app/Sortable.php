<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait Sortable
{
    protected function applySort(Builder $query, Request $request, array $allowed, string $default): Builder {
        $sort = (string) $request->query('sort', $default);
        $direction = 'asc';

        if (str_starts_with($sort, '-')) {
            $direction = 'desc';
            $sort = substr($sort, 1);
        }

        if (! in_array($sort, $allowed, true)) {
            $sort = ltrim($default, '-');
            $direction = str_starts_with($default, '-') ? 'desc' : 'asc';
        }

        return $query->orderBy($sort, $direction);
    }
}