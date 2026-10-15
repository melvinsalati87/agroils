<?php

namespace App\Modules\Users\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Users\Requests\LoginRequest;
use App\Modules\Users\Requests\UserRegistrationRequest;
use App\Modules\Users\Requests\VerifyTokenRequest;
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

    public function create(UserRegistrationRequest $request,$created_uuid){
        try {
            $user = $this->userService->createUser($request->validated());

            return response()->json([
                'message' => 'User created successfully. Otp sent to your whatsapp number.',
                'user' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating the account.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function verifyToken(VerifyTokenRequest $verifyTokenRequest){
        $verifyTokenRequest->validated();
        $this->userService->verify($verifyTokenRequest->token);
    }
}
