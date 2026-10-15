<?php

namespace App\Modules\Users\Requests;

use App\Helpers\PhoneValidation;
use Illuminate\Foundation\Http\FormRequest;

class UserRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        if (!$user) {
            return false;
        }

        return in_array($user->user_type, [1, 2, 3], true);
    }

    /**
     * Clean up the phone number after validation succeeds.
     */
    protected function passedValidation()
    {
        $this->merge([
            'mobile_phone_number' => PhoneValidation::sanitizePhoneNumber($this->mobile_phone_number)
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'middle_name' => 'nullable',
            'first_name' => 'required',
            'password' => 'required|min:8',
            'last_name' => 'required',

            // ✅ Enforces strict Zambian phone formats before sanitization
            'mobile_phone_number' => [
                'required',
                'unique:users,mobile_phone_number',
                'regex:/^(\+260|260|0)(97|96|95|77|76|75)\d{7}$/'
            ],

            'gender' => 'required|in:Male,Female',
            'date_of_birth' => 'required|before:today',
            'created_by' => 'required|exists:users,user_uuid',
            'username' => 'required|unique:users,username',
            'user_type' => 'required|integer'
        ];
    }

    /**
     * Custom message for the regex rule to help frontend users.
     */
    public function messages(): array
    {
        return [
            'mobile_phone_number.regex' => 'The mobile phone number must be a valid Zambian number starting with 09, 07, 2609, or 2607.',
        ];
    }
}
