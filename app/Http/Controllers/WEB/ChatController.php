<?php

namespace App\Http\Controllers\WEB;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Chat;
use App\Models\Room;
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

        $room = Room::get();
        foreach ( $room as $key => $value ) {
            $value->ads;
            foreach ( $value['ads']['meta'] as $item_key => $item_value ) {
                if ( $item_value->meta_key == '_ad_image' ) {
                    $value['ad_image'] = $item_value->meta_value;
                    break;
                }
            }
        }

        $data['room'] = $room;

        return view( 'chat.index', $data );
    }

    public function getMessage( Request $request ) {
        $room = Room::where( 'id', $request->id )->first();
        $room->seller;
        $room->buyer;
        $room->message;
        foreach ( $room['message'] as $key => $value ) {
            $value->sender;
        }
        return $room;
    }

    public function deleteMessage( Request $request ) {
        $chat = Chat::where( 'id', $request->id )->first();
        if ( $chat->attach_file ) {
            $file_path = substr( $chat->attach_file, 1 );
            unlink( $file_path );
        }
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
