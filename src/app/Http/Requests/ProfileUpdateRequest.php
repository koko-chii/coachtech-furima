<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'     => 'required|string|max:255',
            'postcode' => 'required|string',
            'address'  => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required'     => 'お名前を入力してください',
            'postcode.required' => '郵便番号を入力してください',
            'address.required'  => '住所を入力してください',
        ];
    }
}
