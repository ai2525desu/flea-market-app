<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtechフリマ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layouts/common.css') }}">
    @yield('css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,100..900&display=swap" rel="stylesheet">
</head>

<body>
    <div class="layout">
        <header class="header">
            <div class="header__inner">
                <div class="header__logo">
                    <a class="header__logo--item" href="{{ route('items.index') }}">
                        <img src="{{ asset('storage/logo.svg') }}" alt="COACHTECH_logo">
                    </a>
                </div>
                <div class="header__form">
                    <form class="search-form" action="{{ route('items.index') }}" method="get">
                        <input class="search-form__input" type="text" name="item_name" placeholder="なにをお探しですか？" value="{{ request('item_name') }}">
                    </form>
                </div>
                <div class="header__item">
                    @guest
                    <div class="header__item--nav">
                        <nav class="nav__wrap">
                            <ul class="nav__list">
                                <li class="nav__item">
                                    <a class="screen-transition" href="{{ route('auth.login') }}">ログイン</a>
                                </li>
                                <li class="nav__item">
                                    <a class="screen-transition" href="{{ route('profiles.show') }}">マイページ</a>
                                </li>
                                <li class="nav__item">
                                    <a class="screen-transition__sell" href="{{ route('items.exhibition') }}">出品</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    @endguest
                    @auth
                    <div class="header__item--nav">
                        <nav class="nav__wrap">
                            <ul class="nav__list">
                                <li class="nav__item">
                                    <form class="logout-form__button" action="/logout" method="post">
                                        @csrf
                                        <button class="logout-form__button--submit" type="submit">ログアウト</button>
                                    </form>
                                </li>
                                <li class="nav__item">
                                    <a class="screen-transition" href="{{ route('profiles.show') }}">マイページ</a>
                                </li>
                                <li class="nav__item">
                                    <a class="screen-transition__sell" href="{{ route('items.exhibition') }}">出品</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    @endauth
                </div>
            </div>
        </header>
        <main class="main">
            @yield('content')
        </main>
    </div>
</body>

</html>