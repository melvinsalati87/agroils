<?php

namespace App\Services;

use App\Helpers\ErpIdentifierService;
use App\Jobs\WhatappJob as WhatappJob;
use App\Models\User;
use App\Repositories\UserOtpRepository;

class UserOtpService
{
    protected UserOtpRepository $otpRepository;

    /**
     * Inject required repository dependencies into the service framework.
     */
    public function __construct(UserOtpRepository $otpRepository)
    {
        $this->otpRepository = $otpRepository;
    }

    /**
     * Generate, store, and dispatch a new OTP verification payload to a user profile.
     */
    public function userOtp(User $user)
    {
        // 1. Generate a secure, cryptographically random numeric string block
        $otpCode = ErpIdentifierService::createOtp(6);

        // 2. Persist the generated metadata values inside the data engine layer
       return  $this->otpRepository->store($user->id, $otpCode, 10);
    }

    /**
     * Process verification payload inputs and mutate profile activation states.
     */
    public function verifyOtp(int $userId, string $otpCode): bool
    {
        // Delegate logical processing validation directly to the repository layer
        return $this->otpRepository->verifyAndConsume($userId, $otpCode);
    }
}
