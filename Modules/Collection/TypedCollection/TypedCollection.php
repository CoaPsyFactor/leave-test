<?php

namespace Modules\Collection\TypedCollection;

use Modules\Collection\GenericCollection\GenericCollection;
use Modules\Exception\Exceptions\TypedCollectionException;

abstract class TypedCollection extends GenericCollection implements ITypedCollection
{

    /**
     *
     * Append model to collection array
     *
     * @param mixed $offset
     * @param mixed $value
     * @throws TypedCollectionException
     */
    public function offsetSet($offset, $value)
    {

        if (false === is_object($value)) {

            throw new TypedCollectionException(
                TypedCollectionException::ERROR_INVALID_OBJECT, gettype($value)
            );
        }

        if (false === is_a($value, $this->getObjectType())) {

            $valueClass = get_class($value);

            throw new TypedCollectionException(
                TypedCollectionException::ERROR_INVALID_OBJECT_TYPE, "Expected {$this->getObjectType()} got {$valueClass}"
            );
        }

        parent::offsetSet($offset, $value);
    }
}