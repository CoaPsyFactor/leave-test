<?php

namespace Src\Models\Leave;

use Modules\Model\IModel;

interface ILeaveModel extends IModel
{

    const FIELD_ID = 'id';
    const FIELD_USER = 'user_id';
    const FIELD_START = 'start_timestamp';
    const FIELD_END = 'end_timestamp';
    const FIELD_STATUS = 'status';
    const FIELD_TYPE = 'type';

    const TYPE_WORKING_FROM_HOME = 'WFH';
    const TYPE_SICK = 'SICK';
    const TYPE_VACATION = 'VACATION';

    const STATUS_PENDING = 'PENDING';
    const STATUS_APPROVED = 'APPROVED';
    const STATUS_REJECTED = 'REJECTED';
}