<?php

namespace App\Http\Requests;

use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginRequest extends FortifyLoginRequest
{
    /**
     * 誰でもこのリクエストを送れるように true を設定
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * 指示書に基づくバリデーションルール
     */
    public function rules(): array
    {
        return [
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ];
    }

    /**
     * 指示書に基づくカスタムエラーメッセージ
     */
    public function messages(): array
    {
        return [
            'email.required'    => 'メールアドレスを入力してください',
            'email.email'       => 'メールアドレスはメール形式で入力してください',
            'password.required' => 'パスワードを入力してください',
        ];
    }

    /**
     * バリデーションの後に実行される追加チェック
     * パスワードが間違っている場合に特定のメッセージを出す
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // メールアドレスが入力されている場合のみDB照合を行う
            if ($this->filled('email')) {
                $user = User::where('email', $this->email)->first();

                // ユーザーは存在するが、パスワードが一致しない場合の個別処理
                if ($user && !Hash::check($this->password, $user->password)) {
                    // 指示書通りの文言をpassword項目に追加
                    $validator->errors()->add('password', 'パスワードが一致しません');
                }
            }
        });
    }
}
