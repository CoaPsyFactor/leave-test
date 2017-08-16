<?php

namespace Src\Models\User;

use Modules\Model\Model;

class UserModel extends Model implements IUserModel
{

    protected $_hidden = [IUserModel::FIELD_PASSWORD];

    /**
     *
     * List of all available fields (columns)
     *
     * @return array
     */
    public function fields()
    {

        return [IUserModel::FIELD_ID, IUserModel::FIELD_NAME, IUserModel::FIELD_PASSWORD];
    }

    /**
     * @return string Table/collection name
     */
    public function table()
    {

        return 'users';
    }
}