<?php

namespace Modules\Collection\ModelCollection;

use Modules\Collection\TypedCollection\ITypedCollection;

interface IModelCollection extends ITypedCollection
{

    /**
     *
     * Fetch from database and fill current instance of collection with hydrated models of given type
     *
     * @param array $criteria
     * @return $this|IModelCollection
     */
    public function fetch(array $criteria);
}