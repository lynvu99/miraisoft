<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAccountRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Adjust based on your authorization logic
    }

    public function rules()
    {
        return [
            'login' => 'required|string|max:20|unique:accounts,login',
            'password' => 'required|string|max:40',
            'phone' => 'required|string|max:20',
        ];
    }
}