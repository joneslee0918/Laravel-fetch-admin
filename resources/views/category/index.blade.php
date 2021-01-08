@extends('layouts.app', ['activePage' => 'category', 'titlePage' => __('Category Management')])
@section('content')
<div class="content">
    <div class="container-fluid row">
        <div class="col-sm-2"></div>
        <div class="card registration col-sm-8">
            <div class="card-header card-header-primary">
                <h4 class="card-title">{{ __('Category') }}</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-4">
                        <a href="{{route('category.create')}}"
                            class="btn btn-sm btn-primary">{{ __('Add Category') }}</a>
                    </div>
                    <div class="col-sm-6" style="text-align:right; margin-bottom:20px">
                    </div>
                </div>
                <div class="fresh-datatables">
                    <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0"
                        width="100%" style='text-align:center'>
                        <thead class=" text-primary">
                            <tr>
                                <th style="width:80px"> {{ __('No ') }} </th>
                                <th> {{ __('Icon') }} </th>
                                <th> {{ __('Name') }} </th>
                                <th> {{ __('Create Date') }} </th>
                                <th> {{ __('Registered Ads') }} </th>
                                <th> {{ __('Description') }} </th>
                                <th> {{ __('Action') }} </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $index => $category)
                            <tr>
                                <td> {{$index+1}}</td>
                                <td>
                                    @if($category->icon)
                                    <img src="{{$category->icon}}?{{time()}}"
                                        style="width:50px; height:50px; border-radius:50%">
                                    @endif
                                </td>
                                <td> {{ $category->name }} </td>
                                <td>{{date('M d Y', strtotime($category->created_at))}}</td>
                                <td>{{count($category->ads)}}</td>
                                <td> {{ $category->etc }} </td>
                                <td>
                                    <form action="{{ route('category.destroy', $category) }}" method="post">
                                        @csrf
                                        @method('delete')
                                        <a rel="tooltip" class="btn btn-success btn-link"
                                            href="{{ route('category.edit', $category) }}" data-original-title="Edit"
                                            title="Edit">
                                            <i class="material-icons">edit</i>
                                            <div class="ripple-container"></div>
                                        </a>
                                        <button rel="tooltip" type="button" class="btn btn-danger btn-link"
                                            data-original-title="Delete" title="Delete"
                                            onclick="confirm('{{ __("Are you sure you want to delete this category?") }}') ? this.parentElement.submit() : ''">
                                            <i class="material-icons">close</i>
                                            <div class="ripple-container"></div>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
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