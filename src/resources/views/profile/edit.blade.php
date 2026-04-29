@extends('layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/edit.css') }}">
@endpush

@section('content')
<div class="profile-edit-container">
    <h1 class="page-title">プロフィール設定</h1>

    <form action="/mypage/profile" method="POST" enctype="multipart/form-data" class="profile-form" novalidate>
        @csrf

        {{-- プロフィール画像選択 --}}
        <div class="image-upload-section">
            <div class="image-preview">
                {{-- ここに現在の画像、なければグレーの円を表示 --}}
            </div>
            <label class="image-select-btn">
                画像を選択する
                <input type="file" name="image" style="display: none;">
            </label>
        </div>

        {{-- ユーザー名 --}}
        <div class="form-group">
            <label for="name">ユーザー名</label>
            <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name) }}">
            @error('name')
                <p style="color: red;">{{ $message }}</p>
            @enderror
        </div>

        {{-- 郵便番号 --}}
        <div class="form-group">
            <label for="postcode">郵便番号</label>
            <input type="text" id="postcode" name="postcode" value="{{ old('postcode') }}">
            @error('postcode')
                <p style="color: red;">{{ $message }}</p>
            @enderror
        </div>

        {{-- 住所 --}}
        <div class="form-group">
            <label for="address">住所</label>
            <input type="text" id="address" name="address" value="{{ old('address') }}">
            @error('address')
                <p style="color: red;">{{ $message }}</p>
            @enderror
        </div>

        {{-- 建物名 --}}
        <div class="form-group">
            <label for="building">建物名</label>
            <input type="text" id="building" name="building" value="{{ old('building') }}">
            @error('building')
                <p style="color: red;">{{ $message }}</p>
            @enderror
        </div>

        {{-- 更新ボタン --}}
        <button type="submit" class="submit-btn">更新する</button>
    </form>
</div>
@endsection
