@extends('layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/item_detail.css') }}">
@endpush

@section('content')
<div class="detail-container">
    {{-- 左側：商品画像 --}}
    <div class="detail-image">
        <img src="{{ $item->img_url }}" alt="{{ $item->name }}">
    </div>

    {{-- 右側：商品情報 --}}
    <div class="detail-info">
        <h1 class="detail-name">{{ $item->name }}</h1>
        <p class="detail-brand">{{ $item->brand }}</p>
        <p class="detail-price">¥{{ number_format($item->price) }} (税込)</p>

        {{-- いいねボタン（FN015のトリガー） --}}
        <div class="detail-actions">
            <form action="/item/{{ $item->id }}/like" method="POST">
                @csrf
                <button type="submit" class="like-button">
                    {{-- いいね済みなら星を赤くする --}}
                    @if(Auth::check() && Auth::user()->likedItems->contains($item->id))
                        <span class="star-icon active">★</span>
                    @else
                        <span class="star-icon">☆</span>
                    @endif
                </button>
            </form>
        </div>

        <div class="detail-description">
            <h2>商品説明</h2>
            <p>{{ $item->description }}</p>
        </div>
    </div>
</div>
@endsection
