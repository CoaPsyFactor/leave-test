<?php

namespace Modules\Collection\TypedCollection;

use Modules\Collection\GenericCollection\IGenericCollection;

interface ITypedCollection extends IGenericCollection
{

    /**
     * @return string
     */
    public function getObjectType();
}