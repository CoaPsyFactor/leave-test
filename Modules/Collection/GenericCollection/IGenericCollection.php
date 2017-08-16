<?php

namespace Modules\Collection\GenericCollection;

interface IGenericCollection extends \Iterator, \ArrayAccess
{

    /**
     *
     * Retrieve amount of items in collection
     *
     * @return int
     */
    public function count();

    /**
     *
     * Checks whether item bag is empty or not
     *
     * @return bool
     */
    public function isEmpty();

    /**
     * @param \Closure $filterCallback Returns Boolean if its TRUE item will be append to result array, otherwise it will be ignored
     * @return mixed
     */
    public function filter(\Closure $filterCallback);

    /**
     *
     * Clears out collection
     *
     * @return void
     */
    public function clear();

    /**
     *
     * Returns collection in type of array
     *
     * @return array
     */
    public function toArray();
}