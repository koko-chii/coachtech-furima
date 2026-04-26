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
        // ここに保存処理を書いていきます（バリデーションなど）
    }
}
