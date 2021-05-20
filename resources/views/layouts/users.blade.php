@stack('styles')
<link href="{{ asset('css/users.css') }}" rel="stylesheet">

<script src="https://cdnjs.cloudflare.com/ajax/libs/radialIndicator/1.4.0/radialIndicator.js"></script>

<div class="row">
    <div id="bannerdiv" class="col-12 p-0 mb-5 position-relative">
        @if ($user->banner == null)
            @if (session('incorrecte'))
                <div class="alert alert-danger col-4 mx-auto mt-2">{{ session()->get('incorrecte') }}!</div>
            @endif
            @if(Auth::user() != null)
                @if($user->id == Auth::user()->id)
                    <button class="btn btn-light position-absolute fixed-top" style="border-radius: 0; z-index:0;"
                        onclick="canviarBanner('{{ csrf_token() }}')"><i class="bi bi-pencil-fill"
                        style="font-size: 1.5rem;"></i>
                    </button>
                @endif
            @endif
        @else
            @if (session('correcte'))
                <img class="centered-and-cropped w-100" height="200" src="/{{ $user->banner }}">
                @if(Auth::user() != null)
                    @if($user->id == Auth::user()->id)
                        <button class="btn btn-light position-absolute fixed-top" style="border-radius: 0; z-index:0;"
                            onclick="canviarBanner('{{ csrf_token() }}')"><i class="bi bi-pencil-fill"
                            style="font-size: 1.5rem;"></i></button>
                    @endif
                @endif
            @else
                @if (session('incorrecte'))
                    <div class="alert alert-danger col-4 mx-auto mt-2">{{ session()->get('incorrecte') }}!</div>
                @endif
                <img class="centered-and-cropped w-100" height="200" src="/{{ $user->banner }}">
                @if(Auth::user() != null)
                    @if($user->id == Auth::user()->id)
                        <button class="btn btn-light position-absolute fixed-top" style="border-radius: 0; z-index:0;"
                            onclick="canviarBanner('{{ csrf_token() }}')"><i class="bi bi-pencil-fill"
                            style="font-size: 1.5rem;"></i></button>
                    @endif
                @endif
            @endif
        @endif
    </div>
</div>

        <div class="row">
            <div class="col">

                <a href="{{ route('user', $user->nick) }}">
                    <img class="pt-0 mr-2"
                        style="border-radius:50%;width:2.5vw;min-width:80px;min-height:80px;"
                        src="../{{ $user->image }}">
                </a>
                <strong><span class="h1 font-weight-bold">{{ $user->nick }}</span></strong>
                @if(Auth::user() != null)
                @if(Auth::user()->id == $user->id)
                <div class="float-right">
                    <a href="{{ route('pujarVideo') }}"><button class="btn btn-outline-dark">UPLOAD</button></a>

                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            CONFIG
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ route('config') }}">CONFIG PROFILE</a>
                            <a class="dropdown-item" href="{{ route('configPassword') }}">CHANGE PASSWORD</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@else

    <div class="row">
        <div class="col-12 w-100">
            <a href="{{ route('user', $user->nick) }}">
                <img class="mr-1" style="border-radius:50%;width:5.5vw;min-width:80px;min-height:80px;"
                    src="../../{{ $user->image }}">
            </a>
            <strong><span class="h1 pl-3">{{ $user->nick }}</span></strong>
        </div>
    </div>
@endif

<div class="row">
    <div class="col">
        <nav class="navbar navbar-expand-lg navbar-light bg-white p-0">
            <ul class="navbar-nav mr-auto">

                @if($user->videos->count() >= 1)
                    <li class="nav-item active">
                        <a class="nav-link" href="{{ route('user', [$user->nick]) }}">PROFILE</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{ route('uservid', [$user->nick]) }}">VIDEOS</a>
                    </li>
                @endif
                <li class="nav-item active">
                    <a class="nav-link" href="{{ route('userinfo', [$user->nick]) }}">MORE INFO</a>
                </li>
                @if($user->videos->count() >= 1)
                    @if(Auth::user() != null)
                        @if($user->id == Auth::user()->id)
                            <li class="nav-item active">
                                <a class="nav-link" href="{{ route('uservidmanager', [$user->nick]) }}">MANAGE VIDEOS</a>
                            </li>
                            <li class="nav-item active">
                                <a class="nav-link" href="{{ route('userecommendations', [$user->nick]) }}">RECOMMENDATIONS</a>
                            </li>
                        @endif
                    @endif
                @endif
            </ul>
            @if($user->videos->count() >= 1)
                <ul class="navbar-nav">
                    <form class="d-flex justify-content-end" method="GET" action="{{ route('usersearch', $user->nick) }}">
                        <input class="form-control mt-3" type="search" placeholder="Search" aria-label="Search" id="search"
                            name="search">
                        <button class="btn btn-outline-success ml-2 mt-3" type="submit">Search</button>
                    </form>
                </ul>
            @endif
        </nav>
    </div>
</div>
<main>
    @yield('user-content')
</main>

<script type="text/javascript" src="{{ asset('js/users.js') }}"></script>