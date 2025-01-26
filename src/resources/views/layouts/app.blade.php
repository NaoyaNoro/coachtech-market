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
        <div class="header__inner">
            <a href="/" class="header__logo">
                <h1>
                    <img src="{{ asset('storage/img/logo.svg') }}" alt="COACHTECH Logo">
                </h1>
            </a>
        </div>
        @section('navigation')
        <nav>
            <div class="nav">
                <form action="/search" class="nav__search" method="post">
                    @csrf
                    <input type="text" class="nav__search--input" placeholder="なにをお探しですか？" name="name" value="{{ session('search_name') ?? '' }}">
                    <button class="nav__search-button" type="submit">
                        検索
                    </button>
                </form>
            </div>
            <div class="nav-button">
                <form action="/logout" class="nav-logout" method="post">
                    @csrf
                    <button type="submit">ログアウト</button>
                </form>
            </div>
            <div class="nav-button">
                <form action="/mypage" class="nav-mypage" method="get">
                    <button type="submit">マイページ</button>
                </form>
            </div>
            <div class="nav-button">
                <form action="/sell" class="nav-sell" method="get">
                    <button type="submit">出品</button>
                </form>
            </div>
        </nav>
        @show
    </header>
    <main>
        @yield('content')

    </main>
</body>

</html>