<?php

namespace App\Http\Controllers;

use Domain\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @param UserRepository $userRepository
     * @return Response
     */
    public function get(Request $request, UserRepository $userRepository): Response
    {
        $userId = $request->get("uid", 0);
        $user = $userRepository->getById($userId);

        return response($user);
    }

    /**
     * @param UserRepository $userRepository
     * @return Response
     */
    public function list(UserRepository $userRepository): Response
    {
        $currentUser = request()->user();
        $users = $userRepository->getAllExceptId($currentUser->id)->toArray();
        $users = array_map(function ($user) {
            return array_intersect_key($user, array_flip(['id','email','name']));
        }, $users);

        return response($users);
    }

}
