<?php

/**
 * Behold the CHAOS :)
 *
 * As implementation of actual workflow doesn't matter as much as structure does, routing and response handling is all done here
 * No fancy OOP request/response nor routing, plain PHP here
 *
 * On following link could my OOP implementation of request, response and routing be found and also some things I borrowed from there
 * such as "Model", "Singleton" and "Database" classes
 *
 * https://bitbucket.org/warhcrew/actionhandler/src/910f229950409594db26e1aa4ee0da4ea8e1407b/Core/Libs/?at=master
 *
 * NOTE: I'm the only developer on project from link, there for because of little spare time its still under heavy development :)
 *
 *
 * A bit about current thingy -
 *
 * Couple of design patterns are present here, patterns like Singleton (OFC), Decorators, Composite, Iterator... maybe some other as well
 * that I did not realized :)
 *
 * There are collections, more precise there are three types of collections, GenericCollection, TypedCollection and ModelCollection.
 * ModelCollection is derivative of TypedCollection that beside GenericCollection and TypedCollection behaviours it adds functionality to
 * fetch items from db, TypedCollection is derivative of GenericCollection and it gives way to limits appending (added) items to defined
 * type (class name) and GenericCollection gives us way to store data into it, filter, count etc
 * (planning on implementing reduction as well)
 *
 * Next things is "Model", we have abstract and actual model, actual model is derivative of abstract model, and its used to describe entity,
 * to provide script with entity table name, available fields, hidden fields etc, and abstract model is where all magic regarding saving,
 * fetching and removing happens. Models also provide us with safe singleton way of instantiating and using decorators for single entity.
 *
 * "Decorator" class is used to decorate custom "IDecorator" with given object, there are two types of "IDecorator" one is "IDecorator" other
 * is "ITypedDecorator" which is derivative of "IDecorator", depending on implemented interfaces "Decorator" will choose to validates
 * input object before decorating around it.
 *
 * There is "Database", Database is more or less facade for PDO, that gives us easy way of executing queries, binding query values and
 * retrieving desire data, fetch will return 1 record, fetchAll multiple records, store will return insert id if available otherwise number
 * of affected row, delete will always return number of affected rows.
 *
 * For routing I choose to use "Slim" framework
 *
 */

$app = new Slim\App(['settings' => [
    'displayErrorDetails' => true,
],]);

$app->get('/leave/{id}', \Src\Controllers\LeaveController::class . ':read');
$app->post('/leave', \Src\Controllers\LeaveController::class . ':create');
$app->patch('/leave/{id}', \Src\Controllers\LeaveController::class . ':update');
$app->delete('/leave/{id}', \Src\Controllers\LeaveController::class . ':delete');

$app->run();