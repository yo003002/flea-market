<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COΛCHTECH</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>
<body>
    <header class="header">
        <div class="header__inner">
            <div class="header__utilities">

                <a class="header__logo" href="/"><img src="{{ asset('images/COACHTECHヘッダーロゴ.png') }}" alt="COΛCHTECH"></a>

                <nav>
                    <ul class="header-nav">
                        <li class="header-nav__item-search">
                            <input class="header-nav__search-input" type="text" placeholder="何をお探しですか？">
                        </li>

                        @auth
                        <li class="header-nav__item">
                            <form  action="/logout" method="post">
                                @csrf
                                <button class="header-nav__button">ログアウト</button>
                            </form>
                        </li>
                        @endauth

                        @guest
                        <li class="header-nav__item">
                            <a href="/login" class="header-nav__link">ログイン</a>
                        </li>
                        @endguest
                        <li class="header-nav__item">
                            <a class="header-nav__link" href="/mypage">マイページ</a>
                        </li>
                        <li class="header-nav__item">
                            <a href="" class="header-nav__link-exhibit">出品</a>
                        </li>
                    </ul>
                </nav>
                
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    @stack('scripts')
    
</body>
</html>