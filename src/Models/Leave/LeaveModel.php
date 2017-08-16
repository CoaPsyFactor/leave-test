<?php

namespace Src\Models\Leave;

use Modules\Model\Model;

class LeaveModel extends Model implements ILeaveModel
{


    /**
     * @return string Table/collection name
     */
    public function table()
    {

        return 'leaves';
    }

    /**
     *
     * List of all available fields (columns)
     *
     * @return array
     */
    public function fields()
    {

        return [
            ILeaveModel::FIELD_ID, ILeaveModel::FIELD_USER,
            ILeaveModel::FIELD_START, ILeaveModel::FIELD_END,
            ILeaveModel::FIELD_TYPE, ILeaveModel::FIELD_STATUS
        ];
    }
}