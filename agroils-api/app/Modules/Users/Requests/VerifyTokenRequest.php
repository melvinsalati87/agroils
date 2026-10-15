<?php

namespace App\Modules\Users\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyTokenRequest extends FormRequest
{
    public function authorize(){
        return true;
    }
    public function rules(){
        return [
            'token' => 'required',
        ];
    }
}
