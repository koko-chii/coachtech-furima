<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggleLike($item_id)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // toggleを実行（あれば削除、なければ追加）
        $user->likedItems()->toggle($item_id);

        // 最新の「いいね状態」を確認
        $is_liked = $user->likedItems()->where('item_id', $item_id)->exists();

        // 最新の「いいね合計数」を取得
        $like_count = \App\Models\Item::findOrFail($item_id)->likedByUsers()->count();

        // JavaScriptに結果を送信
        return response()->json([
            'is_liked' => $is_liked,
            'like_count' => $like_count
        ]);
    }
}
