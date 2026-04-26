<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        if (auth()->check() && empty(auth()->user()->postcode)) {
        return redirect('/mypage/profile');
        }

        $items = Item::all();
        return view('index', compact('items'));
    }
}
