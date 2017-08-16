<?php

require_once __DIR__ . '/../src/initialize.php';

/** @var \Src\Models\User\IUserModel $user */
$user = \Src\Models\User\UserModel::getSharedInstance()->find(1);

/** @var \Src\Decorators\UserLeave\UserLeaveDecorator $userDecorator */
$userDecorator = $user->decorate(\Src\Decorators\UserLeave\UserLeaveDecorator::class);

$start = time() - (mt_rand(1, 5) * \Src\Decorators\UserLeave\IUserLeaveDecorator::DAY_IN_SECONDS);
$end = time() + (mt_rand(0, 5) * \Src\Decorators\UserLeave\IUserLeaveDecorator::DAY_IN_SECONDS);

$fromToString = 'From ' . date('r', $start) . ' to ' . date('r', $end);

var_dump("Trying to request leave | {$fromToString}");

$status = $userDecorator->createLeave($start, $end);

switch ($status) {
    case \Src\Decorators\UserLeave\IUserLeaveDecorator::ERROR_NONE:

        var_dump('Leave Request Sent');
        break;
    case \Src\Decorators\UserLeave\IUserLeaveDecorator::ERROR_REQUEST_PENDING:

        var_dump('You already have pending vacation request');
        break;

    case \Src\Decorators\UserLeave\IUserLeaveDecorator::ERROR_MAX_DAYS:

        var_dump(
            "Maximum numbers of vacation days exceeded, try less days. You have {$userDecorator->getRemainDays()} days left"
        );

        break;

    case \Src\Decorators\UserLeave\IUserLeaveDecorator::ERROR_CREATION_FAILED:

        var_dump('For some reason vacation request failed to create');
        break;
    default:

        var_dump('Some weird sh*t happened here');
        break;
}