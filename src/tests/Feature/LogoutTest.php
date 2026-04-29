<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ログアウトができる
     */
    public function test_ログアウトができる()
    {
        // 1. テスト用のユーザーを作成
        $user = User::factory()->create();

        // 2. そのユーザーでログイン状態で、ログアウト処理（POST）を実行
        $response = $this->actingAs($user)
                        ->post('/logout');

        // 3. ログアウトが実行され、未ログイン状態になっていることを確認
        $this->assertGuest();

        // 4. ログアウト後のリダイレクト先（ログイン画面など）を確認
        // 指示書の挙動に合わせて、リダイレクト先を /login などに調整してください
        $response->assertRedirect('/login');
    }
}
