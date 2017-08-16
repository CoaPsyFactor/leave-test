<?php

namespace Modules\Exception\Exceptions;

use Modules\Exception\BaseException;

class ModelException extends BaseException
{

    const ERROR_INVALID_FIELD = 30001;
    const ERROR_MISSING_PRIMARY = 30002;

    protected $_errors = [
        ModelException::ERROR_INVALID_FIELD => 'Field is not valid. Field',
        ModelException::ERROR_MISSING_PRIMARY => 'Primary key is required'
    ];

}