<?php

namespace Src\Models\User;

use Modules\Model\IModel;

interface IUserModel extends IModel
{

    const FIELD_ID = 'id';
    const FIELD_NAME = 'name';
    const FIELD_PASSWORD = 'password';
}