<?php

namespace Src\Decorators\UserLeave;

use Modules\Decorator\ITypedDecorator;
use Src\Collections\LeaveModelCollection;
use Src\Models\Leave\ILeaveModel;
use Src\Models\Leave\LeaveModel;
use Src\Models\User\UserModel;

class UserLeaveDecorator implements ITypedDecorator
{

    /** @var LeaveModelCollection */
    private $_collection;

    /** @var int */
    private $_remainingDays;

    /** @var UserModel */
    private $_user;

    /**
     *
     * Sets object that will be decorated
     *
     * @param $object
     * @return void
     */
    public function decorate($object)
    {

        $this->_user = $object;

        $this->_collection = LeaveModelCollection::getNewInstance();
    }

    /**
     *
     * @return LeaveModelCollection
     */
    public function getLeaves()
    {

        if ($this->_collection->isEmpty()) {

            return $this->_fetchLeaves();
        }

        return $this->_collection;
    }

    /**
     *
     * Retrieve how many vacation days does user have
     *
     * @return int
     */
    public function getRemainDays()
    {

        if (null === $this->_remainingDays) {

            $this->_calculateRemainingDays();
        }

        return $this->_remainingDays;
    }

    /**
     *
     * Retrieve all vacation leave requests that are still pending
     *
     * @return LeaveModelCollection
     */
    public function getPendingVacationRequests()
    {

        return $this->getLeaves()->filter(function (ILeaveModel $leaveModel) {

            return (
                $leaveModel->getAttribute('status') === ILeaveModel::STATUS_PENDING &&
                $leaveModel->getAttribute('type') === ILeaveModel::TYPE_VACATION
            );
        });
    }

    /**
     *
     * Validates remaining days for user, and if everything is okay creates leave request
     *
     * @param int $startTimestamp Start time in unix timestamp format
     * @param int $endTimestamp End date time in unix timestamp format
     * @param string $type
     * @return int
     */
    public function createLeave($startTimestamp, $endTimestamp, $type = ILeaveModel::TYPE_VACATION)
    {

        if (false === $this->getPendingVacationRequests()->isEmpty()) {

            return IUserLeaveDecorator::ERROR_REQUEST_PENDING;
        }

        $days = ($endTimestamp - $startTimestamp) / IUserLeaveDecorator::DAY_IN_SECONDS;

        if ($days > $this->getRemainDays() && ILeaveModel::TYPE_VACATION === $type) {

            return IUserLeaveDecorator::ERROR_MAX_DAYS;
        }

        $leave = LeaveModel::getNewInstance([
            'user_id' => (int)$this->_user->primaryValue(),
            'start_timestamp' => (int)$startTimestamp,
            'end_timestamp' => (int)$endTimestamp,
            'type' => $type,
            'status' => ILeaveModel::STATUS_APPROVED // This could be excluded as default value in db is 'PENDING' :)
        ], true);

        if (false === $leave->save()) {

            unset($leave);

            return IUserLeaveDecorator::ERROR_CREATION_FAILED;
        }

        $this->_collection->clear();

        return IUserLeaveDecorator::ERROR_NONE;
    }

    /**
     *
     * Retrieve model class name
     *
     * @return string
     */
    public function getObjectClass()
    {

        return UserModel::class;
    }

    /**
     * Calculates how many free days user has, and stores that value in variable
     */
    private function _calculateRemainingDays()
    {

        $usedDays = 0;

        $collection = $this->getLeaves()->filter(function (ILeaveModel $leaveModel) {

            return (
                $leaveModel->getAttribute('status') === ILeaveModel::STATUS_APPROVED &&
                $leaveModel->getAttribute('type') === ILeaveModel::TYPE_VACATION
            );
        });

        /** @var ILeaveModel $leaveModel */
        foreach ($collection as $leaveModel) {

            $startTime = (int)$leaveModel->getAttribute('start_timestamp');

            $endTime = (int)$leaveModel->getAttribute('end_timestamp');

            $usedDays += ($endTime - $startTime) / IUserLeaveDecorator::DAY_IN_SECONDS;
        }

        $this->_remainingDays = IUserLeaveDecorator::DEFAULT_MAX_VACATION_DAYS - ceil($usedDays);
    }

    /**
     *
     * @return LeaveModelCollection
     */
    private function _fetchLeaves()
    {

        $this->_collection->clear();

        $this->_collection->fetch(['user_id' => (int)$this->_user->getAttribute('id')]);

        return $this->_collection;
    }
}