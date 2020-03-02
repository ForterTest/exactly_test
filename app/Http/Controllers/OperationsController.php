<?php

namespace App\Http\Controllers;

use App\Exceptions\IncorrectAmountException;
use App\Exceptions\UserIdNotFoundException;
use Domain\Services\OperationsService;
use Domain\Models\User;
use Domain\Types\Amount;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Facades\Domain\Types\Amount as AmountType;

class OperationsController extends Controller
{
    /**
     * @var OperationsService
     */
    protected $operationsService;

    /**
     * OperationsController constructor.
     *
     * @param OperationsService $operationsService
     */
    public function __construct(OperationsService $operationsService)
    {
        $this->operationsService = $operationsService;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function sendToUser(Request $request): Response
    {
        $userId = (int)$request->post("uid", 0);
        $amountRaw = (float)$request->post("amount", 0);

        // Validation
        try {
            $this->validateOperationData($userId, $amountRaw);
        } catch (UserIdNotFoundException|IncorrectAmountException $e) {
            return response(['msg' => $e->getMessage()], $e->getCode());
        }

        $amount = AmountType::fromMinor($amountRaw);

        // Check user balance
        $requestUser = request()->user();
        if ($requestUser->balance < $amount->getMajor()) {
            return response(['msg' => 'Error: Insufficient funds'], Response::HTTP_BAD_REQUEST);
        }

        // Do the operation
        try {
            $this->operationsService->sendTo($requestUser, $userId, $amount);
        } catch (Exception $e) {
            return response(['msg' => 'Error: Service unavailable, please contact support'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response(['msg' => 'Success'], Response::HTTP_OK);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function sendToUsers(Request $request): Response
    {
        // Validate format, we expect JSON here
        $operations = json_decode($request->post("operations", ''), true);
        if (empty($operations)) {
            return response(['msg' => 'Error: List of operations has incorrect format'], Response::HTTP_BAD_REQUEST);
        }

        // Validate all the rows before actual operations
        try {
            $totalAmount = 0;
            foreach ($operations as &$operation) {
                $operation['uid'] = intval($operation['uid'] ?? 0);
                $operation['amount'] = floatval($operation['amount'] ?? 0);
                $operation['amount'] = (new Amount())->fromMinor($operation['amount']);

                $this->validateOperationData($operation['uid'], $operation['amount']->getMajor());
                $totalAmount += $operation['amount']->getMajor();
            }
        } catch (UserIdNotFoundException|IncorrectAmountException $e) {
            return response(['msg' => $e->getMessage()], $e->getCode());
        }

        // Check user balance
        $requestUser = request()->user();
        if ($requestUser->balance < $totalAmount) {
            return response(['msg' => 'Error: Insufficient funds'], Response::HTTP_BAD_REQUEST);
        }

        // Do the operations
        try {
            array_map(function ($operation) use ($requestUser) {
                $this->operationsService->sendTo($requestUser, $operation['uid'], $operation['amount']);
            }, $operations);
        } catch (Exception $e) {
            return response(['msg' => 'Error: Service unavailable, please contact support'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response(['msg' => 'Success'], Response::HTTP_OK);
    }

    /**
     * @param mixed $userId
     * @param mixed $amount
     * @return bool
     * @throws UserIdNotFoundException|IncorrectAmountException
     */
    private function validateOperationData($userId, $amount): bool
    {
        if ((float)$amount <= 0) {
            throw new IncorrectAmountException($amount);
        }

        /**
         * @var $user User
         */
        try {
            $user = User::find((int)$userId);
        } catch (Exception $e) {
            throw new UserIdNotFoundException($userId);
        }
        if (!$user) {
            throw new UserIdNotFoundException($userId);
        }

        return true;
    }
}
