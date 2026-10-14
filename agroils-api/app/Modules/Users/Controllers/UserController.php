<?php

namespace App\Modules\Users\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Users\Requests\LoginRequest;
use App\Modules\Users\Services\UserService;

class UserController extends Controller
{
    protected  UserService $userService;
    /**
     * Create a new class instance.
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function login(LoginRequest $loginRequest){
        try{
            $loginRequest->validated();
            return $this->userService->login($loginRequest->email, $loginRequest->password);
        }
        catch(\Exception $e){
            return  response()->json([
                'message' => $e->getMessage()
            ],500);
        }
    }
}
