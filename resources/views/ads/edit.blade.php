@extends('layouts.app', ['activePage' => 'ads', 'titlePage' => __('Ads Management')])

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form method="post" action="{{ route('ads.update', $ads) }}" autocomplete="off" class="form-horizontal"
                    enctype="multipart/form-data">
                    @csrf
                    @method('put')

                    <div class="card ">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">{{ __('Edit Ads') }}</h4>
                            <p class="card-category"></p>
                        </div>
                        <div class="card-body row">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('ads.index') }}"
                                    class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="row">
                                    <div class="col-md-8 row">
                                        <label class="col-sm-2 col-form-label"
                                            style="margin-top:10px">{{ __('User') }}</label>
                                        <div class="col-sm-4" style="margin-top:10px">
                                            <select class="selectpicker" name="user" data-style="btn btn-primary">
                                                @foreach($user as $key => $item)
                                                <option value="{{$item->id}}"
                                                    <?php echo $ads->id_user == $item->id ? 'selected' :''?>>
                                                    {{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <label class="col-sm-2 col-form-label"
                                            style="margin-top:10px">{{ __('Gender') }}</label>
                                        <div class="col-sm-4" style="margin-top:10px">
                                            <select class="selectpicker" name="gender" data-style="btn btn-primary">
                                                <option value="1" <?php echo $ads->gender == 1 ? 'selected':'' ?>>
                                                    Male</option>
                                                <option value="0" <?php echo $ads->gender == 0 ? 'selected':'' ?>>
                                                    Female</option>
                                            </select>
                                        </div>
                                        <label class="col-sm-2 col-form-label"
                                            style="margin-top:10px">{{ __('Category') }}</label>
                                        <div class="col-sm-4" style="margin-top:10px">
                                            <select class="selectpicker" name="category" data-style="btn btn-primary">
                                                @foreach($category as $key => $item)
                                                <option value="{{$item->id}}"
                                                    <?php echo $ads->id_category == $item->id ? 'selected' :''?>>
                                                    {{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <label class="col-sm-2 col-form-label"
                                            style="margin-top:10px">{{ __('Breed') }}</label>
                                        <div class="col-sm-4" style="margin-top:10px">
                                            <select class="selectpicker" name="breed" data-style="btn btn-primary">
                                                @foreach($breed as $key => $item)
                                                <option value="{{$item->id}}"
                                                    <?php echo $ads->id_breed == $item->id ? 'selected' :''?>>
                                                    {{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <label class="col-sm-2 col-form-label" for="input-age">{{ __('Age') }}</label>
                                        <div class="col-sm-4">
                                            <div class="form-group{{ $errors->has('age') ? ' has-danger' : '' }}">
                                                <input
                                                    class="form-control{{ $errors->has('age') ? ' is-invalid' : '' }}"
                                                    name="age" id="input-age" placeholder="{{ __('Age') }}"
                                                    value="{{ old('age', $ads->age) }}" required="true" type="number"
                                                    aria-required="true" />
                                                @if ($errors->has('age'))
                                                <span id="age-error" class="error text-danger"
                                                    for="input-age">{{ $errors->first('age') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <label class="col-sm-2 col-form-label"
                                            for="input-price">{{ __('Price') }}</label>
                                        <div class="col-sm-4">
                                            <div class="form-group{{ $errors->has('price') ? ' has-danger' : '' }}">
                                                <input
                                                    class="form-control{{ $errors->has('price') ? ' is-invalid' : '' }}"
                                                    name="price" id="input-price" placeholder="{{ __('Price') }}"
                                                    value="{{ old('price', $ads->price) }}" required="true"
                                                    type="number" aria-required="true" />
                                                @if ($errors->has('price'))
                                                <span id="price-error" class="error text-danger"
                                                    for="input-price">{{ $errors->first('price') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <label class="col-sm-2 col-form-label"
                                            for="input-lat">{{ __('Latitude') }}</label>
                                        <div class="col-sm-4">
                                            <div class="form-group{{ $errors->has('lat') ? ' has-danger' : '' }}">
                                                <input
                                                    class="form-control{{ $errors->has('lat') ? ' is-invalid' : '' }}"
                                                    name="lat" id="input-lat" placeholder="{{ __('Latitude') }}"
                                                    value="{{ old('lat', $ads->lat) }}" required="true" type="number"
                                                    aria-required="true" />
                                                @if ($errors->has('lat'))
                                                <span id="lat-error" class="error text-danger"
                                                    for="input-lat">{{ $errors->first('lat') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <label class="col-sm-2 col-form-label"
                                            for="input-long">{{ __('Longitude') }}</label>
                                        <div class="col-sm-4">
                                            <div class="form-group{{ $errors->has('long') ? ' has-danger' : '' }}">
                                                <input
                                                    class="form-control{{ $errors->has('long') ? ' is-invalid' : '' }}"
                                                    name="long" id="input-long" placeholder="{{ __('long') }}"
                                                    value="{{ old('long', $ads->long) }}" required="true" type="number"
                                                    aria-required="true" />
                                                @if ($errors->has('long'))
                                                <span id="long-error" class="error text-danger"
                                                    for="input-v">{{ $errors->first('long') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <label class="col-sm-2 col-form-label">{{ __('Status') }}</label>
                                        <div class="col-sm-4">
                                            <div class="togglebutton">
                                                <label class="col-form-label">
                                                    <input type="checkbox" class="status_switch"
                                                        onclick="toggleStatus(1, this.checked)"
                                                        <?php echo (intval ($ads->status) == 1 ? "checked" : '') ?>>
                                                    <span class="toggle"></span>
                                                </label>
                                            </div>
                                            <input type="hidden" id="status" name="status" value="{{$ads->status}}">
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
                    <div class="row" style="margin-bottom:10px">
                        <div class="col-md-12">
                            <button type="button" onclick="addNewImage()" class="btn btn-primary">Add Image</button>
                        </div>
                    </div>
                    <div class="col-sm-12 row" id="ad_image_container">
                        @foreach($ad_images as $index => $item)
                        <div class="col-md-2" id="ad_image_item_{{$index}}">
                            <div class="row"
                                style="position:absolute; top:5px; left:35px; border-radius:50%; background:#ffffffab; width:30px; height:30px; padding:2px 3px">
                                <a onclick="deleteImage({{$item['id']}}, {{$index}})" style="cursor:pointer">
                                    <i class="material-icons">close</i>
                                </a>
                            </div>
                            <div class="fileinput text-center fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail">
                                    <img src="{{$item->meta_value}}?{{time()}}" style="width:100%; height:150px"
                                        alt="...">
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail"
                                    style="width:100%; height:150px"></div>
                                <div>
                                    <span class="btn btn-round btn-rose btn-file">
                                        <span class="fileinput-new"> Photo</span>
                                        <span class="fileinput-exists">Change</span>
                                        <input type="file" name="photo_path[]">
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
                        @endforeach
                        <input type="hidden" id="ad_image_count" name="ad_image_count" value="{{count($ad_images)}}">
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

var toggleStatus = function(type, value) {
    if (type == 1) {
        $('#status').val(value ? 1 : 0);
    }
}

var deleteImage = function(id, index) {
    var question = confirm("Are you sure you want to delete this image?");
    if (!question)
        return;

    if (id == 0) {
        $('#ad_image_item_' + index).remove();

        var ad_image_count = $('#ad_image_count').val();
        ad_image_count--;
        $('#ad_image_count').val(ad_image_count);
        return;
    }
    var url = "../image/delete";
    $.ajax({
        url: url,
        data: {
            id: id
        },
        method: 'post',
        success: function(result) {
            if (result == 'success') {
                $('#ad_image_item_' + index).remove();
                showToast('success', "Ads image successfullly removed.");

                var ad_image_count = $('#ad_image_count').val();
                ad_image_count--;
                $('#ad_image_count').val(ad_image_count);
            } else if (result == 'failed') {
                showToast('danger',
                    "This ads image can't delete. Because there is only 5 image on current ads.");
            }
        },
        error: function(xhr, status, error) {
            location.reload();
        }
    });
}

var addNewImage = function() {
    var ad_image_count = $('#ad_image_count').val();

    var html = '';

    html += `<div class="col-md-2" id="ad_image_item_${ad_image_count}">
                <div class="row"
                    style="position:absolute; top:5px; left:35px; border-radius:50%; background:#ffffffab; width:30px; height:30px; padding:2px 3px">
                    <a onclick="deleteImage(0, ${ad_image_count})" style="cursor:pointer">
                        <i class="material-icons">close</i>
                    </a>
                </div>
                <div class="fileinput text-center fileinput-new" data-provides="fileinput">
                    <div class="fileinput-new thumbnail">
                        <img src="{{ asset('material') }}/img/Default_Thumbnail.png?{{time()}}" style="width:100%; height:150px"
                            alt="...">
                    </div>
                    <div class="fileinput-preview fileinput-exists thumbnail"
                        style="width:100%; height:150px"></div>
                    <div>
                        <span class="btn btn-round btn-rose btn-file">
                            <span class="fileinput-new"> Photo</span>
                            <span class="fileinput-exists">Change</span>
                            <input type="file" name="photo_path[]">
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
            </div>`;

    $('#ad_image_container').append(html);

    ad_image_count++;
    $('#ad_image_count').val(ad_image_count);
}
</script>
@endpush