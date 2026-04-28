<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $tab = $request->query('tab');
        $keyword = $request->query('keyword');

        // 1. 認証とプロフィール設定のリダイレクト制御
        if ($user) {
            // 【要件】メール認証がまだの場合、誘導画面へ飛ばす
            if (!$user->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }

            // 【要件】プロフィール（郵便番号など）が未設定なら設定画面へ飛ばす
            if (empty($user->postcode)) {
                return redirect('/mypage/profile');
            }
        }

        // 2. タブによる取得データの切り替え（おすすめ vs マイリスト）
        if ($tab === 'mylist') {
            // 【FN015】マイリスト：自分がいいねした商品
            if (!$user) {
                // 未ログイン時は空にする
                $items = collect();
            } else {
                // Userモデルの likedItems リレーションを使用
                $items = $user->likedItems();
            }
        } else {
            // 【FN014】おすすめ：全商品
            $items = Item::query();

            // 【FN014-4】ログイン中なら自分が出品した商品は除外
            if ($user) {
                $items->where('user_id', '!=', $user->id);
            }
        }

        // 3. 【FN016】検索機能：商品名の部分一致検索（共通）
        if (!empty($keyword)) {
            $items->where('name', 'LIKE', '%' . $keyword . '%');
        }

        // 4. 最後にデータを取得
        // $itemsがクエリビルダ（Item::query()等）の場合はget()を呼び出す
        $finalItems = ($items instanceof \Illuminate\Support\Collection) ? $items : $items->get();

        return view('index', [
            'items' => $finalItems,
            'tab' => $tab,
            'keyword' => $keyword
        ]);
    }

    public function show($item_id)
    {
        // IDを元に商品を取得（なければ404エラー）
        $item = Item::findOrFail($item_id);

        // item_detail.blade.php を表示
        return view('item_detail', compact('item'));
    }

    /**
     * いいねの登録・解除を切り替える (FN015)
     */
    public function toggleLike($item_id)
    {
        $user = auth()->user();

        // 中間テーブルのレコードを入れ替える（あれば削除、なければ追加）
        $user->likedItems()->toggle($item_id);

        return back(); // 前の画面（詳細画面）に戻る
    }
}
