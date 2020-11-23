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
    public function test() {
        return $this->send( 'test-title', 'test-content', null );
    }

    public function send( $title, $body, $user_ids ) {
        if ( $users ) {
            $tokens = array_merge(
                User::whereIn( 'id', $user_ids )->pluck( 'device_token' )->toArray(),
                User::whereIn( 'id', $user_ids )->pluck( 'iphone_device_token' )->toArray()
            );
        } else {
            $tokens = array_merge(
                User::pluck( 'device_token' )->toArray(),
                User::pluck( 'iphone_device_token' )->toArray()
            );
        }

        $notification = array
        (
            'title' => $title,
            'body' => $body,
        );

        $message_data = array
        (
            'to' => $tokens,
            'title' => $title,
            'body' => $body,
            'notification' => $notification
        );

        $$api_firebase_id = AppSetting::where( 'meta_key', 'firebase_api_key' )->value( 'meta_value' );

        define( 'API_ACCESS_KEY', $api_firebase_id );

        $data_string = json_encode( $message_data );

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

    }
}
