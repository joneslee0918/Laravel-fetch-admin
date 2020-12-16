@extends('layouts.app', ['activePage' => 'ads', 'titlePage' => __('Ads Management')])
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="card registration">
            <div class="card-header card-header-primary">
                <h4 class="card-title">{{ __('Ads') }}</h4>
            </div>
            <div class="card-body ">
                <div class="row">
                    <div class="col-sm-4">
                        <a href="{{route('ads.create')}}" class="btn btn-sm btn-primary">{{ __('Add Ads') }}</a>
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
                                <th> {{ __('Ad Image') }} </th>
                                <th> {{ __('User') }} </th>
                                <th> {{ __('Category') }} </th>
                                <th> {{ __('Breed') }} </th>
                                <th> {{ __('Age') }} </th>
                                <th> {{ __('Gender') }} </th>
                                <th> {{ __('Price') }} </th>
                                <th> {{ __('Latitude') }} </th>
                                <th> {{ __('Longitude') }} </th>
                                <th> {{ __('Description') }} </th>
                                <th> {{ __('Status') }} </th>
                                <th> {{ __('Likes') }} </th>
                                <th> {{ __('Views') }} </th>
                                <th> {{ __('Create Date') }} </th>
                                <th> {{ __('Action') }} </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ads_data as $index => $ads)
                            <?php $click = "data-toggle='modal' data-target='#detail_".$index."' style='cursor:pointer'";?>
                            <tr>
                                <td> {{$index+1}}</td>
                                <td <?php echo $click; ?> rel="tooltip" data-original-title="Click to view more images."
                                    title="Click to view more images.">
                                    <img src="{{$ads->ad_image}}?{{time()}}"
                                        style="width:80px; height:80px; border-radius:50%">
                                </td>
                                <td> {{ $ads->user->name }} </td>
                                <td> {{ $ads->category->name }} </td>
                                <td> {{ $ads->breed->name }} </td>
                                <td> {{ $ads->age }}</td>
                                <td>
                                    @if($ads->gender == 1)
                                    Male
                                    @else
                                    Female
                                    @endif
                                </td>
                                <td> {{ $ads->price }} $</td>
                                <td> {{ $ads->lat }}</td>
                                <td> {{ $ads->long }}</td>
                                <td> {{ $ads->description }}</td>
                                <td>
                                    @if($ads->status == 1)
                                    Active
                                    @else
                                    Closed
                                    @endif
                                </td>
                                <td> {{ $ads->likes }}</td>
                                <td> {{ $ads->views }}</td>
                                <td>{{date('M d Y', strtotime($ads->created_at))}}</td>
                                <td>
                                    <form action="{{ route('ads.destroy', $ads) }}" method="post">
                                        @csrf
                                        @method('delete')
                                        <a rel="tooltip" class="btn btn-success btn-link"
                                            href="{{ route('ads.edit', $ads) }}" data-original-title="Edit"
                                            title="Edit">
                                            <i class="material-icons">edit</i>
                                            <div class="ripple-container"></div>
                                        </a>
                                        <button rel="tooltip" type="button" class="btn btn-danger btn-link"
                                            data-original-title="Delete" title="Delete"
                                            onclick="confirm('{{ __("Are you sure you want to delete this ads?") }}') ? this.parentElement.submit() : ''">
                                            <i class="material-icons">close</i>
                                            <div class="ripple-container"></div>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <div class="modal right fade" id="detail_{{$index}}" tabindex="-1" role="dialog"
                                aria-labelledby="title">
                                <div class="modal-dialog" role="document" style="width:350px">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        </div>
                                        <div class="modal-body row">
                                            @foreach($ads->meta as $meta_key => $meta_item)
                                            @if($meta_item->meta_key == '_ad_image')
                                            <div style="margin-left:75px; margin-right:75px; margin-bottom:10px">
                                                <img src="{{$meta_item->meta_value}}?{{time()}}"
                                                    style="width:200px; height:150px;">
                                            </div>
                                            @endif`
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
@endsection
@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
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