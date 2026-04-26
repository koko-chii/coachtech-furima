@extends('layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endpush

@section('content')
<div class="auth-container">
    {{-- 適切な見出し構造 (h1) --}}
    <h1 class="auth-title">ログイン</h1>

    {{-- FN007: Fortifyを使用したログインアクション --}}
    {{-- 適切なインデントと命名規則 (auth-form, form-group) --}}
    <form action="{{ route('login') }}" method="POST" class="auth-form">
        @csrf

        {{-- メールアドレス入力エリア (FN008) --}}
        <div class="form-group">
            <label for="email" class="form-label">メールアドレス</label>
            <input type="email" name="email" id="email" class="form-input" value="{{ old('email') }}">
            {{-- FN010: バリデーションエラーメッセージ --}}
            @error('email')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        {{-- パスワード入力エリア (FN008) --}}
        <div class="form-group">
            <label for="password" class="form-label">パスワード</label>
            <input type="password" name="password" id="password" class="form-input">
            @error('password')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        {{-- ログイン実行 --}}
        <div class="form-button">
            <button type="submit" class="btn-submit">ログインする</button>
        </div>
    </form>

    {{-- FN011: 会員登録画面への遷移 --}}
    <div class="auth-footer">
        <a href="{{ route('register') }}" class="link-register">会員登録はこちら</a>
    </div>
</div>
@endsection
