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

class InboxController extends Controller {
    private $notification;

    public function __construct() {
        $this->notification = new NotificationController;
    }

    public function inbox() {

        $data = array();
        $success = true;
        $message = '';

        $user_id = Auth::user()->id;

        $inbox = [];

        $inbox = Chat::where( 'id_user_snd', $user_id )->orWhere( 'id_user_rcv', $user_id )->groupby( 'id_ads' )->get();

        foreach ( $inbox as $inbox_key => $inbox_item ) {
            $chat_messages = Chat::where( 'id_ads', $inbox_item->id_ads )->get();

            foreach ( $chat_messages as $key => $item ) {
                $item->sender;
                $item->receiver;
                $item->ads;
                $item['ads']->meta;
            }
            $inbox[$inbox_key]['message'] = $chat_messages;
        }

        if ( count( $inbox ) == 0 ) {
            $message = 'You have no chat yet';
        }

        $data['inbox'] = $inbox;

        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }
}
