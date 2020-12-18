@extends('layouts.app', ['activePage' => 'user', 'titlePage' => __('Users Management')])

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                @if (session('error'))
                <div class="row">
                    <div class="col-sm-12">
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <i class="material-icons">close</i>
                            </button>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                </div>
                @elseif (session('status'))
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
                <form method="post" action="{{ route('user.update', $user) }}" autocomplete="off"
                    class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    @method('put')

                    <div class="card ">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">{{ __('Edit User') }}</h4>
                            <p class="card-category"></p>
                        </div>
                        <div class="card-body row">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('user.index') }}"
                                    class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="row">
                                    <div class="col-md-4 row" style="justify-content: center; align-content: center;">
                                        <div class="fileinput text-center fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail img-circle">
                                                @if($user->avatar)
                                                <img src="{{$user->avatar}}?{{time()}}" style="width:100px;height:100px"
                                                    alt="...">
                                                @else
                                                <img src="{{ asset('material') }}/img/default.png"
                                                    style="width:100px;height:100px" alt="...">
                                                @endif
                                            </div>
                                            <div class="fileinput-preview fileinput-exists thumbnail img-circle"
                                                style=""></div>
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
                                                <input
                                                    class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                                    name="name" id="input-name" type="text"
                                                    placeholder="{{ __('Name') }}"
                                                    value="{{ old('name', $user->name) }}" required="true"
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
                                                <input
                                                    class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                                    name="email" id="input-email" type="email"
                                                    placeholder="{{ __('Email') }}"
                                                    value="{{ old('email', $user->email) }}" required />
                                                @if ($errors->has('email'))
                                                <span id="email-error" class="error text-danger"
                                                    for="input-email">{{ $errors->first('email') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <label class="col-sm-2 col-form-label"
                                            for="input-password">{{ __('New Password') }}</label>
                                        <div class="col-sm-4">
                                            <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                                                <input
                                                    class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                                    name="password" id="input-password" type="password"
                                                    placeholder="{{ __('New Password') }}"
                                                    <?php echo $user->is_social != 0 ? 'disabled' : '' ?> />
                                                @if ($errors->has('password'))
                                                <span id="password-error" class="error text-danger"
                                                    for="input-password">{{ $errors->first('password') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <label class="col-sm-2 col-form-label">{{ __('Phone number') }}</label>
                                        <div class="col-sm-4">
                                            <div
                                                class="form-group{{ $errors->has('phonenumber') ? ' has-danger' : '' }}">
                                                <input
                                                    class="form-control{{ $errors->has('phonenumber') ? ' is-invalid' : '' }}"
                                                    name="phonenumber" id="input-phonenumber" type="phonenumber"
                                                    placeholder="{{ __('Phone number') }}"
                                                    value="{{ old('phonenumber', $user->phonenumber) }}" required />
                                                @if ($errors->has('phonenumber'))
                                                <span id="phonenumber-error" class="error text-danger"
                                                    for="input-phonenumber">{{ $errors->first('phonenumber') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <label class="col-sm-2 col-form-label">{{ __('Term Agree') }}</label>
                                        <div class="col-sm-4">
                                            <div class="togglebutton">
                                                <label class="col-form-label">
                                                    <input type="checkbox" class="term_switch"
                                                        onclick="toggleStatus(1, this.checked)"
                                                        <?php echo (intval ($user->terms) == 1 ? "checked" : '') ?>>
                                                    <span class="toggle"></span>
                                                </label>
                                            </div>
                                            <input type="hidden" id="terms" name="terms" value="{{$user->terms}}">
                                        </div>
                                        <label class="col-sm-2 col-form-label">{{ __('Active') }}</label>
                                        <div class="col-sm-4">
                                            <div class="togglebutton">
                                                <label class="col-form-label">
                                                    <input type="checkbox" class="role_switch"
                                                        onclick="toggleStatus(2, this.checked)"
                                                        <?php echo (intval ($user->active) == 1 ? "checked" : '') ?>>
                                                    <span class="toggle"></span>
                                                </label>
                                            </div>
                                            <input type="hidden" id="active" name="active" value="{{$user->active}}">
                                        </div>
                                        <label
                                            class="col-sm-2 col-form-label">{{ __('Show Phone Number On Ads') }}</label>
                                        <div class="col-sm-4">
                                            <div class="togglebutton">
                                                <label class="col-form-label">
                                                    <input type="checkbox" class="term_switch"
                                                        onclick="toggleStatus(3, this.checked)"
                                                        <?php echo (intval ($user_meta['_show_phone_on_ads']) == 1 ? "checked" : '') ?>>
                                                    <span class="toggle"></span>
                                                </label>
                                            </div>
                                            <input type="hidden" id="_show_phone_on_ads" name="_show_phone_on_ads"
                                                value="{{$user_meta['_show_phone_on_ads']}}">
                                        </div>
                                        <label class="col-sm-2 col-form-label">{{ __('Show Notification') }}</label>
                                        <div class="col-sm-4">
                                            <div class="togglebutton">
                                                <label class="col-form-label">
                                                    <input type="checkbox" class="role_switch"
                                                        onclick="toggleStatus(4, this.checked)"
                                                        <?php echo (intval ($user_meta['_show_notification']) == 1 ? "checked" : '') ?>>
                                                    <span class="toggle"></span>
                                                </label>
                                            </div>
                                            <input type="hidden" id="_show_notification" name="_show_notification"
                                                value="{{$user_meta['_show_notification']}}">
                                        </div>
                                        <label class="col-sm-2 col-form-label"
                                            style="margin-top:10px">{{ __('Account type') }}</label>
                                        <div class="col-sm-4" style="margin-top:10px">
                                            <select onchange="_onChangeAccountType(this.value)" class="selectpicker"
                                                name="is_social" data-style="btn btn-primary">
                                                <option value="0"
                                                    <?php echo (intval ($user->is_social) == 0 ? "selected" : '')?>>
                                                    Normal Account</option>
                                                <option value="1"
                                                    <?php echo (intval ($user->is_social) == 1 ? "selected" : '')?>>
                                                    Google Account</option>
                                                <option value="3"
                                                    <?php echo (intval ($user->is_social) == 3 ? "selected" : '')?>>
                                                    Apple Account</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
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

<script>
var toggleStatus = function(type, value) {
    if (type == 1) {
        $('#terms').val(value ? 1 : 0);
    } else if (type == 2) {
        $('#active').val(value ? 1 : 0);
    } else if (type == 3) {
        $('#_show_phone_on_ads').val(value ? 1 : 0);
    } else if (type == 4) {
        $('#_show_notification').val(value ? 1 : 0);
    }
}

var _onChangeAccountType = function(value) {
    if (value == 0) {
        $('#input-password').removeAttr('disabled');
    } else {
        $('#input-password').attr('disabled', 'true');
    }
}
</script>
@endpush