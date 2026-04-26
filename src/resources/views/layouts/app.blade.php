<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtechフリマ</title>

    {{-- 全画面共通のCSS --}}
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">

    {{-- ★これを追記！各ページ独自のCSSをここに差し込みます★ --}}
    @stack('css')
</head>

<body>
    <header class="header">
        <div class="header__inner">
            {{-- ロゴ --}}
            <a href="/" class="header__logo-link">
                <img src="{{ asset('img/logo.png') }}" alt="COACHTECH" class="header__logo">
            </a>

            {{-- ログインしている時だけ表示するナビゲーション --}}
            @if(Auth::check() && Auth::user()->hasVerifiedEmail())
                <div class="header__search">
                    <input type="text" placeholder="なにをお探しですか？" class="header__search-input">
                </div>

                <nav class="header__nav">
                    <ul class="header__nav-list">
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
                    </ul>
                </nav>
            @endif
        </div>
    </header>

    <main class="main">
        @yield('content')
    </main>
</body>
</html>
