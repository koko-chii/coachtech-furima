<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    public function storeComment(CommentRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $item->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);

        return back()->with('message', 'コメントを投稿しました');
    }

}
