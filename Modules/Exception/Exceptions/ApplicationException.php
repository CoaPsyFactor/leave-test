<?php

namespace Modules\Exception\Exceptions;


use Modules\Exception\BaseException;

class ApplicationException extends BaseException
{

    const ERROR_MISSING_CONFIG = 10001;
    const ERROR_INVALID_CONFIG = 10002;
    const ERROR_CONFIG_MISSING_FIELD = 10003;

    protected $_errors = [
        ApplicationException::ERROR_MISSING_CONFIG => 'Configuration file not found. Path',
        ApplicationException::ERROR_INVALID_CONFIG => 'Configuration file contains invalid JSON. Error',
        ApplicationException::ERROR_CONFIG_MISSING_FIELD => 'Configuration tree is missing required field. Field'
    ];


}