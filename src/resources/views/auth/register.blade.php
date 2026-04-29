@extends('layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endpush

@section('content')
{{-- divに「auth-container」を追加 --}}
<div class="auth-container">
    {{-- h1に「auth-title」を追加 --}}
    <h1 class="auth-title">会員登録</h1>

    <form action="{{ route('register') }}" method="POST" class="auth-form" novalidate>
        @csrf

        {{-- 各入力エリアに「form-group」「form-label」「form-input」を追加 --}}
        <div class="form-group">
            <label for="name" class="form-label">ユーザー名</label>
            <input type="text" name="name" id="name" class="form-input" value="{{ old('name') }}">
            @error('name')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="email" class="form-label">メールアドレス</label>
            <input type="email" name="email" id="email" class="form-input" value="{{ old('email') }}">
            @error('email')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="password" class="form-label">パスワード</label>
            <input type="password" name="password" id="password" class="form-input">
            @error('password')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        {{-- 確認用パスワードも同様に --}}
        <div class="form-group">
            <label for="password_confirmation" class="form-label">確認用パスワード</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-input">
        </div>

        {{-- ボタンに「btn-submit」を追加 --}}
        <div class="form-button">
            <button type="submit" class="btn-submit">登録する</button>
        </div>
    </form>

    {{-- 下部リンクに「auth-footer」を追加 --}}
    <div class="auth-footer">
        <a href="{{ route('login') }}" class="link-login">ログインはこちら</a>
    </div>
</div>
@endsection
