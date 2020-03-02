<?php

namespace Domain\Services;

use Domain\Models\User;
use Domain\Types\Amount;
use Illuminate\Support\Facades\DB;

class HistoryService
{
    /**
     * @param User $user
     * @return array
     */
    public function getHistoryForUser(User $user): array
    {
        $operationsCollection = DB::table('operations AS o')
            ->join('users AS u_src', 'o.source_user_id', '=', 'u_src.id')
            ->leftJoin('users AS u_trg', 'o.target_user_id', '=', 'u_trg.id')
            ->where('o.source_user_id', $user->id)
            ->orWhere('o.target_user_id', $user->id)
            ->select(
                'u_src.id AS source_id',
                'u_trg.id AS target_id',
                'u_src.name AS source_name',
                'u_trg.name AS target_name',
                'u_src.email AS source_email',
                'u_trg.email AS target_email',
                'o.amount AS amount',
                'o.source_new_balance',
                'o.target_new_balance',
                'o.created_at'
            )
            ->orderBy('o.created_at', 'ASC')
            ->orderBy('o.id', 'ASC')
            ->get();

        $amount = new Amount();
        $operationsArray = [];
        foreach ($operationsCollection as $operation) {
            $newOperation = [];

            $newOperation['amount'] = $amount->fromMajor($operation->amount)->getMinor();
            if ($operation->source_id == $user->id) {
                $newOperation['user_name'] = $operation->target_name ?? 'Subscription fee';
                $newOperation['user_email'] = $operation->target_email ?? 'subscription@example.com';
                $newOperation['amount'] = -$newOperation['amount'];
                $newOperation['new_balance'] = $amount->fromMajor($operation->source_new_balance)->getMinor();
            } else {
                $newOperation['user_name'] = $operation->source_name;
                $newOperation['user_email'] = $operation->source_email;
                $newOperation['new_balance'] = $amount->fromMajor($operation->target_new_balance)->getMinor();
            }
            $newOperation['datetime'] = $operation->created_at;

            $operationsArray[] = $newOperation;
        }

        return $operationsArray;
    }
}
