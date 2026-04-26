<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    // 保存を許可するカラムを指定
    protected $fillable = [
        'name',
        'price',
        'brand',
        'description',
        'img_url',
        'condition',
    ];
}
