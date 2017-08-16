<?php

use Modules\Collection\GenericCollection\GenericCollection;
use Src\Collections\UserModelCollection;
use Src\Models\User\IUserModel;
use Src\Models\User\UserModel;

require_once __DIR__ . '/../src/initialize.php';

try {

    $modelCollection = UserModelCollection::getNewInstance([\Src\Models\Leave\LeaveModel::getNewInstance()]);
} catch (\Modules\Exception\Exceptions\TypedCollectionException $exception) {

    var_dump('Following exception is expected', $exception->getMessage());
}

$modelCollection = UserModelCollection::getNewInstance([
    UserModel::getNewInstance(['id' => 1, 'name' => 'User 1', 'password' => 'sdasce3qonajdsk']),
    UserModel::getNewInstance(['id' => 'INVALID', 'name' => 'Invalid User ', 'password' => '']),
    UserModel::getNewInstance(['id' => 2, 'name' => 'User 2', 'password' => 'nkJSNcoin2q09h9v']),
    UserModel::getNewInstance(['id' => 3, 'name' => 'User 3', 'password' => 'ACS<>;l,3io0)']),
    UserModel::getNewInstance(['id' => 'INVALID', 'name' => 'Invalid User ', 'password' => '']),
    UserModel::getNewInstance(['id' => 4, 'name' => 'User 4', 'password' => 'asnj4btgirhoawh']),
    UserModel::getNewInstance(['id' => 5, 'name' => 'User 5', 'password' => 'riojadhae']),
    UserModel::getNewInstance(['id' => 'INVALID', 'name' => 'Invalid User ', 'password' => '']),
    UserModel::getNewInstance(['id' => 6, 'name' => 'User 6', 'password' => 'oijv009qacs']),
]);

$validIdFilterFunction = function (IUserModel $model) {

    // $model->id is also possible, but this looks cleaner and IDE works better with it ;)
    return is_numeric($model->getAttribute('id'));
};

$validEntries = $modelCollection->filter($validIdFilterFunction)->count();

var_dump(
    'ModelCollection is derivative of TypedCollection with possibility to fetch items from database',
    "{$validEntries} out of {$modelCollection->count()} entries are valid",
    $modelCollection->toArray()
);

$genericCollection = GenericCollection::getNewInstance([new stdClass(), 1, 'string as well :)', 2.2, true]);

var_dump('Generic collection can hold any kind of data', $genericCollection->toArray());
