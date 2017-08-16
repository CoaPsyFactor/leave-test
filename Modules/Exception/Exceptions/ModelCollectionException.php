<?php

namespace Modules\Exception\Exceptions;

use Modules\Exception\BaseException;

class ModelCollectionException extends BaseException
{

    const ERROR_INVALID_MODEL_TYPE = 50001;
    const ERROR_UNKNOWN = 50002;

    protected $_errors = [

        ModelCollectionException::ERROR_INVALID_MODEL_TYPE => 'Model is wrong type',
        ModelCollectionException::ERROR_UNKNOWN => 'Unknown error occur during entities fetch fetch. In'
    ];

}