<?php

namespace App;

use Domain\Traits\HasAmountInMajorUnits;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Operation extends Model
{
    use HasAmountInMajorUnits;

    /**
     * @var array
     */
    protected $fillable = [
        'source_user_id', 'target_user_id', 'amount', 'source_new_balance', 'target_new_balance',
    ];

    /**
     * @return HasOne
     */
    public function sourceUser(): HasOne
    {
        return $this->hasOne('App\User', 'id', 'source_user_id');
    }

    /**
     * @return HasOne
     */
    public function targetUser(): HasOne
    {
        return $this->hasOne('App\User', 'id', 'target_user_id');
    }

}
