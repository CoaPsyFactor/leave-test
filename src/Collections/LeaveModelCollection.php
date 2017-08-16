<?php

namespace Src\Collections;

use Modules\Collection\ModelCollection\ModelCollection;
use Src\Models\Leave\LeaveModel;

class LeaveModelCollection extends ModelCollection
{

    /**
     * @return string
     */
    public function getObjectType()
    {

        return LeaveModel::class;
    }
}