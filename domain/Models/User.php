<?php

namespace Domain\Models;

use App\Entities\User as UserEntity;
use Domain\Types\Amount;
use Facades\Domain\Types\Amount as AmountType;

class User extends UserEntity
{
    /**
     * @var Amount
     */
    private $balanceAmount;

    /**
     * @return Amount
     */
    public function getBalanceAmount(): Amount
    {
        if (empty($this->balanceAmount)) {
            $this->balanceAmount = AmountType::fromMajor((int)$this->balance);
        }

        return $this->balanceAmount;
    }

    /**
     * @param Amount $amount
     */
    public function setBalanceAmount(Amount $amount): void
    {
        $this->balanceAmount = $amount;
        $this->balance = $amount->getMajor();
    }

    /**
     * @return float
     */
    public function getBalanceInMinorUnitsAttribute(): float
    {
        return $this->getBalanceAmount()->getMinor();
    }

    /**
     * @return int
     */
    public function getBalanceInMajorUnitsAttribute(): int
    {
        return $this->getBalanceAmount()->getMajor();
    }

}
