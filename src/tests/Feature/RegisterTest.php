<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 名前が入力されていない場合、バリデーションメッセージが表示される
     */
    public function test_名前が未入力の場合にエラーが表示される()
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['name' => 'お名前を入力してください']);
    }

    /**
     * パスワードが確認用と一致しない場合、バリデーションメッセージが表示される
     */
    public function test_パスワードが一致しない場合にエラーが表示される()
    {
        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different_password',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードと一致しません']);
    }

    /**
     * 全ての項目が入力されている場合、会員情報が登録され、プロフィール設定画面に遷移される
     */
    public function test_会員登録が成功しプロフィール設定画面に遷移する()
    {
        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // 指示書の通り、プロフィール画面（またはメール認証誘導）へリダイレクトされるか
        $response->assertRedirect('/mypage/profile');

        // データベースに保存されたか
        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
        ]);
    }
}
