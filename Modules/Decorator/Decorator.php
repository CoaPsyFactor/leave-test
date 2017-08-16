<?php

namespace Modules\Decorator;

use Modules\Exception\Exceptions\DecoratorException;

abstract class Decorator
{

    /**
     *
     * Creates new decorator
     *
     * @param string $decoratorClassName
     * @param mixed $object
     * @param array $decoratorArgs
     * @return IDecorator
     * @throws DecoratorException
     */
    public static function decorate($decoratorClassName, $object, $decoratorArgs = [])
    {

        if (false === class_exists($decoratorClassName)) {

            throw new DecoratorException(DecoratorException::ERROR_DECORATOR_NOT_FOUND, $decoratorClassName);
        }

        /** @var IDecorator $decorator */
        $decorator = (new \ReflectionClass($decoratorClassName))->newInstanceArgs($decoratorArgs);

        if (false === $decorator instanceof IDecorator) {

            throw new DecoratorException(DecoratorException::ERROR_DECORATOR_NOT_VALID, $decoratorClassName);
        }

        if ($decorator instanceof ITypedDecorator) {

            self::_validateDecoratorObjectType($decorator, $object);
        }

        $decorator->decorate($object);

        return $decorator;
    }

    /**
     *
     * Validates type of object that will be passed to decorator if everything is good
     *
     * @param ITypedDecorator $decorator
     * @param mixed $object
     * @throws DecoratorException
     */
    private static function _validateDecoratorObjectType(ITypedDecorator $decorator, $object)
    {

        if (false === is_object($object)) {

            throw new DecoratorException(DecoratorException::ERROR_INVALID_OBJECT_TYPE, gettype($object));
        }

        if (false === is_a($object, $decorator->getObjectClass())) {

            $class = get_class($object);

            throw new DecoratorException(
                DecoratorException::ERROR_OBJECT_TYPE_MISMATCH, "Expected {$decorator->getObjectClass()} got {$class}"
            );
        }
    }
}