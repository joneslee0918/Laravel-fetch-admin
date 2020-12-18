@extends('layouts.app', ['activePage' => 'user', 'titlePage' => __('Users Management')])
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="card registration">
            <div class="card-header card-header-primary">
                <h4 class="card-title">{{ __('Users') }}</h4>
            </div>
            <div class="card-body ">
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
                                <th> {{ __('Role') }} </th>
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
                                    @if ($user->active == 1)
                                    Allowed
                                    @else
                                    Blocked
                                    @endif
                                </td>
                                <td>
                                    @if ($user->terms == 1)
                                    Agree
                                    @else
                                    Disagree
                                    @endif
                                </td>
                                <td>
                                    @if ($user->role == 1)
                                    SuperAdmin
                                    @else
                                    User
                                    @endif
                                </td>
                                <td>
                                    @if($user->role == 0)
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
                                            onclick="confirm('{{ __("Will be deleted all this user data. Are you sure you want to delete this user?") }}') ? this.parentElement.submit() : ''">
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