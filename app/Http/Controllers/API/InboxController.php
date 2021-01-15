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
use DateTime;

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
        $room_sell_ids = Room::where( ['id_user_sell' => $user_id, 's_block_b' => 0] )->pluck( 'id' )->toArray();
        ///get room as buyer
        $room_buy_ids = Room::where( ['id_user_buy' => $user_id, 'b_block_s' => 0] )->pluck( 'id' )->toArray();

        $room_ids = array_merge( $room_sell_ids, $room_buy_ids );
        $room = Room::whereIn( 'id', $room_ids )->get();
        foreach ( $room as $key => $value ) {
            $value->ads;
            $value->buyer;
            $value->seller;
            $value->message;
            $value['ads']->meta;
            $value['ads']->boost;
            $value['ads']['is_boost'] = false;
            if ( count( $value['ads']['boost'] ) > 0 ) {
                $latest_boost = $value['ads']['boost'][count( $value['ads']['boost'] ) - 1];
                $date_boost = new DateTime( $latest_boost['expired_at'] );
                $date_now = new DateTime();
                if ( $date_boost > $date_now ) {
                    $value['ads']['is_boost'] = true;
                }
            }
        }

        if ( count( $room ) == 0 ) {
            $message = 'You have no chat yet';
        }

        $data['inbox'] = $room;

        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }

    public function blockUser( Request $request ) {

        $data = array();
        $success = true;
        $message = '';

        $room = Room::where( 'id', $request->room_id )->first();
        $other_id = $room->id_user_buy;
        if ( Auth::user()->id == $room->id_user_buy ) {
            $other_id = $room->id_user_sell;
        }
        Room::where( ['id_user_sell' => Auth::user()->id, 'id_user_buy' => $other_id] )->update( ['s_block_b' => 1] );
        Room::where( ['id_user_buy' => Auth::user()->id, 'id_user_sell' => $other_id] )->update( ['b_block_s' => 1] );

        $message = 'This user blocked on your chat.';

        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }

    public function getBlockList() {
        $data = array();
        $success = true;
        $message = '';

        $buyer_ids = Room::where( ['id_user_sell' => Auth::user()->id, 's_block_b' => 1] )->groupby( 'id_user_buy' )->pluck( 'id_user_buy' )->toArray();
        $seller_ids = Room::where( ['id_user_buy' => Auth::user()->id, 'b_block_s' => 1] )->groupby( 'id_user_sell' )->pluck( 'id_user_sell' )->toArray();
        $user_ids = array_merge( $buyer_ids, $seller_ids );
        $user = User::whereIn( 'id', $user_ids )->get();

        if ( count( $user ) == 0 ) {
            $message = 'There is no unblock user on your chat.';
        }
        $data['user'] = $user;

        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }

    public function unblockUser( Request $request ) {
        $data = array();
        $success = true;
        $message = '';

        Room::where( ['id_user_sell' => Auth::user()->id, 'id_user_buy' => $request->user_id] )->update( ['s_block_b' => 0] );
        Room::where( ['id_user_buy' => Auth::user()->id, 'id_user_sell' => $request->user_id] )->update( ['b_block_s' => 0] );

        $message = 'This user unblocked on your chat.';

        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }
}
