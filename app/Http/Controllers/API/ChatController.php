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
use App\Models\Breed;
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
        $ads->meta;
        $data['ads'] = $ads;

        $chat = Chat::where( 'id_ads', $request->ad_id )->where( 'id_user_snd', $user_id )->orWhere( 'id_user_rcv', $user_id )->get();
        foreach ( $chat as $key => $item ) {
            $item->sender;
            $item->receiver;
        }
        $data['chat'] = $chat;

        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }

    public function postMessage( Request $request ) {
        $data = array();
        $success = false;
        $message = '';

        $newMessage = Chat::create( $request->all() );
        $newMessage->sender;
        $newMessage->receiver;

        $rcv_user_id = $newMessage->id_user_rcv;
        $type = 'chat_message';
        $title = 'New Message from '.Auth::user()->name;
        $body = $newMessage->message;

        $notify_result = $this->notification->send( $rcv_user_id, $type, $title, $body, $newMessage );
        $notify_result = str_replace( '\n', '', $notify_result );
        $notify_result = rtrim( $notify_result, ',' );
        $notify_result = '[' . trim( $notify_result ) . ']';
        $json_result = json_decode( $notify_result, true );
        if ( $json_result[0]['success'] == 1 ) {
            $success = true;
        } else {
            $message = "Your message can't send.";
        }

        $data['newMessage'] = $newMessage;

        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }
}
