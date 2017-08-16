<?php

namespace Modules\Exception\Exceptions;

use Modules\Exception\BaseException;

class DatabaseException extends BaseException
{

    const ERROR_PREPARING_QUERY = 20001;
    const ERROR_EXECUTING_QUERY = 20002;

    protected $_errors = [
        DatabaseException::ERROR_PREPARING_QUERY => 'Failed to prepare query',
        DatabaseException::ERROR_EXECUTING_QUERY => 'Failed to execute query',
    ];

}