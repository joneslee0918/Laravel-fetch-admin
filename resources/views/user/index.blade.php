@extends('layouts.app', ['activePage' => 'user', 'titlePage' => __('Users Management')])
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="card registration">
            <div class="card-header card-header-primary">
                <h4 class="card-title">{{ __('Users') }}</h4>
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
                    <div class="col-sm-4">
                        <a href="{{route('user.create')}}" class="btn btn-sm btn-primary">{{ __('Add User') }}</a>
                    </div>
                    <div class="col-sm-8" style="text-align:right; margin-bottom:20px">
                    </div>
                </div>
                <div class="fresh-datatables">
                    <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0"
                        width="100%" style='text-align:center'>
                        <thead class=" text-primary">
                            <tr>
                                <th style="width:80px"> {{ __('No ') }} </th>
                                <th> {{ __('Avatar') }} </th>
                                <th> {{ __('Name') }} </th>
                                <th> {{ __('Email') }} </th>
                                <th> {{ __('Phone number') }} </th>
                                <th> {{ __('Account type') }} </th>
                                <th> {{ __('Create Date') }} </th>
                                <th> {{ __('Active') }} </th>
                                <th> {{ __('Terms') }} </th>
                                <th> {{ __('Action') }} </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['users'] as $index => $user)
                            <tr>
                                <td> {{$index+1}}</td>
                                <td rel="tooltip" data-original-title="{{$user->name}}" title="{{$user->name}}">
                                    @if($user->avatar)
                                    <img src="{{$user->avatar}}?{{time()}}"
                                        style="width:80px; height:80px; border-radius:50%">
                                    @else
                                    <img src="{{ asset('material') }}/img/default.png?{{time()}}" alt="..."
                                        style="width:80px; height:80px; border-radius:50%">
                                    @endif
                                </td>
                                <td> {{ $user->name }} </td>
                                <td> {{ $user->email }} </td>
                                <td> {{ $user->phonenumber }}</td>
                                <td rel="tooltip"
                                    data-original-title="<?php if($user->is_social == 0) echo('Normal Account'); else if($user->is_social == 1) echo('Google Account');else if($user->is_social == 3) echo('Apple Account');?>"
                                    title="<?php if($user->is_social == 0) echo('Normal Account'); else if($user->is_social == 1) echo('Google Account');else if($user->is_social == 3) echo('Apple Account');?>">
                                    @if($user->is_social == 0)
                                    <img src="{{ asset('material') }}/img/normal.png?{{time()}}" alt="..."
                                        style="width:25px; height:25px; border-radius:50%">
                                    @elseif($user->is_social == 1)
                                    <img src="{{ asset('material') }}/img/google.png?{{time()}}" alt="..."
                                        style="width:40px; height:40px; border-radius:50%">
                                    @else
                                    <img src="{{ asset('material') }}/img/apple.png?{{time()}}" alt="..."
                                        style="width:30px; height:30px; border-radius:50%">
                                    @endif
                                </td>
                                <td>{{date('M d Y', strtotime($user->created_at))}}</td>
                                <td>
                                    @if ($user->active)
                                    Allowed
                                    @else
                                    Blocked
                                    @endif
                                </td>
                                <td>
                                    @if ($user->terms)
                                    Agree
                                    @else
                                    Disagree
                                    @endif
                                </td>
                                <td>
                                    @if ($user->role != 1)
                                    <form action="{{ route('user.destroy', $user) }}" method="post">
                                        @csrf
                                        @method('delete')
                                        <a rel="tooltip" class="btn btn-success btn-link"
                                            href="{{ route('user.edit', $user) }}" data-original-title="Edit"
                                            title="Edit">
                                            <i class="material-icons">edit</i>
                                            <div class="ripple-container"></div>
                                        </a>
                                        <button rel="tooltip" type="button" class="btn btn-danger btn-link"
                                            data-original-title="Delete" title="Delete"
                                            onclick="confirm('{{ __("Are you sure you want to delete this user?") }}') ? this.parentElement.submit() : ''">
                                            <i class="material-icons">close</i>
                                            <div class="ripple-container"></div>
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Theme -->
    <div class="modal fade bd-example-modal-sm" id="position_modal" tabindex="-1" role="dialog"
        aria-labelledby="position_title" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="position_title">Positions management</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span
                            aria-hidden="true">&times;</span> </button>
                </div>
                <div class="modal-body row">
                    <div class="col-md-8" style="text-align:left; bottom:-15px">
                        Change order with Drag and Drop
                    </div>
                    <div class="col-md-4" style="text-align:right;">
                        <button rel="tooltip" type="button" class="btn btn-danger btn-round btn-sm"
                            data-original-title="Delete" title="Delete" onclick="addTheme()">
                            <i class="material-icons">add</i>
                        </button>
                    </div>
                    <table id="position_table" class="table table-striped table-no-bordered table-hover" cellspacing="0"
                        width="100%" style='text-align:center'>
                        <thead>
                            <tr>
                                <td></td>
                                <td style="width:200px"></td>
                            </tr>
                        </thead>
                        <tbody id="position_tbody">

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="saveThemes()">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    @endsection