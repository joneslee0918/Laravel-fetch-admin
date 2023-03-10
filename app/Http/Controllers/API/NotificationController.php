<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Storage;
use App\Models\User;
use App\Models\Chat;
use App\Models\AppSetting;
use App\Models\Notification;
use App\Models\Room;
use DB;

class NotificationController extends Controller {

    public function send( $user_id, $type, $title, $body, $image, $data ) {

        $device_token = User::where( 'id', $user_id )->value( 'device_token' );
        $iphone_device_token = User::where( 'id', $user_id )->value( 'iphone_device_token' );

        $data['notification_type'] = $type;
        $notification_data = array
        (
            'title' => $title,
            'body' => $body,
            'data' => $data
        );

        $cloud_message_data_android = array
        (
            'to' => $device_token,
            'data' => $notification_data,
            'notification' => $notification_data
        );

        $cloud_message_data_iphone = array
        (
            'to' => $iphone_device_token,
            'data' => $notification_data,
            'notification' => $notification_data
        );

        $api_firebase_id = AppSetting::where( 'meta_key', 'firebase_api_key' )->value( 'meta_value' );
        define( 'API_ACCESS_KEY', $api_firebase_id );

        $data_string_android = json_encode( $cloud_message_data_android );
        $data_string_iphone = json_encode( $cloud_message_data_iphone );

        $this->sendNotification( $data_string_android );
        $this->sendNotification( $data_string_iphone );
    }

    public function sendNotification( $data_string ) {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $headers = array
        (
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_POST, true );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $data_string );
        $result = curl_exec( $ch );
        curl_close( $ch );

        return $result;
    }

    public function notification() {
        $data = array();
        $success = true;
        $message = '';
        try {
            $notification = Notification::where( ['id_rcv_user' => Auth::user()->id, 'deleted_at' => null] )->orderby( 'read_status', 'ASC' )->orderby( 'created_at', 'DESC' )->get();
            if ( count( $notification ) == 0 ) {
                $message = 'There is no new message.';
                $data['notification'] = [];
            } else {
                foreach ( $notification as $key => $value ) {
                    if ( $value->type == 0 ) {
                        $room = Room::where( 'id', $value->id_type )->first();
                        $room['message'] = Chat::where( 'id_room', $room['id'] )->get();
                        $value['room'] = $room;
                    }
                }
                $data['notification'] = $notification;
            }
        } catch ( \Throwable $th ) {
            $success = false;
            $message = '';
            $data = array();
        }
        return $response = array( 'success' =>$success, 'message' => $message, 'data' => $data );
    }

    public function read( Request $request ) {
        $data = array();
        $success = true;
        $message = '';

        try {
            $notification = Notification::where( 'id', $request->id )->first();
            if ( $notification->type == 0 ) {
                ///chat message
                Notification::where( ['id_type' => $notification->id_type, 'id_snd_user' => $notification->id_snd_user, 'id_rcv_user' => $notification->id_rcv_user, 'type' => 0] )->update( ['read_status' => 1] );
                Chat::where( 'id_room', $notification->id_type )->where( 'id_user_snd', '!=', Auth::user()->id )->update( ['read_status' => 1] );
            } else {
                Notification::where( 'id', $request->id )->update( ['read_status' => 1] );
            }
        } catch ( \Throwable $th ) {
            $data = array();
            $success = false;
            $message = '';
        }

        return $response = array( 'success' =>$success, 'message' => $message, 'data' => $data );
    }

    public function delete( Request $request ) {
        $data = array();
        $success = true;
        $message = '';

        try {
            Notification::where( 'id', $request->id )->update( ['deleted_at' => now()] );
        } catch ( \Throwable $th ) {
            $data = array();
            $success = false;
            $message = '';
        }

        return $response = array( 'success' =>$success, 'message' => $message, 'data' => $data );
    }
}
