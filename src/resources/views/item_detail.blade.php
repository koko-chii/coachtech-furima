@extends('layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/item_detail.css') }}">
@endpush

@section('content')
<div class="detail-container">
    <div class="detail-image">
        <img src="{{ asset('storage/' . $item->img_url) }}" alt="{{ $item->name }}">
    </div>

    <div class="detail-info">
        <h1 class="detail-name">{{ $item->name }}</h1>
        <p class="detail-brand">{{ $item->brand ?? 'ブランド名なし' }}</p>
        <p class="detail-price">
            ¥{{ number_format($item->price) }} <span>(税込)</span>
        </p>

        <div class="detail-actions">
            <div class="action-item">
                <form action="/item/{{ $item->id }}/like" method="POST">
                    @csrf
                    <button type="submit" class="like-button">
                        @if(Auth::check() && Auth::user()->likedItems->contains($item->id))
                            <img src="{{ asset('img/liked.png') }}" alt="いいね済み" class="heart-icon">
                        @else
                            <img src="{{ asset('img/HeartLogo.png') }}" alt="いいね" class="heart-icon">
                        @endif
                    </button>
                </form>
                <span class="like-count">{{ $item->likedByUsers()->count() }}</span>
            </div>

            <div class="action-item">
                <img src="{{ asset('img/CommentLogo.png') }}" alt="コメント" class="heart-icon">
                <span class="count">{{ $item->comments->count() }}</span>
            </div>
        </div>

        <a href="/purchase/{{ $item->id }}" class="btn-purchase">購入手続きへ</a>

        <div class="detail-description">
            <h2 class="section-title">商品説明</h2>
            <p>{{ $item->description }}</p>
        </div>

        <div class="detail-info-section">
            <h2 class="section-title">商品情報</h2>
            <table class="info-table">
                <tr>
                    <th>カテゴリー</th>
                    <td>
                        @if($item->categories && $item->categories->count() > 0)
                            @foreach($item->categories as $category)
                                <span class="category-tag">{{ $category->content }}</span>
                            @endforeach
                        @else
                            <span>カテゴリーなし</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>商品の状態</th>
                    <td>{{ $item->condition }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection
