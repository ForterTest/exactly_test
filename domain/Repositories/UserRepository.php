<?php

namespace Domain\Repositories;

use Domain\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository
{
    /**
     * @param int $userId
     * @return User
     */
    public function getById(int $userId): User
    {
        return User::find($userId);
    }

    /**
     * @param int $userId
     * @return Collection
     */
    public function getAllExceptId(int $userId): Collection
    {
        return User::select('id','email','name')->where('id', '!=', $userId)->get();
    }

    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        return User::all();
    }

}
