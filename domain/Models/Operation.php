<?php

namespace Domain\Models;

use App\Entities\Operation as OperationEntity;
use Domain\Types\Amount;

class Operation extends OperationEntity
{
    /**
     * @var User
     */
    private $sourceUser;

    /**
     * @var User
     */
    private $targetUser;

    /**
     * @param User $user
     * @return Operation
     */
    public function setSourceUser(User $user): Operation
    {
        $this->source_user_id = $user->id;

        return $this;
    }

    /**
     * @param User $user
     * @return Operation
     */
    public function setTargetUser(User $user): Operation
    {
        $this->target_user_id = $user->id;

        return $this;
    }

    /**
     * @param Amount $amount
     * @return Operation
     */
    public function setAmount(Amount $amount): Operation
    {
        $this->amount = $amount->getMajor();

        return $this;
    }

    /**
     * @param Amount $amount
     * @return Operation
     */
    public function setNewSourceBalance(Amount $amount): Operation
    {
        $this->source_new_balance = $amount->getMajor();

        return $this;
    }

    /**
     * @param Amount $amount
     * @return Operation
     */
    public function setNewTargetBalance(Amount $amount): Operation
    {
        $this->target_new_balance = $amount->getMajor();

        return $this;
    }

}
