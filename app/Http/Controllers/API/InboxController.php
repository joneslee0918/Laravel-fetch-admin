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

        ///get room as seller
        $room_sell_ids = Room::where( 'id_user_sell', $user_id )->pluck( 'id' )->toArray();
        ///get room as buyer
        $room_buy_ids = Room::where( 'id_user_buy', $user_id )->pluck( 'id' )->toArray();

        $room_ids = array_merge( $room_sell_ids, $room_buy_ids );
        $room = Room::whereIn( 'id', $room_ids )->get();
        foreach ( $room as $key => $value ) {
            $value->ads;
            $value->buyer;
            $value->seller;
            $value->message;
            $value['ads']->meta;
        }

        if ( count( $room ) == 0 ) {
            $message = 'You have no chat yet';
        }

        $data['inbox'] = $room;

        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }
}
