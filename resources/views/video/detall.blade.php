@extends('layouts.app')
@stack('styles')
<link href="{{ asset('css/detallVideo.css') }}" rel="stylesheet">

@section('content')
<script src="{{ asset('js/detall.js') }}"></script>
<div id="container" class="container" style="margin-top: 100px;">
    <div class="row">
        @if(isset($error))
            <div class="col-lg-12 col-xs-12 mb-3">
                <div class="card">
                    <div class="card-header">Error!</div>
                    <div class="card-body">
                        <p>{{ $error }}</p>
                    </div>
                </div>
            </div>
        @else
            <div class="col-lg-8 col-xs-12 mb-3">
                <div class="card shadow">
                    <div class="poster">
                        <video class="video" poster="../{{ $video->image }}" controls>
                            <source src="../{{ $video->video_path }}">
                        </video>
                    </div>


                    <div class="card-body">

                        <h4 class="card-title font-weight-bolder">{{ $video->title }}</h4>
                        <div class="row">
                            <div class="col-10">
                                <p class="card-text text-muted">{{ $video->views }} views · Release date:
                                    {{ Str::limit($video->created_at, 10, '') }}</p>
                                <a href="{{ route('user', $video->user->nick) }}">
                                    <img class="mr-1"
                                        style="border-radius:50%;width:2.5vw;min-width:40px;min-height:40px;"
                                        src="../{{ $video->user->image }}">
                                </a>
                                <span class="font-weight-bold">{{ $video->user->nick }}</span>
                                <p class="mt-2">{{ $video->description }}</p>
                            </div>
                            <div class="col-2">
                                @if(Auth::user())
                                    @php
                                        $vot = $video->vots->where('user_id', '=', Auth::user()->id)->first()
                                    @endphp
                                    @if(isset($vot))
                                        @if($vot->votacio == 1)
                                            <button class="btn p-0"><i id="like_{{ $video->id }}"
                                                    class="bi bi-hand-thumbs-up-fill"
                                                    onclick="like({{ $video->id }}, 'like', '{{ csrf_token() }}')"></i></button>
                                            <span
                                                id="like_{{ $video->id }}_count">{{ $video->vots->where('votacio', '=', true)->count() }}</span>
                                            <button class="btn p-0"><i id="dislike_{{ $video->id }}"
                                                    class="bi bi-hand-thumbs-down"
                                                    onclick="like({{ $video->id }}, 'dislike', '{{ csrf_token() }}')"></i></button>
                                            <span
                                                id="dislike_{{ $video->id }}_count">{{ $video->vots->where('votacio', '=', false)->count() }}</span>
                                        @else
                                            <button class="btn p-0"><i id="like_{{ $video->id }}"
                                                    class="bi bi-hand-thumbs-up"
                                                    onclick="like({{ $video->id }}, 'like', '{{ csrf_token() }}')"></i></button>
                                            <span
                                                id="like_{{ $video->id }}_count">{{ $video->vots->where('votacio', '=', true)->count() }}</span>
                                            <button class="btn p-0"><i id="dislike_{{ $video->id }}"
                                                    class="bi bi-hand-thumbs-down-fill"
                                                    onclick="like({{ $video->id }}, 'dislike', '{{ csrf_token() }}')"></i></button>
                                            <span
                                                id="dislike_{{ $video->id }}_count">{{ $video->vots->where('votacio', '=', false)->count() }}</span>
                                        @endif
                                    @else
                                        <button class="btn p-0"><i id="like_{{ $video->id }}"
                                                class="bi bi-hand-thumbs-up"
                                                onclick="like({{ $video->id }}, 'like', '{{ csrf_token() }}')"></i></button>
                                        <span
                                            id="like_{{ $video->id }}_count">{{ $video->vots->where('votacio', '=', true)->count() }}</span>
                                        <button class="btn p-0"><i id="dislike_{{ $video->id }}"
                                                class="bi bi-hand-thumbs-down"
                                                onclick="like({{ $video->id }}, 'dislike', '{{ csrf_token() }}')"></i></button>
                                        <span
                                            id="dislike_{{ $video->id }}_count">{{ $video->vots->where('votacio', '=', false)->count() }}</span>
                                    @endif
                                @else
                                    <button class="btn p-0"><i id="like_{{ $video->id }}" class="bi bi-hand-thumbs-up"
                                            onclick="like({{ $video->id }}, 'like', '{{ csrf_token() }}')"></i></button>
                                    <span
                                        id="like_{{ $video->id }}_count">{{ $video->vots->where('votacio', '=', true)->count() }}</span>
                                    <button class="btn p-0"><i id="dislike_{{ $video->id }}"
                                            class="bi bi-hand-thumbs-down"
                                            onclick="like({{ $video->id }}, 'dislike', '{{ csrf_token() }}')"></i></button>
                                    <span
                                        id="dislike_{{ $video->id }}_count">{{ $video->vots->where('votacio', '=', false)->count() }}</span>
                                @endif

                                @php
                                    $mitjanes = array();
                                    foreach($valoracions as $nom => $name) {
                                    $contador = 0;
                                    $suma = 0;
                                    foreach($name as $id => $valoracio) {
                                    $contador = $contador + 1;
                                    $suma = $suma + $valoracio['valoracio'];
                                    }
                                    $mitjanes[$nom] = round($suma / $contador, 2);
                                    }
                                @endphp
                            </div>
                        </div>

                        <h4 class="font-weight-bold" id="contador">{{ $video->comentaris->count() }} comments</h4>
                        <textarea placeholder="Write a comment!" name="contingut" id="contingut" class="form-control"
                            rows="5"></textarea>
                        <button onclick="afegirComentari({{ $video->id }}, '{{ csrf_token() }}')"
                            class="btn btn-large btn-block btn-primary mt-3" type="submit">Enviar</button>
                        <div id="comentaris" class="mt-3">
                            @if($video->comentaris->count() == 0)
                                <h4 class="font-weight-bold">There are no comments!</h4>
                            @else
                                @foreach($video->comentaris as $comentari)
                                    <div class="mt-3" id={{ $comentari->id }}>
                                        <a
                                            href="{{ route('user', $comentari->user->nick) }}">
                                            <img class="mr-1"
                                                style="border-radius:50%;width:2.5vw;min-width:40px;min-height:40px;"
                                                src="/{{ $comentari->user->image }}">
                                        </a>
                                        <span class="font-weight-bold">{{ $comentari->user->nick }}</span>

                                        @if(Auth::user() !== null)
                                            @if($comentari->user_id === Auth::user()->id || $comentari->video->user->id === Auth::user()->id)
                                                <div class="dropdown float-right">
                                                    <button class="btn dropdown-toggle" type="button"
                                                        id="dropdownMenuButton" data-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <button
                                                            onclick="editarComentari({{ $comentari->id }}, '{{ $comentari->contingut }}', '{{ csrf_token() }}')"
                                                            class="dropdown-item">Edit</button>
                                                        <button
                                                            onclick="eliminarComentari({{ $comentari->id }}, '{{ csrf_token() }}')"
                                                            class="dropdown-item">Delete</button>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif

                                        <div id='{{ $comentari->id }}_contingut'>
                                            <p class="ml-5">{{ $comentari->contingut }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-xs-12">
                <div class="card shadow">
                    <div class="card-body">
                        <h4 class="card-title font-weight-bolder text-center">Video quality</h4>
                        <p class="text-muted" id=video>The average rating is:
                            <strong>{{ isset($mitjanes['video']) ? $mitjanes['video'] : '' }}</strong><span
                                style="color: orange;" class="ml-1 fa fa-star pl-0 d-inline"></span></p>
                        @for($i = 1; $i < 6; $i++)
                            <button class="fa fa-star pl-0" onmouseover="cmbst('video',{{ $i }})"
                            onmouseout="cmbst2('video',{{ $i }})" id={{ 'video'.$i }} value={{ $i }}
                            style="background-color: white; border:0;"
                            onclick="valorar('video',{{ $i }},{{ $video->id }}, '{{ csrf_token() }}')">
                            </button>
                        @endfor
                        @php
                            if(Auth::user() !== null) {
                            $valoracio = $video->valoracions->where('user_id', '=', Auth::user()->id)->where('name',
                            '=',
                            'video')->first();
                            if(isset($valoracio)) {
                            echo '<script type="text/javascript">
                                perma("video", '.$valoracio->valoracio.');

                            </script>';
                            }
                            }
                        @endphp
                    </div>
                </div>

                <div class="card shadow mt-2">
                    <div class="card-body">
                        <h4 class="card-title font-weight-bolder text-center">Audio quality</h4>
                        <p class="text-muted" id=audio>The average rating is:
                            <strong>{{ isset($mitjanes['audio']) ? $mitjanes['audio'] : '' }}</strong><span
                                style="color: orange;" class="ml-1 fa fa-star pl-0 d-inline"></span></p>
                        @for($i = 1; $i < 6; $i++)
                            <button class="fa fa-star pl-0" onmouseover="cmbst('audio',{{ $i }})"
                            onmouseout="cmbst2('audio',{{ $i }})" id={{ 'audio'.$i }} value={{ $i }}
                            style="background-color: white; border:0;"
                            onclick="valorar('audio',{{ $i }},{{ $video->id }}, '{{ csrf_token() }}')">
                            </button>
                        @endfor
                        @php
                            if(Auth::user() !== null) {
                            $valoracio = $video->valoracions->where('user_id', '=', Auth::user()->id)->where('name',
                            '=',
                            'audio')->first();
                            if(isset($valoracio)) {
                            echo '<script type="text/javascript">
                                perma("audio", '.$valoracio->valoracio.');

                            </script>';
                            }
                            }

                        @endphp
                    </div>
                </div>

                <div class="card shadow mt-2">
                    <div class="card-body">
                        <h4 class="card-title font-weight-bolder text-center">Content quality</h4>
                        <p class="text-muted" id=content>The average rating is:
                            <strong>{{ isset($mitjanes['content']) ? $mitjanes['content'] : '' }}</strong><span
                                style="color: orange;" class="ml-1 fa fa-star pl-0 d-inline"></span></p>
                        @for($i = 1; $i < 6; $i++)
                            <button class="fa fa-star pl-0" onmouseover="cmbst('content',{{ $i }})"
                            onmouseout="cmbst2('content',{{ $i }})" id={{ 'content'.$i }} value={{ $i }}
                            style="background-color: white; border:0;"
                            onclick="valorar('content',{{ $i }},{{ $video->id }}, '{{ csrf_token() }}')">
                            </button>
                        @endfor
                        @php
                            if(Auth::user() !== null) {
                            $valoracio = $video->valoracions->where('user_id', '=', Auth::user()->id)->where('name',
                            '=',
                            'content')->first();
                            if(isset($valoracio)) {
                            echo '<script type="text/javascript">
                                perma("content", '.$valoracio->valoracio.');

                            </script>';
                            }
                            }
                        @endphp
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
