<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class LikeCommentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * テストユーザーを作成し、全てのガードを突破させる
     */
    private function createFullAccessUser()
    {
        return User::factory()->create([
            'email_verified_at' => now(),
            'postcode' => '123-4567',
            'address' => '東京都渋谷区',
            'name' => 'テストユーザー'
        ]);
    }

    /** @test */
    public function test_いいねアイコンを押下して合計値が増加する()
    {
        $user = $this->createFullAccessUser();
        $item = Item::factory()->create();

        // ルート定義に合わせて /like/{id}/like にPOST
        $response = $this->actingAs($user)->post("/like/{$item->id}/like");

        // 成功のレスポンス（302または200）を確認
        $response->assertStatus($response->status() == 302 ? 302 : 200);
        
        // 【修正】テーブル名を likes に変更
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    /** @test */
    public function test_いいねを解除すると合計値が減少する()
    {
        $user = $this->createFullAccessUser();
        $item = Item::factory()->create();
        
        // 最初からいいねしておく（DBに直接挿入）
        $user->likedItems()->attach($item->id);
        $this->assertEquals(1, $item->likedByUsers()->count());

        // 再度POSTして解除
        $response = $this->actingAs($user)->post("/like/{$item->id}/like");

        $response->assertStatus($response->status() == 302 ? 302 : 200);
        $this->assertEquals(0, $item->likedByUsers()->count());
    }

    /** @test */
    public function test_ログイン済みのユーザーはコメントを送信できる()
    {
        $user = $this->createFullAccessUser();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->post("/comment/{$item->id}/comment", [
            'comment' => 'テストコメントです'
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('comments', [
            'comment' => 'テストコメントです',
            'user_id' => $user->id
        ]);
    }

    /** @test */
    public function test_未ログインユーザーはコメントを送信できない()
    {
        $item = Item::factory()->create();

        $response = $this->post("/comment/{$item->id}/comment", [
            'comment' => '未ログインコメント'
        ]);

        $response->assertRedirect('/login');
    }

    /** @test */
    public function test_255文字以上のコメントはバリデーションエラーになる()
    {
        $user = $this->createFullAccessUser();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)
            ->from("/item/{$item->id}")
            ->post("/comment/{$item->id}/comment", [
                'comment' => str_repeat('あ', 256)
            ]);

        $response->assertSessionHasErrors(['comment']);
    }
}
