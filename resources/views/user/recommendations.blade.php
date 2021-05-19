@extends('layouts.app')

@section('content')
<main class="main" role="main">
    <div class="container bg-white shadow rounded" style="margin-top: 100px;">
        @include('layouts.users')
        @section('user-content')
        @stop

        <div class="row bg-light mt-3">
            @php
                $mitjanesglob = array();
                $cont2 = 0;
            @endphp
            @foreach ($posts as $video)
                @php
                    $mitjanes = array();
                    $valoracions = $video->valoracions->groupBy('name');
                    foreach($valoracions as $nom => $name) {
                        $contador = 0;
                        $suma = 0;
                        foreach($name as $id => $valoracio) {
                            $contador = $contador + 1;
                            $suma = $suma + $valoracio['valoracio'];
                        }
                        $mitjanes[$nom] = round($suma / $contador,2);

                        if (isset($mitjanesglob[$nom])) {
                            $mitjanesglob[$nom] = $mitjanesglob[$nom] + round($suma / $contador,2);
                        } 
                        else {
                            $mitjanesglob[$nom] = round($suma / $contador,2);
                        }
                    }

                    if (isset($mitjanes['video']) and (isset($mitjanes['audio']) and isset($mitjanes['content']))){
                        $cont2 = $cont2 + 1;
                    }
                @endphp

            @endforeach


            @if(isset( $mitjanesglob['video']) and $mitjanesglob['video']/$cont2 <= 2.4) <div
                class="card shadow w-75 mx-auto mt-5">
                <div class="row">
                    <div class="col-5">
                        <img class="card-img-top w-100 h-100" style="object-fit: cover;" src="{{ asset('cams.jpeg') }}"
                            alt="Card image">
                    </div>
                    <div class="col-7">
                        <div class="card-body">
                            <h4 class="card-title font-weight-bold">Video quality <i
                                    class="bi bi-camera-reels-fill"></i></h4>
                            <p class="card-text text-muted">Your latest videos haven't got really good valorations on
                                video quality. <br><br>
                                We want the best for our customers so we offer you some of our video products to improve
                                your channel so you can have a better future on our platform.
                            </p>
                            <a href="#" class="btn btn-primary">Visit shop</a>
                        </div>
                    </div>
                </div>
        </div>
            @endif

        @if(isset( $mitjanesglob['audio']) and $mitjanesglob['audio']/$cont2 <= 2.4) <div
            class="card shadow w-75 mx-auto mt-3">
            <div class="row">
                <div class="col-5">
                    <img class="card-img-top w-100 h-100" style="object-fit: cover" src="{{ asset('mic.jpg') }}"
                        alt="Card image">
                </div>
                <div class="col-7">
                    <div class="card-body">
                        <h4 class="card-title font-weight-bold">Audio quality <i class="bi bi-mic-fill"></i></h4>
                        <p class="card-text text-muted">Your latest videos haven't got really good valorations on audio
                            quality. <br><br>
                            We want the best for our customers so we offer you some of our audio products to improve
                            your channel so you can have a better future on our platform.
                        </p>
                        <a href="#" class="btn btn-primary">Visit shop</a>
                    </div>
                </div>
            </div>
    </div>
        @endif

    @if(isset( $mitjanesglob['content']) and $mitjanesglob['content']/$cont2 <= 2.4) <div
        class="card shadow w-75 mx-auto mt-3 mb-5">
        <div class="row">
            <div class="col-5">
                <img class="card-img-top w-100 h-100" style="object-fit: cover;"
                    src="{{ asset('video-marketing.jpg') }}" alt="Card image">
            </div>
            <div class="col-7">
                <div class="card-body">
                    <h4 class="card-title font-weight-bold">Content quality <i class="bi bi-cloudy-fill"></i></h4>
                    <p class="card-text text-muted">Your latest videos haven't got really good valorations on content
                        quality. <br><br>
                        We know that is hard to offer a content that everyone likes.
                        But our users are not valorating it good, we are just warning you, but the decision to change is
                        yours. <br><br>
                        Try to think about new creative content but if you are happy with what you are doing no one can
                        stop you.<br><br>
                    </p>
                </div>
            </div>
        </div>
    </div>
        @endif

        @if(!isset( $mitjanesglob['content']) and !isset($mitjanesglob['audio']) and !isset($mitjanesglob['video']))
            <p>There are no recommendations</p>
        @endif

    </div>
    </div>
</main>
@endsection