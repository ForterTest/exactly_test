<?php

namespace Domain\Services;

use Domain\Models\Operation;
use Domain\Repositories\UserRepository;
use Facades\Domain\Types\Amount as AmountType;

class SubscriptionService
{
    const SUBSCRIPTION_FEE = 100;

    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function chargeFees()
    {
        $users = $this->userRepository->getAll();
        $users->each(function ($user) {
            $user->lockForUpdate();
            $user->balance -= self::SUBSCRIPTION_FEE;
            $user->save();


            // Store the operation in history
            $operation = new Operation();
            $operation
                ->setSourceUser($user)
                ->setAmount(AmountType::fromMajor(self::SUBSCRIPTION_FEE))
                ->setNewSourceBalance(AmountType::fromMajor($user->balance))
                ->save();
        });
    }
}
