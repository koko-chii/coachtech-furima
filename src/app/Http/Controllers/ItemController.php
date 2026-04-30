<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Http\Requests\ItemSearchRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\CommentRequest;

class ItemController extends Controller
{
    public function sell()
    {
        return view('item_create');
    }

    public function store(StoreItemRequest $request)
    {
        $path = "";
        if ($request->hasFile('img_url')) {
            $path = $request->file('img_url')->store('items', 'public');
        }

        Item::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'img_url' => $path,
            'condition' => $request->condition,
            'brand' => $request->brand,
            'is_sold' => false,
        ]);

        return redirect('/')->with('message', '商品を出品しました');
    }

    public function storeComment(CommentRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $item->comments()->create([
            'user_id' => auth()->id(),
            'comment' => $request->comment,
        ]);

        return back()->with('message', 'コメントを投稿しました');
    }

    public function index(ItemSearchRequest $request)
    {
        $user = auth()->user();
        if ($user) {
            if (!$user->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }

            if (empty($user->postcode)) {
                return redirect('/mypage/profile');
            }
        }

        $tab = $request->getTab();
        $keyword = $request->getKeyword();
        $query = Item::query();

        if ($tab === 'mylist') {
            if ($user) {
                $query = $user->likedItems();
            } else {
                $query->where('id', 0);
            }
        } else {
            if ($user) {
                $query->where('user_id', '!=', $user->id);
            }
        }

        if ($keyword) {
            $query->where('name', 'LIKE', '%' . $keyword . '%');
        }

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

        if ($user) {
            if (!$user->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }

            if (empty($user->postcode)) {
                return redirect('/mypage/profile');
            }
        }

        $item = Item::findOrFail($item_id);

        return view('item_detail', compact('item'));
    }

    public function toggleLike($item_id)
    {
        $user = auth()->user();

        $user->likedItems()->toggle($item_id);

        return back();
    }
}
