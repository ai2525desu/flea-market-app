<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtechフリマ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layouts/auth.css') }}">
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
                    <!-- app.blade.phpと同様、flexによる三分割レイアウトを維持するために空要素 -->
                </div>
                <div class="header__item">
                    <!-- app.blade.phpと同様、flexによる三分割レイアウトを維持するために空要素 -->
                </div>
            </div>
        </header>
        <main class="main">
            @yield('content')
        </main>
    </div>
</body>

</html>