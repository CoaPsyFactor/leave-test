<?php

namespace Modules\Singleton;

/**
 *
 * Even though "Singleton" is trait, we use static $_instances as array that matches [CLASS NAME]=>ClassObject.
 * This is done to achieve using traits from children classes
 *
 * @package Modules\Singleton
 */
trait Singleton
{

    /** @var array */
    protected static $_instances = [];

    /**
     *
     * Returns singleton instance of object using "func_get_args" to retrieve arguments if new instance is required
     *
     * @return $this
     */
    public static function getSharedInstance()
    {

        return static::getSharedInstanceArgs(func_get_args());
    }

    /**
     *
     * Returns singleton instance of object using parameter array as arguments if new instance is required
     *
     * @param array $arguments
     * @return mixed
     */
    public static function getSharedInstanceArgs(array $arguments)
    {

        if (empty(static::$_instances[static::class])) {

            static::$_instances[static::class] = static::getNewInstanceArgs($arguments);
        }

        return static::$_instances[static::class];
    }

    /**
     * @return $this
     */
    public static function getNewInstance()
    {

        return static::getNewInstanceArgs(func_get_args());
    }


    /**
     *
     * Returns new instance of class event if constructor is not accessible (private/protected)
     *
     * @param array $arguments
     * @return $this
     */
    public static function getNewInstanceArgs(array $arguments = [])
    {

        $reflection = new \ReflectionClass(static::class);

        $instance = $reflection->newInstanceWithoutConstructor();

        $constructor = $reflection->getConstructor();

        if ($constructor instanceof \ReflectionMethod) {

            $constructor->setAccessible(true);

            $constructor->invokeArgs($instance, $arguments);
        }

        return $instance;
    }

}