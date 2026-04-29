<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureProfileIsCompleted
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // 郵便番号（または住所）が空ならプロフィール編集画面へリダイレクト
        if ($user && empty($user->postcode)) {
            return redirect()->route('profile.edit');
        }

        return $next($request);
    }
}
