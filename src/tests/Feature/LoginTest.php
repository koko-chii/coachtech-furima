<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * ログイン画面が表示されるかテスト
     */
    public function test_ログイン画面が表示される()
    {
        // 1. ログイン画面にアクセス
        $response = $this->get('/login');

        // 2. ステータスコードが200（成功）であることを確認
        $response->assertStatus(200);
    }

    public function test_メールアドレスが未入力の場合にエラーが表示される()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password',
        ]);

        // セッションに指示書通りのエラーが含まれているか確認
        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
    }

    /**
     * パスワードが入力されていない場合、バリデーションメッセージが表示される
     */
    public function test_パスワードが未入力の場合にエラーが表示される()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
    }
}

