<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Order;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * テストユーザーを作成（住所登録・メール認証済み）
     */
    private function createVerifiedUser()
    {
        return User::factory()->create([
            'name' => 'テスト太郎',
            'email_verified_at' => now(),
            'postcode' => '123-4567',
            'address' => '東京都渋谷区',
        ]);
    }

    /** @test */
    public function test_プロフィールページにユーザー情報が表示される()
    {
        $user = $this->createVerifiedUser();

        $response = $this->actingAs($user)->get('/mypage');

        $response->assertStatus(200);
        $response->assertSee('テスト太郎');
        // 出品した商品タブがデフォルトで選択されているか
        $response->assertSee('出品した商品');
    }

    /** @test */
    public function test_自分が出品した商品が表示される()
    {
        $user = $this->createVerifiedUser();
        $item = Item::factory()->create([
            'name' => '私の出品物',
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($user)->get('/mypage?page=sell');

        $response->assertStatus(200);
        $response->assertSee('私の出品物');
    }

    /** @test */
    public function test_自分が購入した商品が表示される()
    {
        $user = $this->createVerifiedUser();
        $item = Item::factory()->create(['name' => '買った商品']);

        // Orderテーブルに購入履歴を作成
        Order::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'postcode' => $user->postcode,
            'address' => $user->address,
        ]);

        $response = $this->actingAs($user)->get('/mypage?page=buy');

        $response->assertStatus(200);
        $response->assertSee('買った商品');
    }

    /** @test */
    public function test_プロフィール編集画面で住所変更が反映される()
    {
        $user = $this->createVerifiedUser();

        // 住所を更新
        $response = $this->actingAs($user)->post('/mypage/profile', [
            'name' => '新しい名前',
            'postcode' => '999-8888',
            'address' => '北海道札幌市',
            'building' => 'サッポロビル'
        ]);

        $response->assertRedirect('/'); // コントローラーの仕様に合わせる

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => '新しい名前',
            'postcode' => '999-8888',
            'address' => '北海道札幌市'
        ]);
    }
}
