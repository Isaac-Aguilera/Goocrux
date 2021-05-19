@extends('layouts.app')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>


@section('content')
@stack('styles')
<link href="{{ asset('css/progress.css') }}" rel="stylesheet">


<div class="container" style="margin-top: 100px;">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow">
                <div class="card-header font-weight-bold">{{ __('Upload video') }}</div>

                <div class="card-body">

                    @if(session('jobid'))

                        <div class="progress">
                            <div class="progress-bar" role="progressbar" aria-valuenow="{{ $jobid->progress_now }}"
                                aria-valuemin="0" aria-valuemax="100" style="width:60%;"></div>
                        </div>

                    @else
                        <form method="POST" class="myform" action="{{ route('pujarVideo') }}"
                            enctype="multipart/form-data">
                            @csrf

                            @if(session('message'))
                                <div class="alert alert-success">{{ session()->get('message') }}
                                </div>
                            @endif

                            <input id="user_id" type="number" name="user_id" value="{{ Auth::user()->id }}" hidden>

                            <div class="form-group row">
                                <label for="title"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Title') }}</label>

                                <div class="col-md-6">
                                    <input type="text" id="title"
                                        class="form-control @error('title') is-invalid @enderror" name="title"
                                        autocomplete="title" autofocus>

                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="description"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>

                                <div class="col-md-6">
                                    <textarea id="description"
                                        class="form-control @error('description') is-invalid @enderror"
                                        name="description" autocomplete="description" autofocus></textarea>

                                    @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="video_path"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Video') }}</label>
                                <div class="col-md-6">
                                    <input style="max-width: 120%;" id="video_path"
                                        class="custom-file @error('video_path') is-invalid @enderror" type="file"
                                        name="video_path">
                                    @error('video_path')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="image"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Thumbnail') }}</label>
                                <div class="col-md-6">
                                    <input style="max-width: 120%;" id="image"
                                        class="@error('image') is-invalid @enderror" type="file" name="image">
                                    @error('image')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="categoria"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Category') }}</label>
                                <div class="col-md-6">
                                    <select id="categoria_id" name="categoria_id"
                                        class="custom-select @error('categoria_id') is-invalid @enderror"
                                        aria-label="Default select example">
                                        <option value="No puede ser!" selected>Select a category!</option>
                                        @foreach($categories as $categoria)
                                            <option value="{{ $categoria->id }}">{{ $categoria->name }}</option>
                                        @endforeach
                                    </select>

                                    @error('categoria_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>"You have to select a category!</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Upload video') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
