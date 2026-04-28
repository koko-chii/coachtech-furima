<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtechフリマ</title>

    {{-- 全画面共通のCSS --}}
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">

    {{-- 各ページ独自のCSSを読み込むためのスタック --}}
    @stack('css')
</head>

<body>
    <header class="header">
        <div class="header__inner">
            {{-- ロゴ --}}
            <a href="/" class="header__logo-link">
                <img src="{{ asset('img/logo.png') }}" alt="COACHTECH" class="header__logo">
            </a>

            {{-- 【FN016-1】検索欄：未認証ユーザーにも表示するため、Auth::check()の外に配置 --}}
            <div class="header__search">
                <form action="/" method="GET" class="header__search-form">
                    {{-- 検索時も現在のタブ（おすすめ/マイリスト）を維持するための隠しパラメータ --}}
                    @if(request('tab'))
                        <input type="hidden" name="tab" value="{{ request('tab') }}">
                    @endif

                    {{-- 【FN016-2,3】名前で検索。valueにrequestを入力することで検索状態を保持 --}}
                    <input type="text" name="keyword" value="{{ request('keyword') }}" 
                        placeholder="なにをお探しですか？" class="header__search-input">
                </form>
            </div>

            {{-- ナビゲーション：ログイン状態によって表示を切り替え --}}
            <nav class="header__nav">
                <ul class="header__nav-list">
                    @if(Auth::check())
                        {{-- ログイン中 --}}
                        <li class="header__nav-item">
                            <form action="/logout" method="POST" class="header__logout-form">
                                @csrf
                                <button type="submit" class="header__nav-link header__logout-btn">ログアウト</button>
                            </form>
                        </li>
                        <li class="header__nav-item">
                            <a href="/mypage" class="header__nav-link">マイページ</a>
                        </li>
                        <li class="header__nav-item">
                            <a href="/sell" class="header__sell-btn">出品</a>
                        </li>
                    @else
                        {{-- 【FN014-5】未ログイン（未認証ユーザー）用の表示 --}}
                        <li class="header__nav-item">
                            <a href="/login" class="header__nav-link">ログイン</a>
                        </li>
                        <li class="header__nav-item">
                            <a href="/register" class="header__nav-link">会員登録</a>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    </header>

    <main class="main">
        @yield('content')
    </main>
</body>
</html>
