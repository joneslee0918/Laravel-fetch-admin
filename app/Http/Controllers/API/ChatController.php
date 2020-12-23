<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Storage;
use App\Models\User;
use App\Models\UserMeta;
use App\Models\Ads;
use App\Models\AdsMeta;
use App\Models\Category;
use App\Models\Chat;
use App\Models\Room;
use App\Models\Breed;
use App\Models\Notification;
use DB;

class ChatController extends Controller {
    private $notification;

    public function __construct() {
        $this->notification = new NotificationController;
    }

    public function chat( Request $request ) {
        $data = array();
        $success = true;
        $message = '';

        $user_id = Auth::user()->id;

        $ads = Ads::where( 'id', $request->ad_id )->first();
        $ads->user;
        $ads->meta;
        $data['ads'] = $ads;

        if ( $request->room_id > 0 ) {
            $room = Room::where( 'id', $request->room_id )->first();
            $room->buyer;
            $room->seller;
            $room->message;

            Chat::where( 'id_room', $room->id )->where( 'id_user_snd', '!=', Auth::user()->id )->update( ['read_status' => 1] );
            Notification::where( ['id_type' => $room->id, 'type' => 0, 'id_rcv_user' => Auth::user()->id] )->update( ['read_status' => 1] );

            $data['room'] = $room;
        } else {
            $exist = Room::where( ['id_ads' => $ads->id, 'id_user_sell' => $ads['user']->id, 'id_user_buy' => Auth::user()->id] )->count();
            if ( $exist > 0 ) {
                $room = Room::where( ['id_ads' => $ads->id, 'id_user_sell' => $ads['user']->id, 'id_user_buy' => Auth::user()->id] )->first();
                $room->buyer;
                $room->seller;
                $room->message;

                Chat::where( 'id_room', $room->id )->where( 'id_user_snd', '!=', Auth::user()->id )->update( ['read_status' => 1] );
                Notification::where( ['id_type' => $room->id, 'type' => 0, 'id_rcv_user' => Auth::user()->id] )->update( ['read_status' => 1] );

                $data['room'] = $room;
            } else {
                $room = new Room;
                $room->id_ads = $ads->id;
                $room->id_user_sell = $ads['user']->id;
                $room->id_user_buy = Auth::user()->id;
                $room->save();

                $room->buyer;
                $room->seller;
                $room->message;

                $data['room'] = $room;
            }
        }

        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }

    public function postMessage( Request $request ) {
        $data = array();
        $success = true;
        $message = '';

        $newMessage = Chat::create( $request->all() );
        $newMessage->room;
        $newMessage->sender;

        $rcv_user_id = Auth::user()->id == $newMessage['room']['id_user_sell'] ? $newMessage['room']['id_user_buy'] : $newMessage['room']['id_user_sell'];
        $type = 'chat_message';
        $title = 'You received a new message.';
        $body = $newMessage->message;

        $notify_result = $this->notification->send( $rcv_user_id, $type, $title, $body, '', $newMessage );

        $newNotification = new Notification;
        $newNotification->id_snd_user = Auth::user()->id;
        $newNotification->id_rcv_user = $rcv_user_id;
        $newNotification->id_type = $newMessage['room']->id;
        $newNotification->title = $title;
        $newNotification->body = $body;
        $newNotification->type = 0;
        $newNotification->save();

        $data['newMessage'] = $newMessage;

        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }

    public function readMessage( Request $request ) {
        $data = array();
        $success = true;
        $message = '';

        Chat::where( 'id_room', $request->id )->where( 'id_user_snd', '!=', Auth::user()->id )->update( ['read_status' => 1] );
        Notification::where( ['id_type' => $request->id, 'type' => 0, 'id_rcv_user' => Auth::user()->id] )->update( ['read_status' => 1] );

        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }
}