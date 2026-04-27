<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * プロフィール編集画面（PG10）を表示する
     */
    public function edit()
    {
        // ログイン中のユーザー情報を取得
        $user = Auth::user();

        // resources/views/profile/edit.blade.php を表示
        return view('profile.edit', compact('user'));
    }

    /**
     * プロフィール情報を更新する
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. プロフィール情報を保存
        $user->name = $request->name;
        $user->postcode = $request->postcode;
        $user->address = $request->address;
        $user->building = $request->building;
        $user->save();

        // 2. 保存が終わったら「トップページ」へ進む
        return redirect('/')->with('message', 'プロフィールを設定しました。');
    }
}
