<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'img_url' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'brand'       => 'nullable|string|max:255',
            'description' => 'required',
            'condition' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください',
            'price.required' => '価格を入力してください',
            'img_url.required' => '商品画像を選択してください',
            'img_url.image' => '画像ファイルを選択してください',
            'description.required' => '商品の説明を入力してください',
            'condition.required' => '商品の状態を選択してください',
        ];
    }
}
