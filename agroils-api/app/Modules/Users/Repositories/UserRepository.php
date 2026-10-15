<?php

namespace App\Modules\Users\Repositories;

use App\Helpers\ErpIdentifierService;
use App\Helpers\PhoneValidation;
use App\Jobs\WhatappJob as WhatsappJob; // Corrected spelling match
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserRepository
{
    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Authenticate user and return the user instance.
     */
    public function authenticate(string $email, string $password): User
    {
        $user = $this->user->where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid email or password.'],
            ]);
        }

        return $user;
    }

    /**
     * Create a user record, register an active tracking OTP, and dispatch a WhatsApp notification.
     */
    public function create(array $data): User
    {
        // 1. Sanitize the phone metric to standard 260... format layout
        $data['mobile_phone_number'] = PhoneValidation::sanitizePhoneNumber($data['mobile_phone_number']);

        // 2. Wrap operations inside a database transaction to prevent partial data writes if messaging fails
        return DB::transaction(function () use ($data) {

            // 3. Persist the main user record entity inside your table
            $user = $this->user->create($data);

            // 4. Create a secure numeric 6-digit verification code string block
            $otpCode = ErpIdentifierService::createOtp(6);

            // 5. Log the newly issued OTP record inside your tracking system table
            DB::table('user_otps')->insert([
                'user_id'    => $user->id,
                'otp_code'   => $otpCode,
                'expires_at' => now()->addMinutes(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 6. Dispatch the queue worker, passing user object, template name, and unique variables array
            WhatsappJob::dispatch($user, 'agroils_erp_otp', [
                'otp_code' => $otpCode
            ]);

            return $user;
        });
    }

    public function verifyOtpToken(string $otpCode)
    {
        // 1. Fetch the full record along with its expiration status
        $otpRecord = DB::table('user_otps')
            ->where('otp_code', $otpCode)
            ->orderBy('id', 'desc') // Ensure we get the latest issued token
            ->first();

        // 2. Structural Check: Ensure the token actually exists
        if (!$otpRecord) {
            throw ValidationException::withMessages([
                'otp_code' => ['The verification code provided is invalid.'],
            ]);
        }

        // 4. Temporal Check: Enforce expiration window
        if (now()->greaterThan($otpRecord->expires_at)) {
            throw ValidationException::withMessages([
                'otp_code' => ['The verification code has expired. Please request a new one.'],
            ]);
        }

        // 5. Success: Consume the token using its explicit primary ID key
        DB::table('user_otps')
            ->where('id', $otpRecord->id)
            ->update([
                'verified_at' => now(),
                'updated_at' => now(),
            ]);

        // 6. Profile Sync: Mark the parent user profile as phone verified
        DB::table('users')
            ->where('id', $otpRecord->user_id)
            ->update([
                'phone_verified_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

        // 7. Communication: Fetch user details and dispatch the success confirmation message
        $user = \App\Models\User::find($otpRecord->user_id);
        if ($user) {
            WhatsappJob::dispatch($user, 'agroils_erp_verified');
        }

        return response()->json([
            'message' => 'Phone number successfully verified.',
        ], 200);

    }
}
