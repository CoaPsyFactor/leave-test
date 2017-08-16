<?php

namespace Src\Collections;

use Modules\Collection\ModelCollection\ModelCollection;
use Src\Models\User\UserModel;

class UserModelCollection extends ModelCollection
{

    /**
     * @return string
     */
    public function getObjectType()
    {

        return UserModel::class;
    }
}