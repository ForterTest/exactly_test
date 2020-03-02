<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Facades\Converters;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * @var array
     */
    protected $fillable = ['name', 'email', 'password',];

    /**
     * @var array
     */
    protected $hidden = ['password', 'remember_token',];

    /**
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @var array
     */
    protected $appends = ['balance_in_minor_units'];

    /**
     * @return float
     */
    public function getBalanceInMinorUnitsAttribute(): float
    {
        return Converters::majorUnitsToMinorUnit((int)$this->balance);
    }

}
