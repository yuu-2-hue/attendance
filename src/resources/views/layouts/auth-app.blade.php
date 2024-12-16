<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtech 勤怠管理アプリ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/layouts/auth-common.css') }}" />
    @yield('css')
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header class="header__container">
        <div class="header__inner">
            <div class="header__item">
                <a class="header__logo" href="/">
                    <img src="{{ asset('img/logo.svg') }}" alt="logo画像">
                </a>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>
</html>