@extends('layouts.app', ['activePage' => 'category', 'titlePage' => __('Category Management')])
@section('content')
<div class="content">
    <div class="container-fluid row">
        <div class="col-sm-2"></div>
        <div class="card registration col-sm-8">
            <div class="card-header card-header-primary">
                <h4 class="card-title">{{ __('Categories') }}</h4>
            </div>
            <div class="card-body">
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
                <div class="row">
                    <div class="col-sm-4">
                        <button type="button" data-toggle="modal" data-target="#category_add_modal"
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
                                <td> {{ $category->name }} </td>
                                <td>{{date('M d Y', strtotime($category->created_at))}}</td>
                                <td>{{count($category->ads)}}</td>
                                <td> {{ $category->etc }} </td>
                                <td>
                                    <form action="{{ route('category.destroy', $category) }}" method="post">
                                        @csrf
                                        @method('delete')
                                        <a rel="tooltip" class="btn btn-success btn-link"
                                            onclick="showEditModal({{$category}})" data-original-title="Edit"
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

    <!-- START ADD MODAL -->
    <div class="modal fade bd-example-modal-sm" id="category_add_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <form method="post" action="{{ route('category.store') }}" autocomplete="off" class="form-horizontal"
            enctype="multipart/form-data">
            @csrf
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="position_title">Add New Category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span
                                aria-hidden="true">&times;</span> </button>
                    </div>
                    <div class="modal-body row">
                        <label class="col-sm-1"></label>
                        <div class="col-sm-10">
                            <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name"
                                    id="input-name" type="name" placeholder="{{ __('Name') }}" value="" required />
                                @if ($errors->has('name'))
                                <span id="name-error" class="error text-danger"
                                    for="input-name">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>
                        <label class="col-sm-1"></label>
                        <label class="col-sm-1"></label>
                        <div class="col-sm-10">
                            <div class="form-group{{ $errors->has('etc') ? ' has-danger' : '' }}">
                                <input class="form-control{{ $errors->has('etc') ? ' is-invalid' : '' }}" name="etc"
                                    id="input-etc" type="etc" placeholder="{{ __('Description...') }}" value="" />
                                @if ($errors->has('etc'))
                                <span id="etc-error" class="error text-danger"
                                    for="input-etc">{{ $errors->first('etc') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- END ADD MODAL -->

    <!-- START EDIT MODAL -->
    <div class="modal fade bd-example-modal-sm" id="category_edit_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <form method="post" action="{{ route('category.update', '22') }}" autocomplete="off" class="form-horizontal"
            enctype="multipart/form-data">
            @csrf
            @method('put')
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="position_title">Edit Category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span
                                aria-hidden="true">&times;</span> </button>
                    </div>
                    <div class="modal-body row">
                        <label class="col-sm-1"></label>
                        <input type="hidden" name="id_category" id="id_category">
                        <div class="col-sm-10">
                            <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                    name="edit_name" id="edit_name" type="name" placeholder="{{ __('Name') }}" value=""
                                    required />
                                @if ($errors->has('name'))
                                <span id="name-error" class="error text-danger"
                                    for="edit_name">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>
                        <label class="col-sm-1"></label>
                        <label class="col-sm-1"></label>
                        <div class="col-sm-10">
                            <div class="form-group{{ $errors->has('etc') ? ' has-danger' : '' }}">
                                <input class="form-control{{ $errors->has('etc') ? ' is-invalid' : '' }}"
                                    name="edit_etc" id="edit_etc" type="etc" placeholder="{{ __('Description...') }}"
                                    value="" />
                                @if ($errors->has('etc'))
                                <span id="etc-error" class="error text-danger"
                                    for="edit_etc">{{ $errors->first('etc') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Edit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- END EDIT MODAL -->

</div>
@endsection
@push('js')
<script>
function showEditModal(category) {
    $('#id_category').val(category.id);
    $('#edit_name').val(category.name);
    $('#edit_etc').val(category.etc);
    $('#category_edit_modal').modal('show');
}
</script>
@endpush