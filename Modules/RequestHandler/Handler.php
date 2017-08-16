<?php

namespace Modules\RequestHandler;


use Modules\GenericCollection\GenericCollection;
use Modules\Singleton\Singleton;

class Handler
{

    use Singleton;

    /** @var GenericCollection */
    private $_errors;

    /** @var GenericCollection */
    private $_controllers;

    private function __construct()
    {

        $this->_errors = GenericCollection::getNewInstance();

//        $this->
    }

    /**
     * @param string $action Query parameter "_action" identifier
     * @param string $controllerMethod "ClassName@method"
     * @param array $requiredParameters required query parameters
     * @param array $requiredBodyParameters required body (post) parameters
     * @return int status code
     */
    public function addController($action, $controllerMethod, array $requiredParameters = [], array $requiredBodyParameters = [])
    {

    }

}