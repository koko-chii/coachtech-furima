<?php

namespace App\Http\Controllers;

// 1. 作成したRequestをuseする
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $page = $request->query('page', 'sell'); // デフォルトは出品（sell）

    if ($page === 'buy') {
        // 購入した商品（Orderなどの中間テーブルから取得）
        $items = $user->purchasedItems ?? []; // リレーションの設定が必要
    } else {
        // 出品した商品
        $items = $user->items ?? [];
    }

        return view('mypage', compact('user','items', 'page'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * 引数を ProfileUpdateRequest に変更
     */
    public function update(ProfileUpdateRequest $request)
    {
        $user = Auth::user();

        // 2. バリデーション済みの値だけを取得して保存
        // $request->all() でも良いですが、安全のためにfillを使うのが一般的です
        $user->fill([
            'name'     => $request->name,
            'postcode' => $request->postcode,
            'address'  => $request->address,
            'building' => $request->building,
        ])->save();

        return redirect('/')->with('message', 'プロフィールを設定しました。');
    }
}
