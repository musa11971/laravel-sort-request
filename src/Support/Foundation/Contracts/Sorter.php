<?php

namespace musa11971\SortRequest\Support\Foundation\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class Sorter
{
    /** @var string $column */
    public string $column;

    /**
     * Applies the sorter to the Eloquent builder.
     *
     * @param Request $request
     * @param Builder $builder
     * @param string  $direction
     *
     * @return Builder
     */
    abstract public function apply(Request $request, Builder $builder, string $direction): Builder;

    /**
     * Returns the directions that can be sorted on.
     *
     * @return array
     */
    public function getDirections(): array
    {
        return [];
    }
}
