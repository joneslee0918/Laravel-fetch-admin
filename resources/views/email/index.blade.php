@extends('layouts.app', ['activePage' => 'email', 'titlePage' => __('Email Management')])

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="card registration">
            <div class="card-header card-header-primary">
                <h4 class="card-title">{{ __('Email') }}</h4>
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
                    <div class="col-sm-12" style="text-align:right; margin-bottom:20px">
                        <a href="{{route('email.create')}}" class="btn btn-sm btn-primary">{{ __('Send email') }}</a>
                    </div>
                    <!-- <div class="col-sm-5" style="padding:3%" id="accordion" role="tablist">
                        @foreach($default_mail as $index => $mail)
                        <div class="card card-collapse">
                            <div class="card-header" role="tab" id="headingOne">
                                <h5 class="mb-0">
                                    <a data-toggle="collapse" href="#collapse_{{$mail->id}}"
                                        <?php echo ($index == 0 ? 'aria-expanded="true"' : '')?>
                                        aria-controls="collapse_{{$mail->id}}">
                                        <?php
                                            if ($mail->type == 0) echo "Add user in admin panel";
                                            else if($mail->type == 1) echo "User register in app";
                                            else if($mail->type == 2) echo "Change password in app";
                                            else if($mail->type == 4) echo "Change user info in admin panel.";
                                            else if($mail->type == 5) echo "Account status(activate/deactivate) (to user)";
                                        ?>  
                                        <i class="material-icons">keyboard_arrow_down</i>
                                    </a>
                                </h5>
                            </div>
                            <div id="collapse_{{$mail->id}}" class="collapse  <?php echo ($index == 0 ? 'show' : '')?>"
                                role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="card-body">
                                    <a rel="tooltip" class="btn btn-success btn-link"
                                        href="{{ route('email.edit', $mail) }}" data-original-title="Edit" title="Edit"
                                        style="float:right; top:-10px">
                                        <i class="material-icons">edit</i>
                                        <div class="ripple-container"></div>
                                    </a>
                                    <h5>{{$mail->title}}</h5>
                                    <p><?php echo $mail->content ?></p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div> -->
                    <div class="col-sm-12">
                        <div class="fresh-datatables">
                            <table id="datatables" class="table table-striped table-no-bordered table-hover"
                                cellspacing="0" width="100%" style='text-align:center'>
                                <thead class=" text-primary">
                                    <tr>
                                        <th style="width:80px"> {{ __('No') }} </th>
                                        <th style="width:80px"> {{ __('email') }} </th>
                                        <th style="width:120px"> {{ __('Title') }} </th>
                                        <th> {{ __('Content') }} </th>
                                        <th style="width:180px"> {{ __('Date') }} </th>
                                        <th style="width:180px"> {{ __('Action') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sended_mail as $index => $mail)
                                    <tr>
                                        <td>{{$index+1}}</td>
                                        <td>{{$mail->user ? $mail->user->email : ""}}</td>
                                        <td>{{$mail->title}}</td>
                                        <td>{{$mail->content}}</td>
                                        <td>{{date('H:i d M Y', strtotime($mail->created_at))}}</td>
                                        <td>
                                            <form action="{{ route('email.destroy', $mail) }}" method="post">
                                                @csrf
                                                @method('delete')
                                                <button type="button" class="btn btn-danger btn-link"
                                                    data-original-title="Delete" title="Delete"
                                                    onclick="confirm('{{ __("Are you sure you want to delete this team?") }}') ? this.parentElement.submit() : ''">
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
    </div>
</div>
@endsection
@push('js')
@endpush