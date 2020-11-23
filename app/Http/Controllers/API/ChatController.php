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

        $ads = Ads::where('id', $request->ad_id)->first();
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
        $success = true;
        $message = '';

        $user_id = Auth::user()->id;
        $receive_user_id = $request->receive_user_id;

        $newChat = new Chat;
        $newChat->id_user_snd = $user_id;
        $newChat->id_user_rcv = $receive_user_id;
        $newChat->message = $request->message;
        $newChat->attach_file = $request->attach_file;
        $newChat->message_type = $request->message_type;
        $newChat->last_seen_time = $request->last_seen_time;
        $newChat->save();

        $this->notification->send( 'New Message', $message, $receive_user_id );

        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }
}
