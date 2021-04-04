<?php

namespace musa11971\SortRequest\Exceptions;

use Exception;

class EloquentSortingException extends Exception
{
    /**
     * EloquentSortingException constructor.
     *
     * @param $message
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
