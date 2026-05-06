<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Category;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        \Illuminate\Support\Facades\DB::table('category_item')->truncate();
        \App\Models\Item::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
        $user = User::where('email', 'test@example.com')->first();

        $categories = [
            'ファッション', '家電', 'インテリア', 'レディース', 'メンズ',
            'コスメ', '本', 'ゲーム', 'スポーツ', 'キッチン'
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['name' => $cat]);
        }

        $items = [
            [
                'name' => '腕時計',
                'price' => 15000,
                'brand' => 'Rolax',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'img_url' => 'items/腕時計.jpg',
                'condition' => '良好',
                'user_id' => $user->id,
            ],
            [
                'name' => 'HDD',
                'price' => 5000,
                'brand' => '西芝',
                'description' => '高速で信頼性の高いハードディスク',
                'img_url' => 'items/HDD.jpg',
                'condition' => '目立った傷や汚れなし',
                'user_id' => $user->id,
            ],
            [
                'name' => '玉ねぎ3束',
                'price' => 300,
                'brand' => 'なし',
                'description' => '新鮮な玉ねぎ3束のセット',
                'img_url' => 'items/玉ねぎ3束.jpg',
                'condition' => 'やや傷や汚れあり',
                'user_id' => $user->id,
            ],
            [
                'name' => '革靴',
                'price' => 4000,
                'brand' => '',
                'description' => 'クラシックなデザインの革靴',
                'img_url' => 'items/革靴.jpg',
                'condition' => '状態が悪い',
                'user_id' => $user->id,
            ],
            [
                'name' => 'ノートPC',
                'price' => 45000,
                'brand' => '',
                'description' => '高性能なノートパソコン',
                'img_url' => 'items/ノートPC.jpg',
                'condition' => '良好',
                'user_id' => $user->id,
            ],
            [
                'name' => 'マイク',
                'price' => 8000,
                'brand' => 'なし',
                'description' => '高音質のレコーディング用マイク',
                'img_url' => 'items/マイク.jpg',
                'condition' => '目立った傷や汚れなし',
                'user_id' => $user->id,
            ],
            [
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'brand' => '',
                'description' => 'おしゃれなショルダーバッグ',
                'img_url' => 'items/ショルダーバッグ.jpg',
                'condition' => 'やや傷や汚れあり',
                'user_id' => $user->id,
            ],
            [
                'name' => 'タンブラー',
                'price' => 500,
                'brand' => 'なし',
                'description' => '使いやすいタンブラー',
                'img_url' => 'items/タンブラー.jpg',
                'condition' => '状態が悪い',
                'user_id' => $user->id,
            ],
            [
                'name' => 'コーヒーミル',
                'price' => 4000,
                'brand' => 'Starbacks',
                'description' => '手動のコーヒーミル',
                'img_url' => 'items/コーヒーミル.jpg',
                'condition' => '良好',
                'user_id' => $user->id,
            ],
            [
                'name' => 'メイクセット',
                'price' => 2500,
                'brand' => '',
                'description' => '便利なメイクアップセット',
                'img_url' => 'items/メイクセット.jpg',
                'condition' => '目立った傷や汚れなし',
                'user_id' => $user->id,
            ],
        ];

        foreach ($items as $itemData) {
            $item = Item::create($itemData);

            $categoryName = match (true) {
                str_contains($item->name, '腕時計') => 'ファッション',
                str_contains($item->name, '革靴')   => 'ファッション',
                str_contains($item->name, 'PC')     => '家電',
                str_contains($item->name, 'HDD')    => '家電',
                str_contains($item->name, 'マイク')  => '家電',
                str_contains($item->name, 'メイク')  => 'コスメ',
                str_contains($item->name, '玉ねぎ')  => 'キッチン',
                str_contains($item->name, 'ミル')    => 'キッチン',
                default => 'インテリア',
            };

            $category = \App\Models\Category::where('name', $categoryName)->first();
            if ($category) {
                $item->categories()->attach($category->id);
            }
        }
    }
}
