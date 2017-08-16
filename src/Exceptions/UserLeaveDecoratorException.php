<?php

namespace Src\Exceptions;


use Modules\Exception\BaseException;

class UserLeaveDecoratorException extends BaseException
{

    const ERROR_INVALID_MODEL = 10001;
    const ERROR_INVALID_DAYS_VALUE = 10001;

    protected $_errors = [
        UserLeaveDecoratorException::ERROR_INVALID_MODEL => 'Object must be instance of UserModel. Got',
        UserLeaveDecoratorException::ERROR_INVALID_DAYS_VALUE => 'Invalid type for remaining days, expected number. Got'
    ];

}