@extends('layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/verify.css') }}">
@endpush

@section('content')
    <div class="verify-container">
        <div class="verify-message">
            <p>登録していただいたメールアドレスに認証メールを送付しました。</p>
            <p>メール認証を完了してください。</p>
        </div>

    {{-- FN012-3: 「認証はこちらから」ボタン --}}
    {{-- 本来はメール内のリンクで認証されますが、画面上のボタンとして配置する場合 --}}
    <div>
        <a href="/" class="btn-verify">認証はこちらから</a>
    </div>

    {{-- FN013: 認証メールを再送する --}}
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn-resend">認証メールを再送する</button>
    </form>

    {{-- 再送時の通知 --}}
    @if (session('status') == 'verification-link-sent')
        <p class="success-message">新しい認証メールを送信しました。</p>
    @endif
</main>
@endsection
