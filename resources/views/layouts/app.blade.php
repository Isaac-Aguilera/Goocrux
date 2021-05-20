<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Goocrux</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script type="text/javascript" src="{{ asset('js/navbar.js') }}" defer></script>



    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Balsamiq+Sans:wght@700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/navbar.css') }}" rel="stylesheet">

</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark" style="z-index: 1;">
            <div class="container-fluid">

                <a class="navbar-brand neon-button" href="{{ url('/') }}">
                    Goocrux
                </a>

                <a class="navbar-brand neon-button" href="{{ url('/') }}">
                    Shop
                </a>

                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mx-auto">
                        <form class="d-flex mt-3" method="GET" action="{{ route('search') }}">
                            <input class="form-control me-2 w-100" type="search" placeholder="Search"
                                aria-label="Search" id="search" name="search">
                            <button class="btn btn-outline-success ml-2" type="submit">Search</button>
                        </form>
                    </ul>

                    <!-- Right Side Of Navbar -->

                    <!-- Authentication Links -->
                    @guest
                        @if(Route::has('login'))
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link active"
                                        href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                        @endif

                        @if(Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link active"
                                    href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else

                    <ul class="navbar-nav">
                        <div class="dropdown">
                            <a href="#" onclick="netejarnoti('{{ csrf_token() }}')" role="button" data-toggle="dropdown"
                                id="dropdownMenu1" data-target="#" aria-expanded="true">
                                <i class="fa fa-bell" style="font-size: 20px;color: white;">
                                </i>
                                <div style="position: relative; left: -10px; top: -10px;" id="notinumber"
                                class="badge badge-danger">{{ Auth::user()->notificacions->where("state", "=", true)->count() }}</div>
                            </a>
                            
                            <ul class="dropdown-menu dropdown-menu-left" role="menu"
                                aria-labelledby="dropdownMenu1">
                                <li role="presentation" class="mx-auto">
                                    <span class="font-weight-bold ml-3">Notifications</span>
                                </li>
                                <ul class="list-group list-group-flush p-0" style="width: 220px">
                                    @if(Auth::user()->notificacions->where("state", "=", true)->count() < 1) <p
                                        class="ml-3">There are no notifications!</p>
                                    @else
                                        @foreach(Auth::user()->notificacions->where("state", "=", true) as $notifi)
                                            <li class="list-group-item p-0 ml-3 mt-3">
                                                <p>
                                                    {{ $notifi->noti_desc }}
                                                    <p class="text-muted">
                                                        {{ \FormatTime::LongTimeFilter($notifi->created_at) }}
                                                    </p>
                                                </p>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                                <li role="presentation">
                                    <a href="#" class="dropdown-menu-header"></a>
                                </li>
                            </ul>
                        </div>
                    </ul>
                        <ul class="navbar-nav">

                            <li class="nav-item my-auto mr-2">

                                <a class="nav-link active" href="{{ route('pujarVideo') }}"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                                        class="bi bi-camera-video-fill" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M0 5a2 2 0 0 1 2-2h7.5a2 2 0 0 1 1.983 1.738l3.11-1.382A1 1 0 0 1 16 4.269v7.462a1 1 0 0 1-1.406.913l-3.111-1.382A2 2 0 0 1 9.5 13H2a2 2 0 0 1-2-2V5z" />
                                    </svg></a>
                            </li>
                            <div class="dropdown">
                                <a id="navbarDropdown" class="nav-link text-light dropdown-toggle" href="#"
                                    role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                    v-pre>
                                    <img class="mr-2"
                                        style="border-radius:50%;width:2.5vw;min-width:40px;min-height:40px;"
                                        src="../../{{ Auth::User()->image }}">
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropright" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item"
                                        href="{{ route('user', Auth::user()->nick) }}">
                                        Profile
                                    </a>
                                    <a class="dropdown-item" href="{{ route('config') }}">
                                        Config
                                    </a>
                                    <a class="dropdown-item" href="{{ route('configPassword') }}">
                                        Password
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}"
                                        method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </div>

                        </ul>
                    @endguest
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <a id="back-to-top" href="#" class="btn btn-light btn-lg back-to-top" role="button"><i
            class="fa fa-chevron-up"></i></a>
</body>
</html>
