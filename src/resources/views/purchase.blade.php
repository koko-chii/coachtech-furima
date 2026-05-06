@extends('layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/purchase.blade.css') }}">
@endpush

@section('content')
<div class="purchase-container">
    <div class="purchase-content">
        <!-- 左側：詳細設定エリア -->
        <div class="purchase-main">
            <!-- 商品情報：商品名をh1に -->
            <div class="item-info">
                <div class="item-image">
                    <img src="{{ asset('storage/' . $item->img_url) }}" alt="{{ $item->name }}">
                </div>
                <div class="item-detail">
                    <h1>{{ $item->name }}</h1>
                    <p>¥ {{ number_format($item->price) }}</p>
                </div>
            </div>

            <hr>

            <!-- 支払い方法セクション：h2から開始 -->
            <div class="selection-section">
                <h2>支払い方法</h2>
                <div class="select-wrapper">
                    <select name="payment_method_select" id="payment-select" class="select-box">
                        <option value="" disabled {{ !session('payment_method') ? 'selected' : '' }}>選択してください</option>
                        <option value="コンビニ払い" {{ session('payment_method') == 'コンビニ払い' ? 'selected' : '' }}>コンビニ払い</option>
                        <option value="カード支払い" {{ session('payment_method') == 'カード支払い' ? 'selected' : '' }}>カード支払い</option>
                    </select>
                </div>
            </div>

            <hr>

            <!-- 配送先セクション -->
            <div class="selection-section">
                <div class="section-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <h2>配送先</h2>
                    <a href="{{ route('purchase.address.edit', ['item_id' => $item->id]) }}" class="change-link">変更する</a>
                </div>
                <div class="address-display">
                    <p>〒 {{ $user->postcode }}</p>
                    <p>{{ $user->address }}{{ $user->building }}</p>
                </div>
            </div>

            <hr>
        </div>

        <!-- 右側：決済確認エリア -->
        <div class="purchase-side">
            <div class="order-summary">
                {{-- ここは表形式なので、見出しは使わずセマンティックに構成 --}}
                <table class="summary-table">
                    <tr>
                        <th>商品代金</th>
                        <td>¥ {{ number_format($item->price) }}</td>
                    </tr>
                    <tr>
                        <th>支払い方法</th>
                        <td id="display-payment">{{ session('payment_method') ?: '未選択' }}</td>
                    </tr>
                </table>

                <form action="{{ route('purchase.store', ['item_id' => $item->id]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_method" id="hidden-payment">

                    <button type="submit" class="purchase-button" id="purchase-btn" disabled>
                        購入する
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- 外部JSファイルを読み込む --}}
<script src="{{ asset('js/purchase.js') }}"></script>
@endsection
