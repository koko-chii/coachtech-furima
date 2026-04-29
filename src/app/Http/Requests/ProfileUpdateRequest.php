<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true; // ログイン中なら誰でもOK
    }

    public function rules()
    {
        return [
            'name'     => 'required|string|max:255',
            'postcode' => 'required|string|regex:/^\d{3}-\d{4}$/', // 郵便番号形式（例: 123-4567）
            'address'  => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required'     => 'お名前を入力してください',
            'postcode.required' => '郵便番号を入力してください',
            'postcode.regex'    => '郵便番号は 000-0000 の形式で入力してください',
            'address.required'  => '住所を入力してください',
        ];
    }
}
