<?php

namespace App\Http\Controllers\WEB;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Chat;
use App\Models\Ads;

class ChatController extends Controller {
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function index() {
        //
        $data = [];

        $user = User::get();
        foreach ( $user as $key => $value ) {
            $user_id = $value->id;

            $inbox = Chat::where( 'id_user_snd', $user_id )->orWhere( 'id_user_rcv', $user_id )->groupby( 'id_ads' )->get();
            foreach ( $inbox as $inbox_key => $inbox_item ) {
                $exist = false;
                foreach ( $data as $key => $data_value ) {
                    if ( $data_value->id == $inbox_item->id ) {
                        $exist = true;
                        break;
                    }
                }
                if ( $exist == false )
                $data[] = $inbox_item;
            }
        }

        $message = [];
        $message_sender_id = -1;
        if ( count( $data ) > 0 ) {
            $id = $data[0]->id;
            $chat = Chat::where( 'id', $id )->first();
            $id_ads = $chat->id_ads;
            $id_sender = $chat->id_user_snd;
            $message_sender_id = $id_sender;
            $id_receiver = $chat->id_user_rcv;

            $id_sends = Chat::where( [ 'id_ads' => $id_ads, 'id_user_snd' => $id_sender, 'id_user_rcv' => $id_receiver] )->pluck( 'id' )->toArray();
            $id_receives = Chat::where( [ 'id_ads' => $id_ads, 'id_user_snd' => $id_receiver, 'id_user_rcv' => $id_sender] )->pluck( 'id' )->toArray();
            $message_ids = array_merge( $id_sends, $id_receives );
            $message = Chat::whereIn( 'id', $message_ids )->orderby( 'created_at', 'DESC' )->get();
        }
        return view( 'chat.index', ['chat' => $data, 'message' => $message, 'message_sender_id' => $message_sender_id] );
    }

    public function getMessage( Request $request ) {
        $id = $request->id;
        $chat = Chat::where( 'id', $id )->first();
        $id_ads = $chat->id_ads;
        $id_sender = $chat->id_user_snd;
        $id_receiver = $chat->id_user_rcv;

        $message = [];

        $id_sends = Chat::where( [ 'id_ads' => $id_ads, 'id_user_snd' => $id_sender, 'id_user_rcv' => $id_receiver] )->pluck( 'id' )->toArray();
        $id_receives = Chat::where( [ 'id_ads' => $id_ads, 'id_user_snd' => $id_receiver, 'id_user_rcv' => $id_sender] )->pluck( 'id' )->toArray();
        $message_ids = array_merge( $id_sends, $id_receives );
        $message = Chat::whereIn( 'id', $message_ids )->orderby( 'created_at', 'DESC' )->get();
        foreach ( $message as $key => $value ) {
            $value->sender;
            $value->receiver;
        }

        $data = array();
        $data['message_sender_id'] = $id_sender;
        $data['message'] = $message;
        return $data;
    }

    public function deleteMessage( Request $request ) {
        Chat::where( 'id', $request->id )->delete();
        return;
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function create() {
        //
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */

    public function store( Request $request ) {
        //
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function show( $id ) {
        //
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function edit( $id ) {
        //
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function update( Request $request, $id ) {
        //
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function destroy( $id ) {
        //
    }
}
