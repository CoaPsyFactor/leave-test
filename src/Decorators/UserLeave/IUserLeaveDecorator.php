<?php

namespace Src\Decorators\UserLeave;

interface IUserLeaveDecorator
{

    const DAY_IN_SECONDS = 86400;
    const DEFAULT_MAX_VACATION_DAYS = 20;

    const ERROR_NONE = 0;
    const ERROR_REQUEST_PENDING = 1;
    const ERROR_MAX_DAYS = 2;
    const ERROR_CREATION_FAILED = 3;
}