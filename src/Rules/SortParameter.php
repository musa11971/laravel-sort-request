<?php

namespace musa11971\SortRequest\Rules;

use Illuminate\Contracts\Validation\Rule;
use musa11971\SortRequest\Sorters\DefaultSorter;
use musa11971\SortRequest\SortableColumn;
use musa11971\SortRequest\SortableColumnCollection;
use musa11971\SortRequest\Support\Foundation\Contracts\Sorter;

class SortParameter implements Rule
{
    const SORT_PATTERN = "~(?'column'[a-zA-Z0-9-_]*)\((?'direction'[a-zA-Z0-9-_]*)\)~";

    /** @var string $failure */
    private string $failure = 'The :attribute is invalid.';

    /** @var SortableColumnCollection $sortableColumns */
    public SortableColumnCollection $sortableColumns;

    /** @var array $sortingRules */
    public array $sortingRules = [];

    /**
     * @param array $sortableColumns
     */
    public function __construct(array $sortableColumns)
    {
        $this->sortableColumns = $this->transformSortableColumns($sortableColumns);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        // Go through the value and take out all the rules
        $rules = [];

        while($rule = $this->takeNextSortRule($value)) {
            $rules[] = $rule;
        }

        // Fail the validation if no rules were passed
        if(!count($rules))
            return $this->fail('The :attribute contains no valid sorting rules.');

        foreach($rules as $rule) {
            $column = $rule['column'];
            $direction = $rule['direction'];

            // Validate that the column can be sorted on
            if(!$this->sortableColumns->isValidColumn($column))
                return $this->fail("'{$column}' cannot be sorted on.");

            // Validate that the direction is valid
            if(!$this->sortableColumns->isValidDirectionForColumn($column, $direction))
                return $this->fail("'{$direction}' is an invalid sorting direction for '{$column}'");

            // Validate whether the column is not present more than once
            $doubleRule = array_filter($rules, function($rule) use($column) {
                return ($rule['column'] == $column);
            });

            if(count($doubleRule) > 1)
                return $this->fail("'{$direction}' should only have one sort rule.");
        }

        $this->sortingRules = $rules;

        return true;
    }

    /**
     * Makes the validation rule fail and sets the failure message.
     *
     * @param string $failure
     * @return bool
     */
    private function fail(string $failure): bool
    {
        $this->failure = $failure;

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return $this->failure;
    }

    /**
     * Attempts to find and return the next sort rule in a subject string.
     *
     * @param $subject
     * @return array|null
     */
    private function takeNextSortRule(&$subject): ?array
    {
        if(preg_match(self::SORT_PATTERN, $subject, $match))
        {
            // Remove the match from the string
            $subject = preg_replace(self::SORT_PATTERN, '', $subject, 1);

            return [
                'column'    => $match['column'],
                'direction' => $match['direction']
            ];
        }

        return null;
    }

    /**
     * Transforms the format used in form requests to a SortableColumnCollection.
     *
     * @param array $columns
     *
     * @return SortableColumnCollection
     */
    private function transformSortableColumns(array $columns): SortableColumnCollection
    {
        $collection = new SortableColumnCollection();

        foreach($columns as $left => $right) {
            // ['columnName' => Sorter::class]; notation
            if (is_string($left)) {
                // Redefine variables for clarity
                $columnName = $left;
                $sortClass = $right;

                // Create the sorter
                /** @var Sorter $sorter */
                $sorter = new $sortClass;
                $sorter->column = $columnName;

                // Add the SortableColumn
                $collection->add(new SortableColumn($columnName, $sorter));
            }
            // ['columnName']; notation
            else {
                // Redefine variables for clarity
                $columnName = $right;

                // Create the default sorter
                $sorter = new DefaultSorter();
                $sorter->column = $columnName;

                // Add the SortableColumn
                $collection->add(new SortableColumn($columnName, $sorter));
            }
        }

        return $collection;
    }
}
