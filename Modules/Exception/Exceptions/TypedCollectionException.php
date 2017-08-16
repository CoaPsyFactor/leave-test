<?php

namespace Modules\Exception\Exceptions;

use Modules\Exception\BaseException;

class TypedCollectionException extends BaseException
{

    const ERROR_INVALID_OBJECT = 70001;
    const ERROR_INVALID_OBJECT_TYPE = 70002;

    protected $_errors = [
        TypedCollectionException::ERROR_INVALID_OBJECT => 'Parameter must be object. Got',
        TypedCollectionException::ERROR_INVALID_OBJECT_TYPE => 'Invalid object type'
    ];

}