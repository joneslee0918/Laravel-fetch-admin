@extends('layouts.app', ['activePage' => 'profile', 'titlePage' => __('User Profile')])

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form method="post" action="{{ route('profile.update') }}" autocomplete="off" class="form-horizontal"
                    enctype="multipart/form-data">
                    @csrf
                    @method('put')

                    <div class="card ">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">{{ __('Edit Profile') }}</h4>
                            <p class="card-category">{{ __('User information') }}</p>
                        </div>
                        <div class="card-body ">
                            @if (session('status'))
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="alert alert-success">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <i class="material-icons">close</i>
                                        </button>
                                        <span>{{ session('status') }}</span>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="row">
                                <div class="col-md-4 row" style="justify-content: center; align-content: center;">
                                    <div class="fileinput text-center fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail img-circle">
                                            @if(auth()->user()->avatar != null)
                                            <img src="{{auth()->user()->avatar}}" alt="...">
                                            @else
                                            <img src="{{ asset('material') }}/img/default.png" alt="...">
                                            @endif
                                        </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail img-circle" style="">
                                        </div>
                                        <div>
                                            <span class="btn btn-round btn-rose btn-file">
                                                <span class="fileinput-new"> Photo</span>
                                                <span class="fileinput-exists">Change</span>
                                                <input type="file" name="photo_path">
                                                <div class="ripple-container"></div>
                                            </span>
                                            <br>
                                            <a href="#pablo" class="btn btn-danger btn-round fileinput-exists"
                                                data-dismiss="fileinput"><i class="fa fa-times"></i> Remove<div
                                                    class="ripple-container">
                                                    <div class="ripple-decorator ripple-on ripple-out"
                                                        style="left: 80.0156px; top: 18px; background-color: rgb(255, 255, 255); transform: scale(15.5098);">
                                                    </div>
                                                    <div class="ripple-decorator ripple-on ripple-out"
                                                        style="left: 80.0156px; top: 18px; background-color: rgb(255, 255, 255); transform: scale(15.5098);">
                                                    </div>
                                                    <div class="ripple-decorator ripple-on ripple-out"
                                                        style="left: 80.0156px; top: 18px; background-color: rgb(255, 255, 255); transform: scale(15.5098);">
                                                    </div>
                                                </div></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8 row">
                                    <label class="col-md-2 col-form-label">{{ __('Name') }}</label>
                                    <div class="col-md-4">
                                        <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                            <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                                name="name" id="input-name" type="text" placeholder="{{ __('Name') }}"
                                                value="{{ old('name', auth()->user()->name) }}" required="true"
                                                aria-required="true" />
                                            @if ($errors->has('name'))
                                            <span id="name-error" class="error text-danger"
                                                for="input-name">{{ $errors->first('name') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <label class="col-sm-2 col-form-label">{{ __('Email') }}</label>
                                    <div class="col-sm-4">
                                        <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                            <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                                name="email" id="input-email" type="email"
                                                placeholder="{{ __('Email') }}"
                                                value="{{ old('email', auth()->user()->email) }}" required />
                                            @if ($errors->has('email'))
                                            <span id="email-error" class="error text-danger"
                                                for="input-email">{{ $errors->first('email') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <label class="col-sm-2 col-form-label">{{ __('Phone number') }}</label>
                                    <div class="col-sm-4">
                                        <div class="form-group{{ $errors->has('phonenumber') ? ' has-danger' : '' }}">
                                            <input
                                                class="form-control{{ $errors->has('phonenumber') ? ' is-invalid' : '' }}"
                                                name="phonenumber" id="input-phonenumber" type="phonenumber"
                                                placeholder="{{ __('Phone number') }}"
                                                value="{{ old('phonenumber', auth()->user()->phonenumber) }}"
                                                required />
                                            @if ($errors->has('phonenumber'))
                                            <span id="phonenumber-error" class="error text-danger"
                                                for="input-phonenumber">{{ $errors->first('phonenumber') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer ml-auto mr-auto">
                            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <form method="post" action="{{ route('profile.password') }}" class="form-horizontal">
                    @csrf
                    @method('put')

                    <div class="card ">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">{{ __('Change password') }}</h4>
                            <p class="card-category">{{ __('Password') }}</p>
                        </div>
                        <div class="card-body ">
                            @if (session('status_password'))
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="alert alert-success">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <i class="material-icons">close</i>
                                        </button>
                                        <span>{{ session('status_password') }}</span>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="row">
                                <label class="col-sm-2 col-form-label"
                                    for="input-current-password">{{ __('Current Password') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('old_password') ? ' has-danger' : '' }}">
                                        <input
                                            class="form-control{{ $errors->has('old_password') ? ' is-invalid' : '' }}"
                                            input type="password" name="old_password" id="input-current-password"
                                            placeholder="{{ __('Current Password') }}" value="" required />
                                        @if ($errors->has('old_password'))
                                        <span id="name-error" class="error text-danger"
                                            for="input-name">{{ $errors->first('old_password') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-2 col-form-label"
                                    for="input-password">{{ __('New Password') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                                        <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                            name="password" id="input-password" type="password"
                                            placeholder="{{ __('New Password') }}" value="" required />
                                        @if ($errors->has('password'))
                                        <span id="password-error" class="error text-danger"
                                            for="input-password">{{ $errors->first('password') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-2 col-form-label"
                                    for="input-password-confirmation">{{ __('Confirm New Password') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group">
                                        <input class="form-control" name="password_confirmation"
                                            id="input-password-confirmation" type="password"
                                            placeholder="{{ __('Confirm New Password') }}" value="" required />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer ml-auto mr-auto">
                            <button type="submit" class="btn btn-primary">{{ __('Change password') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script src="{{ asset('material') }}/js/plugins/jasny-bootstrap.min.js"></script>
@endpush