@extends('layouts.app')

@section('content')
<div class="index-container">
    {{-- タブ部分 --}}
    <div class="index-tabs">
        <a href="/" class="tab-item {{ !request()->has('tab') ? 'active' : '' }}">おすすめ</a>
        <a href="/?tab=mylist" class="tab-item {{ request()->get('tab') == 'mylist' ? 'active' : '' }}">マイリスト</a>
    </div>

    {{-- 商品一覧表示（グリッド） --}}
    <div class="product-grid">
        @foreach($items as $item)
            <a href="/item/{{ $item->id }}" class="product-card">
                <div class="product-image-wrapper">
                    {{-- 実際には $item->img_url など --}}
                    <span>商品画像</span>
                </div>
                <p class="product-name">{{ $item->name }}</p>
            </a>
        @endforeach
    </div>
</div>
@endsection
