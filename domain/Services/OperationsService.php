<?php

namespace Domain\Services;

use Domain\Models\Operation;
use Domain\Models\User;
use Domain\Repositories\UserRepository;
use Domain\Types\Amount;
use Illuminate\Support\Facades\DB;
use Facades\Domain\Types\Amount as AmountType;

class OperationsService
{
    /**
     * @param User $sourceUser
     * @param int $targetUserId
     * @param Amount $amount
     */
    public function sendTo(User $sourceUser, int $targetUserId, Amount $amount): void
    {
        DB::transaction(function () use ($sourceUser, $targetUserId, $amount) {
            // Adjust balance for source user
            $sourceUser->lockForUpdate();
            $sourceNewBalance = $sourceUser->balance - $amount->getMajor();
            $sourceUser->balance = $sourceNewBalance;
            $sourceUser->save();

            // Adjust balance for target
            $user = (new UserRepository())->getById($targetUserId);
            $user->lockForUpdate();
            $targetNewBalance = $user->balance + $amount->getMajor();
            $user->balance = $targetNewBalance;
            $user->save();

            // Store the operation in history
            $operation = new Operation();
            $operation
                ->setSourceUser($sourceUser)
                ->setTargetUser($user)
                ->setAmount($amount)
                ->setNewSourceBalance(AmountType::fromMajor($sourceNewBalance))
                ->setNewTargetBalance(AmountType::fromMajor($targetNewBalance))
                ->save();
        });
    }

}
