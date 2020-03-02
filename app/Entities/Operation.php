<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Operation extends Model
{
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
        return $this->hasOne('App\Entities\User', 'id', 'source_user_id');
    }

    /**
     * @return HasOne
     */
    public function targetUser(): HasOne
    {
        return $this->hasOne('App\Entities\User', 'id', 'target_user_id');
    }

}
