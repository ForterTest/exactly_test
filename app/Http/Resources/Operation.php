<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Operation extends JsonResource
{
    /**
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        /**
         * @var $this \App\Entities\Operation
         */

        return [
            'id' => $this->id,
            'source_user' => $this->sourceUser()->id,
            'target_user' => $this->targetUser()->id,
            'amount' => $this->getAmountInMinorUnits(),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
