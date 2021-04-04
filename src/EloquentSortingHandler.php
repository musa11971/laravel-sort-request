<?php

namespace musa11971\SortRequest;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use musa11971\SortRequest\Exceptions\EloquentSortingException;

class EloquentSortingHandler
{
    /** @var Request $request */
    private Request $request;

    /** @var Builder $builder */
    private Builder $builder;

    /** @var array $rules */
    private array $rules;

    /** @var SortableColumnCollection $sortableColumns */
    private SortableColumnCollection $sortableColumns;

    public function __construct($request, $builder)
    {
        $this->request = $request;
        $this->builder = $builder;

        // Get the rules and sortable columns from the request
        $this->rules = $this->request->validatedSortingRules();
        $this->sortableColumns = $this->request->transformedSortableColumns();
    }

    /**
     * Handles the actual Eloquent sorting and returns the builder.
     *
     * @return Builder
     * @throws EloquentSortingException
     */
    function handle(): Builder
    {
        // Check whether the request uses the trait before continuing
        $this->checkForTrait();

        // Loop through every rule and handle it individually
        foreach($this->rules as $rule)
        {
            // Find the relevant sortable column for this rule
            $sortableColumn = $this->sortableColumns->find($rule['column']);

            // Handle the sorting actions for the column
            $this->handleSort($sortableColumn, $rule, $this->builder);
        }

        // Pass back the builder so that it can be chained
        return $this->builder;
    }

    /**
     * Handle the sorting of a column.
     *
     * @param SortableColumn $sortableColumn
     * @param array          $rule
     * @param Builder        $builder
     */
    private function handleSort(SortableColumn $sortableColumn, array $rule, Builder &$builder)
    {
        // Call the sorter's apply method
        $builder = $sortableColumn->sorter->apply(request(), $builder, $rule['direction']);
    }

    /**
     * Checks whether the request uses the trait.
     *
     * @throws EloquentSortingException
     */
    private function checkForTrait()
    {
        if(!method_exists($this->request, 'validatedSortingRules')) {
            $requestClass = get_class($this->request);

            throw new EloquentSortingException("{$requestClass} is not using the SortsViaRequest trait.");
        }
    }
}
