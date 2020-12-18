@extends('layouts.app', ['activePage' => 'chat', 'titlePage' => __('Chat Management')])

@section('content')
<div class="content" style="width:90%; margin:auto; margin-top:60px">
    <div class="container-fluid">
        <div class="card registration">
            <div class="card-header card-header-primary text-right">
            </div>
            <div class="card-body ">
                <div class="messaging">
                    <div class="inbox_msg">
                        <div class="inbox_people">
                            <div class="inbox_chat">
                                @foreach($chat as $index => $item)
                                <div class="chat_list active_chat" id="chat{{$item->id}}">
                                    <div class="chat_people" style="cursor: pointer;"
                                        onClick="getMessage({{$item->id}})">
                                        <div class="row">
                                            <div class="col">
                                                <div class="chat_img" style="width:30%">
                                                    @if($item->sender->avatar)
                                                    <img src="{{$item->sender->avatar}}?{{time()}}"
                                                        style="max-width:50px; height:50px">
                                                    @else
                                                    <img src="{{ asset('material') }}/img/default.png?{{time()}}"
                                                        alt="..." style="max-width:50px; height:50px">
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
                                                    <img src="{{$item->receiver->avatar}}?{{time()}}"
                                                        style="max-width:50px; height:50px">
                                                    @else
                                                    <img src="{{ asset('material') }}/img/default.png?{{time()}}"
                                                        alt="..." style="max-width:50px; height:50px">
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
                                                <img src="{{$item->ads->meta[0]->meta_value }}"
                                                    style="width:40px; height:40px; border-radius:50%; margin-right:50px"
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
                        <div class="mesgs">
                            <div class="msg_history" id="message_container">
                                @foreach($message as $key => $item)
                                @if($message_sender_id == $item->sender->id)
                                <div class="outgoing_msg" id="message_item_{{$item->id}}">
                                    <div class="sent_msg_img">
                                        @if($item->sender->avatar)
                                        <img src="{{$item->sender->avatar}}?{{time()}}"
                                            style="max-width:50px; height:50px">
                                        @else
                                        <img src="{{ asset('material') }}/img/default.png?{{time()}}" alt="..."
                                            style="max-width:50px; height:50px">
                                        @endif
                                    </div>
                                    <div class="sent_msg">
                                        <button style="cursor:pointer" onclick="deleteMessage({{$item->id}})"
                                            class="delete_msg_btn delete_sent_msg">
                                            <i class="fa fa-close" aria-hidden="true"></i>
                                        </button>
                                        <p>
                                            <span>{{$item->sender->name}}</span><br>
                                            {{$item->message}}
                                        </p>
                                        <span class="time_date">{{$item->created_at}}</span>
                                    </div>
                                </div>
                                @else
                                <div class="incoming_msg" id="message_item_{{$item->id}}">
                                    <div class="incoming_msg_img">
                                        @if($item->sender->avatar)
                                        <img src="{{$item->sender->avatar}}?{{time()}}"
                                            style="max-width:50px; height:50px">
                                        @else
                                        <img src="{{ asset('material') }}/img/default.png?{{time()}}" alt="..."
                                            style="max-width:50px; height:50px">
                                        @endif
                                    </div>
                                    <div class="received_msg">
                                        <div class="received_withd_msg">
                                            <button style="cursor:pointer" onclick="deleteMessage({{$item->id}})"
                                                class="delete_msg_btn">
                                                <i class="fa fa-close" aria-hidden="true"></i>
                                            </button>
                                            <p>
                                                <span>{{$item->sender->name}}</span><br>
                                                {{$item->message}}
                                            </p>
                                            <span class="time_date" style="float:right">{{$item->created_at}}</span>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
function getMessage(id) {
    var url = "chat/messages";
    $.ajax({
        url: url,
        data: {
            id: id
        },
        method: 'post',
        success: function(result) {
            var message = result.message;
            var message_sender_id = result.message_sender_id;

            var html = '';
            message.forEach((item, index) => {
                let date_ob = new Date(item.created_at);
                let date = ("0" + date_ob.getDate()).slice(-2);
                let month = ("0" + (date_ob.getMonth() + 1)).slice(-2);
                let year = date_ob.getFullYear();
                let hours = date_ob.getHours();
                let minutes = date_ob.getMinutes();
                let seconds = date_ob.getSeconds();

                item.created_at = year + "-" + month + "-" + date + " " + hours + ":" + minutes +
                    ":" + seconds;
                if (message_sender_id == item.sender.id) {
                    html += `<div class="outgoing_msg" id="message_item_${item.id}">
                                <div class="sent_msg_img">
                                    ${item.sender.avatar ? `<img src="${item.sender.avatar}?${new Date()}" style="max-width:50px; height:50px">` : `<img src="{{ asset('material') }}/img/default.png?${new Date()}" alt="..." style="max-width:50px; height:50px">`}
                                </div>
                                <div class="sent_msg">
                                    <button style="cursor:pointer" onclick="deleteMessage(${item.id})" class="delete_msg_btn delete_sent_msg">
                                        <i class="fa fa-close" aria-hidden="true"></i>
                                    </button>
                                    <p>
                                        <span>${item.sender.name}</span><br>
                                        ${item.message}
                                    </p>
                                    <span class="time_date">${item.created_at}</span>
                                </div>
                            </div>`;
                } else {
                    html += `<div class="incoming_msg" id="message_item_${item.id}">
                                <div class="incoming_msg_img">
                                    ${item.sender.avatar ? `<img src="${item.sender.avatar}?${new Date()}" style="max-width:50px; height:50px">` : `<img src="{{ asset('material') }}/img/default.png?${new Date()}" alt="..." style="max-width:50px; height:50px">`}
                                </div>
                                <div class="received_msg">
                                    <div class="received_withd_msg">
                                        <button style="cursor:pointer" onclick="deleteMessage(${item.id})" class="delete_msg_btn">
                                            <i class="fa fa-close" aria-hidden="true"></i>
                                        </button>
                                        <p>
                                            <span>${item.sender.name}</span><br>
                                            ${item.message}
                                        </p>
                                        <span class="time_date" style="float:right">${item.created_at}</span>
                                    </div>
                                </div>
                            </div>`;
                }
            });

            $('#message_container').empty();
            $('#message_container').append(html);
        },
        error: function(xhr, status, error) {
            location.reload();
        }
    });
}

function deleteMessage(id) {
    var question = confirm("Are you sure you want to delete this message?");
    if (!question)
        return;

    var url = "chat/messages/delete";
    $.ajax({
        url: url,
        data: {
            id: id
        },
        method: 'post',
        success: function(result) {
            showToast('success', "Message successfullly removed.");
            $(`#message_item_${id}`).remove();
        },
        error: function(xhr, status, error) {
            location.reload();
        }
    });
}
</script>
@endpush