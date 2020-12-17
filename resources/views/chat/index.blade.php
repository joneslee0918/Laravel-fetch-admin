@extends('layouts.app', ['activePage' => 'chat', 'titlePage' => __('Chat Management')])

@section('content')
<div class="content" style="width:90%; margin:auto; margin-top:60px">
    <div class="container-fluid">
        <div class="card registration">
            <div class="card-header card-header-primary text-right">
                <div class="btn-group" style="margin:0">
                </div>
            </div>
            <div class="card-body ">
                <div class="messaging">
                    <div class="inbox_msg">
                        <div class="inbox_people">
                            <div class="inbox_chat">
                                @foreach($chat as $index => $item)
                                <div class="chat_list active_chat" id="chat{{$item->id}}">
                                    <div class="chat_people" style="cursor: pointer;">
                                        <div class="row">
                                            <div class="col">
                                                <div class="chat_img" style="width:30%">
                                                    @if($item->sender->avatar)
                                                    <img src="{{$item->sender->avatar}}?{{time()}}" style="">
                                                    @else
                                                    <img src="{{ asset('material') }}/img/default.png?{{time()}}"
                                                        alt="..." style="">
                                                    @endif
                                                </div>
                                                <div class="chat_ib" style="width:70%">
                                                    <h5>{{$item->sender->name}}</h5>
                                                    <h5>{{$item->sender->phonenumber}}</h5>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="chat_img" style="width:30%; float:right">
                                                    @if($item->receiver->avatar)
                                                    <img src="{{$item->receiver->avatar}}?{{time()}}" style="">
                                                    @else
                                                    <img src="{{ asset('material') }}/img/default.png?{{time()}}"
                                                        alt="..." style="">
                                                    @endif
                                                </div>
                                                <div class="chat_ib"
                                                    style="width:70%; padding-right:10%; text-align:right">
                                                    <h5>{{$item->receiver->name}}</h5>
                                                    <h5>{{$item->receiver->phonenumber}}</h5>
                                                </div>
                                            </div>
                                            <div class="col-12" style="text-align:right; margin-right:10%">
                                                @if($item->ads->meta[0])
                                                <img src="{{$item->ads->meta[0]->meta_value }}" style="width:50px; height:50px; border-radius:50%; margin-right:40px"
                                                    alt="...">
                                                    @endif
                                                <span
                                                    class="chat_date">{{date('H:i | M d Y', strtotime($item->created_at))}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <!-- message -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection