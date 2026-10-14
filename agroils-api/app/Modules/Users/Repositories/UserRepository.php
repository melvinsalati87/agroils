<?php

namespace App\Modules\Users\Repositories;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    protected User $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }
    public function authenticate(string $email, string $password) : JsonResponse{

        $user = $this->user
            ->where('email', $email)
            ->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json([
                'message' => 'Invalid email or password.',
            ], 401);
        }

        $token  = $user->createToken(env('APP_NAME'))->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
            'app_name' => env('APP_NAME'),
        ]);
    }
}
