<?php

namespace Domain\Types;

class Amount
{
    const MAJOR_MULTIPLIER = 100;

    /**
     * @var int
     */
    private $amountInMajorUnits;

    /**
     * @param float $amount
     * @return Amount
     */
    public function fromMinor(float $amount): Amount
    {
        $this->amountInMajorUnits = (int)bcmul($amount, self::MAJOR_MULTIPLIER);

        return $this;
    }

    /**
     * @param int $amount
     * @return Amount
     */
    public function fromMajor(int $amount): Amount
    {
        $this->amountInMajorUnits = $amount;

        return $this;
    }

    /**
     * @return float
     */
    public function getMinor(): float
    {
        return bcdiv($this->amountInMajorUnits, self::MAJOR_MULTIPLIER, 2);
    }

    /**
     * @return int
     */
    public function getMajor(): int
    {
        return $this->amountInMajorUnits;
    }

}
