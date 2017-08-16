<?php

namespace Src\Controllers;

use Modules\Singleton\Singleton;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Src\Decorators\UserLeave\IUserLeaveDecorator;
use Src\Decorators\UserLeave\UserLeaveDecorator;
use Src\Models\Leave\ILeaveModel;
use Src\Models\Leave\LeaveModel;
use Src\Models\User\UserModel;

class LeaveController
{

    use Singleton;

    /** @var UserModel */
    private $_user;

    /** @var UserLeaveDecorator */
    private $_userLeaveDecorator;

    public function create(Request $request, Response $response)
    {

        $parameters = $request->getParsedBody();

        // This should be done by router :)
        if ($this->_isAnyParameterMissing(['user_id', 'start', 'end', 'type'], $parameters)) {

            return $response->withJson(['message' => 'Parameters mismatch', 'required' => ['user_id', 'start', 'end', 'type']], 400);
        }

        // This should be done using Middleware :)
        if (false === $this->_loadUserAndDecorators($parameters['user_id'])) {

            return $response->withJson(['message' => 'User not found'], 404);
        }

        $status = $this->_userLeaveDecorator->createLeave($parameters['start'], $parameters['end'], $parameters['type']);

        switch ($status) {
            case IUserLeaveDecorator::ERROR_NONE:

                return $response->withJson(['message' => 'Leave request sent'], 200);
            case IUserLeaveDecorator::ERROR_REQUEST_PENDING:

                return $response->withJson(['message' => 'You have pending leave request'], 409);
            case IUserLeaveDecorator::ERROR_MAX_DAYS:

                return $response->withJson(['message' => 'Maximum days exceeded'], 400);
            case IUserLeaveDecorator::ERROR_CREATION_FAILED:

                return $response->withJson(['message' => 'Failed to create leave request'], 503);
        }

        return $response->withJson(['message' => 'Something strange happened, please contact admins'], 500);
    }

    public function read(Request $request, Response $response)
    {

        $leave = LeaveModel::getSharedInstance()->find($request->getAttribute('route')->getArgument('id'));

        if (false === $leave->exists()) {

            return $response->withJson(['message' => 'Leave not found'], 404);
        }

        return $response->withJson(['message' => 'Leave request found', 'leave' => $leave->toArray()], 200);
    }

    public function update(Request $request, Response $response)
    {

        $leave = LeaveModel::getSharedInstance()->find($request->getAttribute('route')->getArgument('id'));

        if (false === $leave->exists()) {

            return $response->withJson(['message' => 'Leave request not found'], 404);
        }

        $acceptableFields = [ILeaveModel::FIELD_START => true, ILeaveModel::FIELD_END => true, ILeaveModel::FIELD_TYPE => true];

        $leave->setAttributes(array_intersect_key($request->getParsedBody(), $acceptableFields));

        $shouldUpdate = $leave->dirty();

        if ($shouldUpdate && $leave->save()) {

            return $response->withJson(['message' => 'Leave request updated', 'leave' => $leave->toArray()], 200);
        } else if (false === $shouldUpdate) {

            return $response->withJson(['message' => 'There is nothing to update'], 200);
        }

        return $response->withJson(['message' => 'Failed to update leave request'], 500);
    }

    public function delete(Request $request, Response $response)
    {

        $leave = LeaveModel::getSharedInstance()->find($request->getAttribute('route')->getArgument('id'));

        if (false === $leave->exists()) {

            return $response->withJson(['message' => 'Leave request not found'], 404);
        }
        
        if ($leave->delete()) {
            
            return $response->withJson(['message' => 'Leave request removed']);
        }
        
        return $response->withJson(['message' => 'Failed to remove leave request']);
    }

    /**
     *
     * validates difference between required parameters and all given parameters, if there is any difference TRUE is returned, otherwise its
     * FALSE
     *
     * @param array $requiredParameters
     * @param array $parameters
     * @return bool
     */
    private function _isAnyParameterMissing(array $requiredParameters = [], array $parameters = [])
    {

        return false === empty(array_diff_key(array_flip($requiredParameters), $parameters));
    }

    /**
     * @param int $userId
     * @return bool
     */
    private function _loadUserAndDecorators($userId)
    {

        $this->_user = UserModel::getSharedInstance()->find($userId);

        if (false === $this->_user->exists()) {

            return false;
        }

        $this->_userLeaveDecorator = $this->_user->decorate(UserLeaveDecorator::class);

        return true;
    }

}