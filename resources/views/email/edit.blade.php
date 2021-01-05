@extends('layouts.app', ['activePage' => 'email', 'titlePage' => __('Email Management')])

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <form method="post" action="{{ route('email.update', $email) }}" autocomplete="off"
                    class="form-horizontal" id="newsForm">
                    @method('put')
                    @csrf
                    <div class="card ">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">{{ __('Change model') }}</h4>
                            <p class="card-category"></p>
                        </div>
                        <div class="card-body row">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('email.index') }}"
                                    class="btn btn-primary">{{ __('Back to list') }}</a>
                            </div>
                            <div class="col-md-12">
                                <input type="text" name="title" class="form-control" id="title" placeholder="Subject"
                                    value="{{$email->title}}">
                                <br />
                                <div class="form-group{{ $errors->has('content') ? ' has-danger' : '' }}">
                                    <textarea id="content" name="content"></textarea>
                                    <span style="color:red">
                                        *
                                        <?php
                                            if ($email->type == 0) echo "{password}";
                                            else if($email->type == 1) echo "";
                                            else if($email->type == 2) echo "";
                                            else if($email->type == 3) echo "{verify_code}";
                                            else if($email->type == 4) echo "";
                                            else if($email->type == 5) echo "{status}";
                                            else if($email->type == 6) echo "";
                                        ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <div class="mr-auto">
                            </div>
                            <button type="submit" class="btn btn-primary">Edit Mail</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="{{ asset('material') }}/js/pages/sumernote.js"></script>
<script>
$(document).ready(function() {

    $("#content").val($("#content").summernote("code"));
    $("#content").summernote('code', `<?php echo $email->content ?>`);
});
$("#newsForm").submit(function(event) {
    $("#content").val($("#content").summernote("code"));
});
</script>
@endpush