<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtech 勤怠管理アプリ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/layouts/user-content-common.css') }}" />
    @yield('css')
</head>
<body>
    <header class="header__container">
        <div class="header__inner">
            <div class="header__item">
                <a class="header__logo" href="/attendance">
                    <img src="{{ asset('img/logo.svg') }}" alt="logo画像">
                </a>
                <ul class="header-nav">
                    <li class="header-nav__item">
                        <a class="header-nav__link" href="/attendance">勤怠</a>
                    </li>
                    <li class="header-nav__item">
                        <a class="header-nav__link" href="/attendance/list">勤怠一覧</a>
                    </li>
                    <li class="header-nav__item">
                        <a class="header-nav__link" href="/stamp_correction_request/list">申請</a>
                    </li>
                    <li class="header-nav__item">
                        @if (Auth::check())
                            <form action="/logout" method="post">
                            @csrf
                                <button class="header-nav__button">ログアウト</button>
                            </form>
                        @elseif(!Auth::check())
                            <a class="header-nav__link" href="/login">ログイン</a>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>
</html>