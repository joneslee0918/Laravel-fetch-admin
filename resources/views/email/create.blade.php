@extends('layouts.app', ['activePage' => 'email', 'titlePage' => __('Email Management')])

@section('content')
<style>
.filter-option-inner-inner {
    color: black !important;
}
</style>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <form method="post" action="{{ route('email.store') }}" autocomplete="off" class="form-horizontal"
                    id="newsForm">
                    @csrf
                    <div class="card ">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">{{ __('Send Email') }}</h4>
                            <p class="card-category"></p>
                        </div>
                        <div class="card-body row">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('email.index') }}"
                                    class="btn btn-primary">{{ __('Back to list') }}</a>
                            </div>
                            <div class="col-md-12">
                                <div class="form-check mr-auto">
                                    <label class="btn btn-primary">
                                        <input class="form-check-input" name='select_all' type="checkbox"
                                            value="checked" id="select_all">Select All
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-12" style="height:50px">
                                <select multiple class="form-control selectpicker" data-style="btn btn-link"
                                    style="color:black" name="email[]" id="email" required>
                                    @foreach($users as $index => $user)
                                    <option <?php echo $index == 0 ? "selected" : "" ?> value="{{$user->id}}">
                                        {{$user->email}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <input type="text" name="title" class="form-control" id="title" placeholder="Subject"
                                    required>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group{{ $errors->has('content') ? ' has-danger' : '' }}">
                                    <textarea id="content" name="content"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <div class="mr-auto">
                            </div>
                            <button type="submit" class="btn btn-primary">Send Mail</button>
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
$("#newsForm").submit(function(event) {
    $("#content").val($("#content").summernote("code"));
});

$("#select_all").click(function(e) {
    $('#email option').prop('selected', true);
})
</script>
@endpush