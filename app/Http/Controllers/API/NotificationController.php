<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Storage;
use App\Models\User;
use App\Models\Chat;
use App\Models\AppSetting;
use DB;

class NotificationController extends Controller {

    public function send( $user_id, $type, $title, $body, $data ) {

        $token = User::where( 'id', $user_id )->value( 'device_token' );
        $notification_data = array
        (
            'type' => $type,
            'title' => $title,
            'body' => $body,
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
}
