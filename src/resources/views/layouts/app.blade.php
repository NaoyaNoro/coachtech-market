<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COACHTECH</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header__top">
            <div class="header__ttl">
                <a href="/" class="header__logo">
                    <h1>
                        <img src="{{ asset('storage/img/logo.svg') }}" alt="COACHTECH Logo">
                    </h1>
                </a>
            </div>
            @section('navigation')
            <div class="search">
                <form action="/search" class="search__form" method="post">
                    @csrf
                    <input type="text" class="search__form-input" placeholder="なにをお探しですか？" name="name" value="{{ session('search_name') ?? '' }}">
                    <button class="search__button" type="submit">
                        検索
                    </button>
                    <button class="search__button search__clear" type="submit" name="name" value="">
                        クリア
                    </button>
                </form>
            </div>
        </div>

        <div class="header__bottom">
            <nav>
                @auth
                <div class="nav__button">
                    <form action="/logout" class="nav__logout" method="post">
                        @csrf
                        <button type="submit" class="logout__button">ログアウト</button>
                    </form>
                </div>
                @endauth
                @guest
                <div class="nav__button">
                    <a href="/login" class="nav__logout">
                        <button type="submit" class="logout__button">ログイン</button>
                    </a>
                </div>
                @endguest
                <div class="nav__button">
                    <a href="/mypage" class="mypage__button">
                        マイページ
                    </a>
                </div>
                <div class="nav__button">
                    <a href="/sell" class="nav__sell">
                        <button type="submit" class="sell__button">出品</button>
                    </a>
                </div>
            </nav>
            @show
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>

</html>