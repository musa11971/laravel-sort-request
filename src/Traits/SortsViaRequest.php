<?php

namespace musa11971\SortRequest\Traits;

use Illuminate\Foundation\Http\FormRequest;
use musa11971\SortRequest\Exceptions\BadTraitImplementation;
use musa11971\SortRequest\Rules\SortParameter;
use musa11971\SortRequest\SortableColumnCollection;

trait SortsViaRequest
{
    /**
     * Returns the columns that can be sorted on.
     *
     * @return array
     */
    abstract function getSortableColumns(): array;

    /** @var SortParameter $sortParameterRule */
    private SortParameter $sortParameterRule;

    /** @noinspection PhpUnhandledExceptionInspection */
    public function __construct()
    {
        if(!($this instanceof FormRequest))
            throw new BadTraitImplementation(__TRAIT__);

        // Create an instance of the validation rule
        $this->sortParameterRule = new SortParameter($this->getSortableColumns());
    }

    /**
     * Returns the validated sorting rules.
     *
     * @return array
     */
    function validatedSortingRules(): array
    {
        return $this->sortParameterRule->sortingRules;
    }

    /**
     * Returns the transformed sortable columns.
     *
     * @return SortableColumnCollection
     */
    function transformedSortableColumns(): SortableColumnCollection
    {
        return $this->sortParameterRule->sortableColumns;
    }

    /**
     * Get the validation rules for the sorting.
     *
     * @param string $parameterName
     * @return array
     */
    protected function sortingRules(string $parameterName = 'sort'): array
    {
        return [
            $parameterName => [$this->sortParameterRule]
        ];
    }
}
