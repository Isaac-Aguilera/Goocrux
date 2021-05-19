@extends('layouts.app')
@stack('styles')
<link href="{{ asset('css/home.css') }}" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.0.js"></script>

@section('content')

<main class="main" role="main">
    <div class="album py-5 bg-light">

        <div id="container" class="container" style="margin-top: 60px;">
            @if(session('message'))
                <div class="alert alert-success">{{ session()->get('message') }}</div>
            @endif
            <h4 class="font-weight-bold" style="margin-bottom: -20px;" id="categoria">All</h4>
            <div style="top: -20px;" class="dropdown text-right">
                <button class="btn dropdown-toggle font-weight-bold" type="button" id="dropdownMenuButton"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Categories
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <button onclick="cambiarCategoria(0, '{{ csrf_token() }}')" class="dropdown-item">All</button>
                    @foreach(App\Models\Categoria::all() as $categoria)
                        <button onclick="cambiarCategoria({{ $categoria->id }}, '{{ csrf_token() }}')"
                            class="dropdown-item">{{ $categoria->name }}</button>
                    @endforeach
                </div>
            </div>
            <div id="videos" class="row">
                @foreach(Video::orderBy('created_at','DESC')->get() as $video)
                    <div class="col-lg-4 col-md-6 col-sm-6">

                        <div class="card mb-4 shadow">
                            <a href="{{ route('video', $video->id) }}">
                                <video class="miniaturas w-100 p-0 m-0" src="/{{ $video->video_path }}"
                                    poster="/{{ $video->image }}" onmouseover="bigImg(this)"
                                    onmouseout="normalImg(this)" loop preload="none" muted="muted"></video>
                            </a>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-3">
                                        <a href="{{ route('user', $video->user->nick) }}">
                                            <img class="mr-1"
                                                style="border-radius:50%;width:2.5vw;min-width:40px;min-height:40px;"
                                                src="../{{ $video->user->image }}">
                                        </a>

                                    </div>
                                    <div class="col-9">
                                        <strong><span title="{{ $video->title }}">
                                                @if( Str::length($video->title) >= 60)
                                                    {{ Str::of($video->title)->limit(57, ' ...') }}
                                                @else
                                                    {{ $video->title }}
                                                @endif
                                            </span></strong><br>

                                        <span class="text-muted">{{ $video->user->nick }}</span><br>
                                        <span
                                            class="text-muted">{{ $video->views . ' views' }}</span><br>
                                        <span class="text-muted">
                                            {{ \FormatTime::LongTimeFilter($video->created_at) }}
                                        </span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</main>
@endsection

<script type="text/javascript" src="{{ asset('js/home.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/image.js') }}"></script>
