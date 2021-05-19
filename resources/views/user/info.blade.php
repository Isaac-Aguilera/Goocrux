@extends('layouts.app')
@stack('styles')
<link href="{{ asset('css/home.css') }}" rel="stylesheet">
@section('content')
<main class="main" role="main">

    <div id="container" class="container bg-white shadow rounded" style="margin-top: 100px;">

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
        @endif
        <div class="row bg-light">
            <div id="colDesc" class="col-8">
                <p class="font-weight-bold">Description</p>
                <hr>
                @if(Auth::user() != null)
                    @if($user->id == Auth::user()->id)
                        <button id="desc" class="btn btn-light" style="border-radius: 0;"
                            onclick="editDesc('{{ csrf_token() }}')"><i class="bi bi-pencil-fill"
                            style="font-size: 1.5rem;"></i></button>
                    @endif
                @endif
                <span id="descSpan" class="ml-2">{{ $user->channel_desc }}</span>
            </div>
            <div class="col-4 mb-3">
                <p class="font-weight-bold">Stats</p>
                <hr>
                <span>Joined {{ Str::limit($user->created_at, 10, '') }}</span>
                <hr>
                <span>
                    {{ $user->videos->sum('views') }} views
                </span>
            </div>
        </div>
    </div>
</main>
@endsection

<script type="text/javascript" src="{{ asset('js/info.js') }}"></script>
