@extends('layouts.app', ['activePage' => 'category', 'titlePage' => __('Category Management')])

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <form method="post" action="{{ route('category.store') }}" autocomplete="off" class="form-horizontal"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="card ">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">{{ __('Add Category') }}</h4>
                            <p class="card-category"></p>
                        </div>
                        <div class="card-body">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('category.index') }}"
                                    class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="row">
                                    <div class="col-md-4 row" style="justify-content: center; align-content: center;">
                                        <div class="fileinput text-center fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail img-circle">
                                                <img src="{{ asset('material') }}/img/normal.png" alt="...">
                                            </div>
                                            <div class="fileinput-preview fileinput-exists thumbnail img-circle"
                                                style=""></div>
                                            <div>
                                                <span class="btn btn-round btn-rose btn-file">
                                                    <span class="fileinput-new"> Photo</span>
                                                    <span class="fileinput-exists">Change</span>
                                                    <input type="file" value="" name="photo_path" required>
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
                                        <label class="col-md-3 col-form-label">{{ __('Name') }}</label>
                                        <div class="col-md-9">
                                            <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                                <input
                                                    class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                                    name="name" id="input-name" type="text"
                                                    placeholder="{{ __('Name') }}" value="" required="true"
                                                    aria-required="true" />
                                                @if ($errors->has('name'))
                                                <span id="name-error" class="error text-danger"
                                                    for="input-name">{{ $errors->first('name') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <label class="col-sm-3 col-form-label">{{ __('Description') }}</label>
                                        <div class="col-sm-9">
                                            <div class="form-group{{ $errors->has('etc') ? ' has-danger' : '' }}">
                                                <input
                                                    class="form-control{{ $errors->has('etc') ? ' is-invalid' : '' }}"
                                                    name="etc" id="input-etc" placeholder="{{ __('Description') }}"
                                                    value="" />
                                                @if ($errors->has('etc'))
                                                <span id="etc-error" class="error text-danger"
                                                    for="input-etc">{{ $errors->first('etc') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <div class="form-check mr-auto">
                            </div>
                            <button type="submit" class="btn btn-primary">Register</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script src="{{ asset('material') }}/js/plugins/jasny-bootstrap.min.js"></script>
<script>
$('document').ready(function() {
    var message = `<?php echo Session::get('error')?>`;
    if (message != '') {
        showToast('danger', message)
    }
    message = `<?php echo Session::get('status')?>`;
    if (message != '') {
        showToast('success', message)
    }
})
</script>
@endpush