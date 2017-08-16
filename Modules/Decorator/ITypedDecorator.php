<?php

namespace Modules\Decorator;

interface ITypedDecorator extends IDecorator
{
    /**
     *
     * Retrieve model class name
     *
     * @return string
     */
    public function getObjectClass();
}