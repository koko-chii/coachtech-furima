<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggleLike($item_id)
    {
        $user = auth()->user();

        $user->likedItems()->toggle($item_id);

        return back();
    }
}
