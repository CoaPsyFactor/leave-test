<?php

namespace Modules\Exception;

/**
 *
 * To use proper error messaging in exceptions extend this class in custom exceptions instead of \Exception
 *
 * @package Core\Exceptions
 */
abstract class BaseException extends \Exception
{

    /** @var string[] */
    protected $_errors;

    /**
     * @param int $code Exception Code
     * @param string|null $message Additional message
     * @param \Exception|null $previous
     */
    public function __construct($code, $message = null, \Exception $previous = null)
    {

        $exceptionMessage = (isset($this->_errors[$code]) ? $this->_errors[$code] : 'Unknown error') . ($message ? ": {$message}" : '');

        parent::__construct($exceptionMessage, $code, $previous);
    }

}