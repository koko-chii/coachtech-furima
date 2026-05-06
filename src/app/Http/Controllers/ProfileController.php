<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $page = $request->query('page', 'sell');

        if ($page === 'buy') {
            $items = $user->purchasedItems ?? [];
        } else {
        $items = $user->items ?? [];
        }

        return view('mypage', compact('user','items', 'page'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(ProfileUpdateRequest $request)
    {
        $user = Auth::user();

        if ($request->hasFile('image')) {
        $path = $request->file('image')->store('profiles', 'public');
        $user->img_url = $path;
    }

        $user->fill([
            'name'     => $request->name,
            'postcode' => $request->postcode,
            'address'  => $request->address,
            'building' => $request->building,
        ])->save();

        return redirect('/')->with('message', 'プロフィールを設定しました。');
    }
}
