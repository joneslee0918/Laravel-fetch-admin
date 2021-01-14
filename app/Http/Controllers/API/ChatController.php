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
        $ads->boost;
        $ads['is_boost'] = false;
        if ( count( $ads['boost'] ) > 0 ) {
            $latest_boost = $ads['boost'][count( $ads['boost'] ) - 1];
            $date_boost = new DateTime( $latest_boost['expired_at'] );
            $date_now = new DateTime();
            if ( $date_boost > $date_now ) {
                $ads['is_boost'] = true;
            }
        }
        $data['ads'] = $ads;

        $own_block = Room::where( ['id_user_sell' => Auth::user()->id, 'id_user_buy' => $ads['user']->id, 's_block_b' => 1] )->count();
        $own_block += Room::where( ['id_user_buy' => Auth::user()->id, 'id_user_sell' => $ads['user']->id, 'b_block_s' => 1] )->count();
        if ( $own_block > 0 ) {
            $message = 'This user blocked by you. You should unblock this user to contact.';
        }
        $own_block = Room::where( ['id_user_sell' => Auth::user()->id, 'id_user_buy' => $ads['user']->id, 'b_block_s' => 1] )->count();
        $own_block += Room::where( ['id_user_buy' => Auth::user()->id, 'id_user_sell' => $ads['user']->id, 's_block_b' => 1] )->count();
        if ( $own_block > 0 ) {
            $message = "This user blocked you on contact. You can't contact with user anymore.";
        }

        if ( $message != '' ) {
            $success = false;
            return $response = array( 'success' => $success, 'data' => '', 'message' => $message );
        }

        if ( $request->room_id > 0 ) {
            $room = Room::where( 'id', $request->room_id )->first();
            $room->buyer;
            $room->seller;
            $room->message;

            $room['buyer']->meta;
            $room['seller']->meta;

            foreach ( $room['message'] as $key => $value ) {
                $value->sender;
            }

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

                $room['buyer']->meta;
                $room['seller']->meta;

                foreach ( $room['message'] as $key => $value ) {
                    $value->sender;
                }

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

                $room['buyer']->meta;
                $room['seller']->meta;

                foreach ( $room['message'] as $key => $value ) {
                    $value->sender;
                }

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

        if ( $request->file( 'chat_image' ) ) {
            $targetDir = base_path( 'uploads' );
            if ( !is_dir( $targetDir ) ) {
                mkDir( $targetDir, 0777, true );
            }
            $targetDir .= '/chat';
            if ( !is_dir( $targetDir ) ) {
                mkDir( $targetDir, 0777, true );
            }

            $file = $request->file( 'chat_image' );
            $sourceFile = $newMessage->id.time().'.'.$file->extension();
            $file->move( $targetDir, $sourceFile );
            $dest_path = '/uploads/chat/'.$sourceFile;

            Chat::where( 'id', $newMessage->id )->update( ['attach_file' => $dest_path] );

            $newMessage = Chat::where( 'id', $newMessage->id )->first();
            $newMessage->room;
            $newMessage->sender;
        }

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