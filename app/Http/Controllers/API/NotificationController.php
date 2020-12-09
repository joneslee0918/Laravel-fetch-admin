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
use DB;

class NotificationController extends Controller {

    public function send( $user_id, $type, $title, $body, $image, $data ) {

        $token = User::where( 'id', $user_id )->value( 'device_token' );
        $notification_data = array
        (
            'type' => $type,
            'title' => $title,
            'body' => $body,
            'image' => $image,
            'data' => $data
        );

        $cloud_message_data = array
        (
            'to' => $token,
            'data' => $notification_data,
            'notification' => $notification_data
        );

        $api_firebase_id = AppSetting::where( 'meta_key', 'firebase_api_key' )->value( 'meta_value' );
        define( 'API_ACCESS_KEY', $api_firebase_id );

        $data_string = json_encode( $cloud_message_data );

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

        $notification = Notification::where( 'id_rcv_user', Auth::user()->id )->where( 'deleted_at', null )->orderby( 'read_status', 'ASC' )->orderby( 'created_at', 'DESC' )->get();
        if ( count( $notification ) == 0 ) {
            $message = 'Notification not found.';
            $data['notification'] = [];
        } else {
            $data['notification'] = $notification;
        }
        return $response = array( 'success' =>$success, 'message' => $message, 'data' => $data );
    }

    public function read( Request $request ) {
        $data = array();
        $success = true;
        $message = '';

        $notification = Notification::where( 'id', $request->id )->first();
        if ( $notification->type == 0 ) {
            ///chat message
            Notification::where( ['id_type' => $notification->id_type, 'id_snd_user' => $notification->id_snd_user, 'id_rcv_user' => $notification->id_rcv_user, 'type' => 0] )->update( ['read_status' => 1] );
        } else {
            Notification::where( 'id', $request->id )->update( ['read_status' => 1] );
        }
        return $response = array( 'success' =>$success, 'message' => $message, 'data' => $data );
    }
}
