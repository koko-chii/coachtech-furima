<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;

Route::get('/', [ItemController::class, 'index']);
Route::get('/item/{item_id}', [ItemController::class, 'show']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(['auth', 'verified', 'ensure.profile.completed'])->group(function () {
    Route::get('/sell', [ItemController::class, 'sell'])->name('sell');
    Route::post('/item/{item_id}/like', [ItemController::class, 'toggleLike']);
    Route::get('/mypage', [ProfileController::class, 'index']);
});

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/go-to-mailhog', function () {
    return redirect('http://localhost:8025');
})->middleware('auth')->name('verification.show');
