<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;

// 1. 誰でもアクセスできるルート
Route::get('/', [ItemController::class, 'index']); // トップ画面（FN014-5対応）
Route::get('/item/{item_id}', [ItemController::class, 'show']); // 商品詳細画面

// 2. ログイン（+メール認証）が必要なルート
Route::middleware(['auth', 'verified'])->group(function () {
    // プロフィール設定画面（ここには入れないと更新できない）
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// 2. プロフィール設定まで完了していないと使えない機能のグループ
// ※ 'ensure.profile.completed' は自分で作成したミドルウェア名と仮定しています
Route::middleware(['auth', 'verified', 'ensure.profile.completed'])->group(function () {
    Route::get('/sell', [ItemController::class, 'sell'])->name('sell');
    Route::post('/item/{item_id}/like', [ItemController::class, 'toggleLike']);
    Route::get('/mypage', [ProfileController::class, 'index']); // マイページTOPなど
});

// 3. メール認証・MailHog関連
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/go-to-mailhog', function () {
    return redirect('http://localhost:8025');
})->middleware('auth')->name('verification.show');
