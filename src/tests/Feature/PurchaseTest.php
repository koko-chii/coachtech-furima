<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Order;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    private function createFullAccessUser()
    {
        return User::factory()->create([
            'email_verified_at' => now(),
            'postcode' => '123-4567',
            'address' => '東京都渋谷区',
        ]);
    }

    /** @test */
    public function test_商品購入画面が表示される()
    {
        $user = $this->createFullAccessUser();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->get("/purchase/{$item->id}");

        $response->assertStatus(200);
        $response->assertSee($item->name);
    }

    /** @test */
    public function test_購入ボタンを押すとStripe決済へリダイレクトされる()
    {
        $user = $this->createFullAccessUser();
        $item = Item::factory()->create(['price' => 1000]);

        $response = $this->actingAs($user)->post("/purchase/{$item->id}", [
            'payment_method' => 'card'
        ]);

        $response->assertStatus(302);
        // ドメイン部分だけでチェックするように修正
        $this->assertStringContainsString('stripe.com', $response->headers->get('Location'));
    }

    /** @test */
    public function test_決済成功後に商品が売り切れ状態になり注文情報が保存される()
    {
        $user = $this->createFullAccessUser();
        $item = Item::factory()->create(['is_sold' => false]);

        session(['shipping_address' => [
            'postcode' => '111-2222',
            'address' => '大阪府大阪市',
            'building' => 'テストビル'
        ]]);

        $response = $this->actingAs($user)->get("/purchase/success/{$item->id}");

        $response->assertRedirect('/');

        // 商品が売り切れ(1 または true)になっているか確認
        $this->assertEquals(1, $item->fresh()->is_sold);

        // ordersテーブルに記録されているか
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }
}
