<?php

namespace musa11971\SortRequest\Tests\Support\Sorters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use musa11971\SortRequest\Support\Foundation\Contracts\Sorter;

class ItemWeightSorter extends Sorter
{
    /**
     * Applies the sorter to the Eloquent builder.
     *
     * @param Request $request
     * @param Builder $builder
     * @param string  $direction
     *
     * @return Builder
     */
    public function apply(Request $request, Builder $builder, string $direction): Builder
    {
        if($direction == 'heavy')
            $builder->orderBy('stackSize', 'desc');
        else
            $builder->orderBy('stackSize', 'asc');

        return $builder;
    }

    /**
     * Returns the directions that can be sorted on.
     *
     * @return array
     */
    public function getDirections(): array
    {
        return ['heavy', 'light'];
    }
}
