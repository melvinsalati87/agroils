<?php

namespace App\Modules\Users\Services;

use App\Modules\Users\Repositories\UserRepository;

class UserService
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(string $email, string $password){
        return $this->userRepository->authenticate($email, $password);
    }

    public function createUser(array $data){
        return $this->userRepository->create($data);
    }

    public function verify($token){
        return $this->userRepository->verifyOtpToken($token);
    }
}
