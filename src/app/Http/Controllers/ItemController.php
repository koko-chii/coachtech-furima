<?php

namespace App\Http\Controllers;

use App\Models\Item;
// 標準のRequestではなく、作成したカスタムRequestをインポートする
use App\Http\Requests\ItemSearchRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function create()
    {
        return view('item_create'); // 出品用のBladeファイル名
    }

    // 出品内容を保存（ここが画像保存のメイン！）
    public function store(Request $request)
    {
        // 1. バリデーション
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'description' => 'required',
            'img_url' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // 画像必須
            'condition' => 'required',
        ]);

        // 2. 画像の保存処理 (storage/app/public/items に保存)
        $path = "";
        if ($request->hasFile('img_url')) {
            // store('保存先フォルダ', 'ディスク名')
            // これにより storage/app/public/items/ファイル名.jpg という形式で保存されます
            $path = $request->file('img_url')->store('items', 'public');
        }

        // 3. データベースへ登録
        Item::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'img_url' => $path, // 'items/xxx.jpg' のようなパスが保存される
            'condition' => $request->condition,
            'brand' => $request->brand,
            'is_sold' => false,
        ]);

        return redirect('/')->with('message', '商品を出品しました');
    }

    /**
     * 引数を ItemSearchRequest に変更
     */
    public function index(ItemSearchRequest $request)
    {
        $user = auth()->user();

        if ($user) {
        // 1. メール認証がまだなら、誘導画面へ飛ばす
            if (!$user->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }

            // 2. プロフィール（郵便番号）が未設定なら設定画面へ飛ばす
            if (empty($user->postcode)) {
                return redirect('/mypage/profile');
            }
        }

        $tab = $request->getTab();
        $keyword = $request->getKeyword();

        // 1. 基本となるクエリを作成（Item::query() で開始すれば get() が必ず使える）
        $query = Item::query();

        if ($tab === 'mylist') {
            if ($user) {
                // ログイン中：自分がいいねした商品に絞り込む
                $query = $user->likedItems();
            } else {
                // 未ログイン：1件もヒットしないように ID 0 で検索（空にする確実な方法）
                $query->where('id', 0);
            }
        } else {
            // おすすめ：自分以外の出品を表示
            if ($user) {
                $query->where('user_id', '!=', $user->id);
            }
        }

        // 2. 検索キーワードがあれば絞り込み
        if ($keyword) {
            $query->where('name', 'LIKE', '%' . $keyword . '%');
        }

        // 3. 最後にデータを取得
        $items = $query->get();

        return view('index', [
            'items' => $items,
            'tab' => $tab,
            'keyword' => $keyword
        ]);
    }

    public function show($item_id)
    {
        $user = auth()->user();

        // ログインしている場合、メール認証とプロフィール設定をチェック
        if ($user) {
            // 1. メール認証がまだなら、誘導画面へ
            if (!$user->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }

            // 2. プロフィール未設定なら、プロフィール画面へ
            if (empty($user->postcode)) {
                return redirect('/mypage/profile');
            }
        }

        // 両方クリアしている、または未ログイン（ゲスト）なら詳細を表示
        $item = Item::findOrFail($item_id);
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
