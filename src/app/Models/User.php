<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    // アトリビュート（#[Fillable]）をやめて、こちらに統一
    protected $fillable = [
        'name',
        'email',
        'password',
        'postcode',
        'address',
        'building',
    ];

    // パスワードなどは隠す設定に統一
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function likedItems()
    {
        return $this->belongsToMany(Item::class, 'likes');
    }

    public function items()
    {
        // 出品した商品（一対多）
        return $this->hasMany(Item::class);
    }

    public function purchasedItems()
    {
        // 購入した商品（多対多など、構造に合わせて設定）
        // 例えば、中間テーブルを使っている場合：
        return $this->belongsToMany(Item::class, 'orders', 'user_id', 'item_id');
    }
}
