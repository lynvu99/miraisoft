<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Adjust based on your authorization logic
    }

    public function rules()
    {
        return [
            'login' => 'sometimes|string|max:20|unique:accounts,login,' . $this->account->id,
            'password' => 'sometimes|string|max:40',
            'phone' => 'sometimes|string|max:20',
        ];
    }
}