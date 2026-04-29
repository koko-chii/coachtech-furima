@extends('layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}"> {{-- タブやグリッドのスタイルを再利用 --}}
    <link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endpush

@section('content')
<div class="main-container">
    {{-- ユーザー情報エリア --}}
    <div class="profile-header">
        <div class="profile-info">
            <div class="profile-image">
                @if($user->img_url)
                    <img src="{{ asset('storage/' . $user->img_url) }}" alt="プロフィール画像">
                @endif
            </div>
            <h1 class="user-name">{{ $user->name }}</h1>
        </div>
        <a href="{{ route('profile.edit') }}" class="btn-profile-edit">プロフィールを編集</a>
    </div>

    {{-- タブ切り替えエリア --}}
    <div class="index-tabs">
        <a href="/mypage?page=sell" class="tab-item {{ request('page') != 'buy' ? 'active' : '' }}">出品した商品</a>
        <a href="/mypage?page=buy" class="tab-item {{ request('page') == 'buy' ? 'active' : '' }}">購入した商品</a>
    </div>

    {{-- 商品一覧（グリッド） --}}
    <div class="product-grid">
        @foreach($items as $item)
            <a href="/item/{{ $item->id }}" class="product-card">
                <div class="product-image-wrapper">
                    <img src="{{ asset('storage/' . $item->img_url) }}" alt="{{ $item->name }}">
                    {{-- 売り切れの場合のバッジ（購入商品一覧なら全て付くはず） --}}
                    @if($item->is_sold)
                        <span class="sold-badge">Sold</span>
                    @endif
                </div>
                <p class="product-name">{{ $item->name }}</p>
            </a>
        @endforeach
    </div>
</div>
@endsection
