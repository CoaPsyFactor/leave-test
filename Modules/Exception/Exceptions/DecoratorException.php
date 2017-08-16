<?php

namespace Modules\Exception\Exceptions;

use Modules\Exception\BaseException;

class DecoratorException extends BaseException
{

    const ERROR_DECORATOR_NOT_FOUND = 40001;
    const ERROR_DECORATOR_NOT_VALID = 40002;
    const ERROR_INVALID_OBJECT_TYPE = 40003;
    const ERROR_OBJECT_TYPE_MISMATCH = 40004;


    protected $_errors = [
        DecoratorException::ERROR_DECORATOR_NOT_FOUND => 'Decorator class not found. Class',
        DecoratorException::ERROR_DECORATOR_NOT_VALID => 'Class does not implement IDecorator interface. Class',
        DecoratorException::ERROR_INVALID_OBJECT_TYPE => 'Decorator "decorate" parameter is expected to be object',
        DecoratorException::ERROR_OBJECT_TYPE_MISMATCH => 'Typed Decorator object mismatch'
    ];


}