<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 指示書に基づいた全商品データを作成する
     */
    private function seedItems()
    {
        $user = User::factory()->create();

        $items = [
            ['name' => '腕時計', 'price' => 15000, 'brand' => 'Rolax', 'description' => 'スタイリッシュなデザインのメンズ腕時計', 'img_url' => 'https://example.com', 'condition' => '良好'],
            ['name' => 'HDD', 'price' => 5000, 'brand' => '西芝', 'description' => '高速で信頼性の高いハードディスク', 'img_url' => 'https://example.com', 'condition' => '目立った傷や汚れなし'],
            ['name' => '玉ねぎ3束', 'price' => 300, 'brand' => 'なし', 'description' => '新鮮な玉ねぎ3束のセット', 'img_url' => 'https://example.com', 'condition' => 'やや傷や汚れあり'],
            ['name' => '革靴', 'price' => 4000, 'brand' => '', 'description' => 'クラシックなデザインの革靴', 'img_url' => 'https://example.com', 'condition' => '状態が悪い'],
            ['name' => 'ノートPC', 'price' => 45000, 'brand' => '', 'description' => '高性能なノートパソコン', 'img_url' => 'https://example.com', 'condition' => '良好'],
            ['name' => 'マイク', 'price' => 8000, 'brand' => 'なし', 'description' => '高音質のレコーディング用マイク', 'img_url' => 'https://example.com', 'condition' => '目立った傷や汚れなし'],
            ['name' => 'ショルダーバッグ', 'price' => 3500, 'brand' => '', 'description' => 'おしゃれなショルダーバッグ', 'img_url' => 'https://example.com', 'condition' => 'やや傷や汚れあり'],
            ['name' => 'タンブラー', 'price' => 500, 'brand' => 'なし', 'description' => '使いやすいタンブラー', 'img_url' => 'https://example.com', 'condition' => '状態が悪い'],
            ['name' => 'コーヒーミル', 'price' => 4000, 'brand' => 'Starbacks', 'description' => '手動のコーヒーミル', 'img_url' => 'https://example.com', 'condition' => '良好'],
            ['name' => 'メイクセット', 'price' => 2500, 'brand' => '', 'description' => '便利なメイクアップセット', 'img_url' => 'https://example.com', 'condition' => '目立った傷や汚れなし'],
        ];

        foreach ($items as $item) {
            Item::create(array_merge($item, ['user_id' => $user->id]));
        }

        return $user;
    }

    /** @test */
    public function test_商品一覧ページですべての商品が表示される()
    {
        $this->seedItems();
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('腕時計');
        $response->assertSee('メイクセット');
        $response->assertSee('コーヒーミル');
    }

    /** @test */
    public function test_商品詳細ページに指示通りの情報が表示される()
    {
        $user = $this->seedItems();

        $user->markEmailAsVerified();
        $user->postcode = '123-4567';
        $user->address = '東京都';
        $user->save();

        $item = Item::where('name', '腕時計')->first();

        $response = $this->actingAs($user)->get("/item/{$item->id}");

        $response->assertStatus(200);
        $response->assertSee('腕時計');
        $response->assertSee('Rolax');
        $response->assertSee('15,000'); // カンマ区切り
        $response->assertSee('スタイリッシュなデザインのメンズ腕時計');
        $response->assertSee('良好');
    }

    /** @test */
    public function test_検索キーワードに部分一致する商品が表示される()
    {
        $this->seedItems();

        // 「PC」で検索
        $response = $this->get('/?keyword=PC');

        $response->assertStatus(200);
        $response->assertSee('ノートPC');
        $response->assertDontSee('腕時計');
        $response->assertDontSee('玉ねぎ3束');
    }

    /** @test */
    public function test_購入済み商品にはSoldラベルが表示される()
    {
        $user = User::factory()->create();
        Item::create([
            'name' => '売り切れの商品',
            'price' => 1000,
            'description' => 'テスト',
            'condition' => '良好',
            'user_id' => $user->id,
            'is_sold' => true,
            'img_url' => 'https://example.com'
        ]);

        $response = $this->get('/');
        $response->assertSee('Sold');
    }
}
