<?php

namespace App\Traits;

use App\Facades\Converters;
use http\Exception\UnexpectedValueException;
use Illuminate\Http\Response;

trait HasAmountInMajorUnits
{
    /**
     * Return amount in minor units
     *
     * @return float
     */
    public function getAmountInMinorUnits(): float
    {
        if (!isset($this->amount)) {
            throw new UnexpectedValueException('Trait HasAmountInMajorUnits used in wrong place.', Response::HTTP_BAD_REQUEST);
        }

        return Converters::majorUnitsToMinorUnit($this->amount);
    }
}
