<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemSearchRequest extends FormRequest
{
    /**
     * 誰でもこのリクエストを送れるように true を返す
     */
    public function authorize()
    {
        return true;
    }

    /**
     * バリデーションルール
     */
    public function rules()
    {
        return [
            'tab' => 'nullable|string',
            'keyword' => 'nullable|string|max:255',
        ];
    }

    /**
     * Controllerをスッキリさせるための独自メソッド
     */
    public function getTab()
    {
        return $this->query('tab', 'recommend');
    }

    public function getKeyword()
    {
        return $this->query('keyword');
    }
}
