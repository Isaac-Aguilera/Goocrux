@extends('layouts.app')
@stack('styles')

<link href="{{ asset('css/search.css') }}" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.0.js"></script>

@section('content')
<main class="main" role="main">
    <div class="container bg-white shadow rounded" style="margin-top: 100px;">
        @if(isset($error))
            <div class="card">
                <div class="card-header">Error!</div>
                <div class="card-body">
                    <p>{{ $error }}</p>
                </div>
            </div>
        @else

            @include('layouts.users')
            @section('user-content')
            @stop

            <div class="row bg-light">
                <div class="col-12">
                    <div class="row bg-light">
                        @if($posts->count() < 1)
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <br>
                                <div class="card mb-4">
                                    <h4 class="text-center m-5">Nothing could be found!</h4>
                                </div>
                            </div>
                        @else
                            @foreach($posts as $video)

                                <div class="col-lg-12 col-md-12 col-sm-12">

                                    <div class="card mb-4">

                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-6">
                                                    <a
                                                        href="{{ route('video', $video->id) }}">
                                                        <video class="miniaturas w-100 card-img-top"
                                                            src="../../{{ $video->video_path }}"
                                                            poster="../../{{ $video->image }}"
                                                            onmouseover="bigImg(this)" onmouseout="normalImg(this)"
                                                            loop preload="none" muted="muted"></video>
                                                    </a>



                                                </div>
                                                <div class="col-6">
                                                    <strong><span title="{{ $video->title }}">
                                                            @if ( Str::length($video->title) >= 60)
                                                                {{ Str::of($video->title)->limit(57, ' ...') }}

                                                            @else
                                                                {{ $video->title }}
                                                            @endif
                                                        </span></strong><br>

                                                    <span
                                                        class="text-muted">{{ $video->views . ' views' }}</span>
                                                    ·
                                                    <span class="text-muted">
                                                        {{ \FormatTime::LongTimeFilter($video->created_at) }}
                                                    </span><br><br>

                                                    <a
                                                        href="{{ route('user', $video->user->id) }}">
                                                        <img class="mr-1"
                                                            style="border-radius:50%;width:2.5vw;min-width:40px;min-height:40px;"
                                                            src="../../{{ $video->user->image }}">
                                                    </a>
                                                    <span
                                                        class="text-muted">{{ $video->user->nick }}</span><br><br>
                                                    <span class="text-muted" title="{{ $video->description }}">
                                                        @if ( Str::length($video->title) >= 140)
                                                            {{ Str::of($video->description)->limit(136, ' ...') }}

                                                        @else
                                                            {{ $video->description }}
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
<main>
@endsection

<script type="text/javascript" src="{{ asset('js/image.js') }}"></script>
