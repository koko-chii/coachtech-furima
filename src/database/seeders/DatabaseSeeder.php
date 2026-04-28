<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'テスト太郎',
                'password' => Hash::make('password123'),
            ]
        );

        // 2. その後に商品データを入れる処理（ItemSeeder）を呼び出す
        $this->call([
            ItemSeeder::class,
        ]);
    }
}
