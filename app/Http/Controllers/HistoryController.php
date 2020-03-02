<?php

namespace App\Http\Controllers;

use Domain\Services\HistoryService;
use Domain\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HistoryController extends Controller
{
    /**
     * @param Request $request
     *
     * @param HistoryService $historyService
     * @return Response
     */
    public function get(Request $request, HistoryService $historyService): Response
    {
        /**
         * @var User $user
         */
        $user = request()->user();
        if (!$user) {
            return response("Error: User not found", Response::HTTP_NOT_FOUND);
        }

        return response($historyService->getHistoryForUser($user));
    }
}
