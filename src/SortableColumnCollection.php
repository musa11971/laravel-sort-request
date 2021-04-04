<?php

namespace musa11971\SortRequest;

use Illuminate\Support\Collection;

class SortableColumnCollection extends Collection
{
    /**
     * Checks whether the given column name can be sorted on.
     *
     * @param string $column
     *
     * @return bool
     */
    function isValidColumn(string $column): bool
    {
        $column = $this->find($column);

        return $column !== null;
    }

    /**
     * Checks whether the given direction is a valid one for the column.
     *
     * @param string $column
     * @param string $direction
     *
     * @return bool
     */
    function isValidDirectionForColumn(string $column, string $direction): bool
    {
        $column = $this->find($column);

        if($column) {
            return in_array($direction, $column->sorter->getDirections());
        }

        return false;
    }

    /**
     * Returns the SortableColumn with the given name or null if not found.
     *
     * @param string $column
     * @return SortableColumn|null
     */
    function find(string $column): ?SortableColumn
    {
        foreach($this->items as $item) {
            if($item->name === $column) return $item;
        }

        return null;
    }
}
