<?php

namespace Modules\Decorator;

interface IDecorator
{

    /**
     *
     * Sets object that will be decorated
     *
     * @param $object
     * @return void
     */
    public function decorate($object);
}