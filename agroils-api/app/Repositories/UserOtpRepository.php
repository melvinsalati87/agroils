<?php

namespace App\Repositories;

use App\Models\Tokens\UserOTP;
use Illuminate\Validation\ValidationException;

class UserOtpRepository
{
    protected UserOTP $model;

    /**
     * Inject the UserOTP Eloquent Model into the repository framework.
     */
    public function __construct(UserOTP $model)
    {
        $this->model = $model;
    }

    /**
     * Save a freshly issued OTP transaction code inside the data layer.
     */
    public function store(int $userId, string $otpCode, int $expiryMinutes = 10): UserOTP
    {
        return $this->model->create([
            'user_id'    => $userId,
            'otp_code'   => $otpCode,
            'expires_at' => now()->addMinutes($expiryMinutes),
        ]);
    }

    /**
     * Assert validation rules, check expiration parameters, and activate verification status.
     * Throws explicit framework ValidationExceptions on failure conditions.
     */
    public function verifyAndConsume(int $userId, string $otpCode): bool
    {
        // 1. Retrieve the absolute latest matching database log record
        $otpRecord = $this->model->where('user_id', $userId)
            ->where('otp_code', $otpCode)
            ->orderBy('id', 'desc')
            ->first();

        // 2. Structural assertion: Ensure token transaction entity exists
        if (!$otpRecord) {
            throw ValidationException::withMessages([
                'otp_code' => ['The provided verification code is incorrect.'],
            ]);
        }

        // 3. Status assertion: Ensure token sequence has not been used previously
        if ($otpRecord->verified_at !== null) {
            throw ValidationException::withMessages([
                'otp_code' => ['This verification code has already been used.'],
            ]);
        }

        // 4. Temporal assertion: Assert current server timestamp has not crossed expiration boundary
        if ($otpRecord->expires_at->isPast()) {
            throw ValidationException::withMessages([
                'otp_code' => ['The verification code has expired. Please request a new code.'],
            ]);
        }

        // 5. Update: Terminate active token lifecycle status by marking it consumed
        $otpRecord->update([
            'verified_at' => now(),
        ]);

        // 6. Update target profile: Mark the parent user account relation confirmed
        $otpRecord->user->update([
            'phone_verified_at' => now(),
        ]);

        return true;
    }
}
