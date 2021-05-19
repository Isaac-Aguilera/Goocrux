@extends('layouts.app')

@section('content')
<div id="container" class="container" style="margin-top: 100px;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if (isset($error))
                <div class="card">
                    <div class="card-header">Error!</div>
                    <div class="card-body">
                        <p>{{ $error }}</p>
                    </div>
                </div>
            @else
                <div id="card" class="card shadow">
                    <div class="card-header font-weight-bold">{{ __('Config') }}<button type="button"
                            class="btn btn-danger float-right" onclick="$('#modal').modal('toggle')" data-bs-toggle="modal"
                            data-bs-target="modal">Delete User</button></div>

                    <!-- Modal -->
                    <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalLabel">Confirm delete user</h5>
                                </div>
                                <div class="modal-body">
                                    Do you really want delete the user?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" onclick="$('#modal').modal('toggle')"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn btn-danger"
                                        onclick="eliminarUsuari({{ $user->id }}, '{{ csrf_token() }}')">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('config') }}" enctype="multipart/form-data">
                            @csrf
                            @if (session('message'))
                            <div class="alert alert-success">{{ session()->get('message') }}</div>
                            @endif
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" value="{{ $user->name }}" required autocomplete="name" autofocus>

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="surname"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Surname') }}</label>

                                <div class="col-md-6">
                                    <input id="surname" type="text"
                                        class="form-control @error('surname') is-invalid @enderror" name="surname"
                                        value="{{ $user->surname }}" required autocomplete="surname" autofocus>

                                    @error('surname')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="nick" class="col-md-4 col-form-label text-md-right">{{ __('Nick') }}</label>

                                <div class="col-md-6">
                                    <input id="nick" type="text" class="form-control @error('nick') is-invalid @enderror"
                                        name="nick" value="{{ $user->nick }}" required autocomplete="nick" autofocus>

                                    @error('nick')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" value="{{ $user->email }}" required autocomplete="email">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="file" class="col-md-4 col-form-label text-md-right">{{ __('Avatar') }}</label>
                                <div class="col-md-6">
                                    <img class="img-fluid mb-3" src="../{{ $user->image }}" alt="">

                                    <input class="@error('file') is-invalid @enderror" id="file" type="file" name="file">
                                    @error('file')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Confirm changes') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

<script type="text/javascript" src="{{ asset('js/config.js') }}"></script>