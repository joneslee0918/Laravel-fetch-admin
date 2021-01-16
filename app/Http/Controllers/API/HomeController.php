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
use App\Models\Breed;
use App\Models\Notification;
use App\Models\Subscription;
use DB;
use DateTime;

class HomeController extends Controller {
    public function home() {

        $data = array();
        $success = true;
        $message = '';

        $ads = Ads::where( 'status', 1 )->orderby( 'updated_at', 'DESC' )->get();
        if ( count( $ads ) == 0 ) {
            $message = 'Ads Not Found.';
            $data['ads'] = [];
        } else {
            foreach ( $ads as $key => $item ) {
                $user = $item->user;
                $item->category;
                $item->breed;
                $item->meta;
                $item->boost;
                $item['is_boost'] = false;
                if ( count( $item['boost'] ) > 0 ) {
                    $latest_boost = $item['boost'][count( $item['boost'] ) - 1];
                    $date_boost = new DateTime( $latest_boost['expired_at'] );
                    $date_now = new DateTime();
                    if ( $date_boost > $date_now ) {
                        $item['is_boost'] = true;
                    }
                }
                $user->meta;
                $item['user'] = $user;

                $exsit_fav = UserMeta::where( ['id_user' => Auth::user()->id, 'meta_key' => '_ad_favourite', 'meta_value' => $item['id']] )->count();
                $is_fav = $exsit_fav == 0 ? false : true;
                $item['is_fav'] = $is_fav;
            }

            $data['ads'] = $ads;
        }

        $is_valid_subscription = false;
        $subscription = Subscription::where( 'id_user', Auth::user()->id )>get();
        if ( count( $subscription ) > 0 ) {
            $latest_sub = $subscription[count( $subscription ) - 1];
            $date_sub = new DateTime( $latest_sub['expired_at'] );
            $date_now = new DateTime();
            if ( $date_sub > $date_now ) {
                $is_valid_subscription = true;
            }
        }
        $data['is_valid_subscription'] = $is_valid_subscription;

        $category = Category::orderby( 'order' )->get();
        $unread_message = Notification::where( ['id_rcv_user' => Auth::user()->id, 'read_status' => 0, 'deleted_at' => null] )->count();

        $data['category'] = $category;
        $data['ads'] = $ads;
        $data['is_show_apple_button'] = 1;
        $data['unread_message'] = $unread_message;

        $success = true;

        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }

    public function filter( Request $request ) {
        $data = array();
        $success = true;
        $message = '';

        $searchText = $request->searchText;
        $ads_ids = [];
        $ads = [];
        if ( $searchText != '' ) {
            $searchText = "'%".$request->searchText."%'";
            $strQuery = 'SELECT a.id AS id FROM ads AS a LEFT JOIN category AS b ON a.`id_category` = b.`id` LEFT JOIN breed AS c ON a.`id_breed` = c.`id` WHERE a.`status` = 1 AND c.`name` LIKE '.$searchText.' OR b.`name` LIKE '.$searchText;
            $result = DB::select( $strQuery );
            foreach ( $result as $key => $value ) {
                $ads_ids[] = $value->id;
            }
        }
        if ( $request->id_category == -1 ) {
            if ( $searchText == '' ) {
                $ads = Ads::where( 'status', 1 )->orderby( 'updated_at', 'DESC' )->get();
            } else if ( count( $ads_ids ) > 0 ) {
                $ads = Ads::where( 'status', 1 )->whereIn( 'id', $ads_ids )->orderby( 'updated_at', 'DESC' )->get();
            }
        } else {
            if ( $searchText == '' ) {
                $ads = Ads::where( 'status', 1 )->where( 'id_category', $request->id_category )->orderby( 'updated_at', 'DESC' )->get();
            } else if ( count( $ads_ids ) > 0 ) {
                $ads = Ads::where( 'status', 1 )->where( 'id_category', $request->id_category )->whereIn( 'id', $ads_ids )->orderby( 'updated_at', 'DESC' )->get();
            }
        }

        if ( count( $ads ) == 0 ) {
            if ( $searchText != '' ) {
                $message = 'Ads Not Found. Please input correct category name or breed name to find pets.';
            } else {
                $message = 'Ads Not Found.';
            }

            $data['ads'] = [];
        } else {
            foreach ( $ads as $key => $item ) {
                $user = $item->user;
                $item->category;
                $item->breed;
                $item->meta;
                $item->boost;
                $item['is_boost'] = false;
                if ( count( $item['boost'] ) > 0 ) {
                    $latest_boost = $item['boost'][count( $item['boost'] ) - 1];
                    $date_boost = new DateTime( $latest_boost['expired_at'] );
                    $date_now = new DateTime();
                    if ( $date_boost > $date_now ) {
                        $item['is_boost'] = true;
                    }
                }
                $user->meta;
                $item['user'] = $user;

                $exsit_fav = UserMeta::where( ['id_user' => Auth::user()->id, 'meta_key' => '_ad_favourite', 'meta_value' => $item['id']] )->count();
                $is_fav = $exsit_fav == 0 ? false : true;
                $item['is_fav'] = $is_fav;
            }

            $data['ads'] = $ads;
        }

        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }

    public function dashboard() {
        $data = array();
        $success = true;
        $message = '';

        $category = Category::orderby( 'order' )->get();
        $unread_message = Notification::where( ['id_rcv_user' => Auth::user()->id, 'read_status' => 0, 'deleted_at' => null] )->count();
        $user = Auth::user();
        $user->review;

        $is_valid_subscription = false;
        $subscription = Subscription::where( 'id_user', Auth::user()->id )->get();
        if ( count( $subscription ) > 0 ) {
            $latest_sub = $subscription[count( $subscription ) - 1];
            $date_sub = new DateTime( $latest_sub['expired_at'] );
            $date_now = new DateTime();
            if ( $date_sub > $date_now ) {
                $is_valid_subscription = true;
            }
        }
        $data['is_valid_subscription'] = $is_valid_subscription;

        $data['review'] = $user['review'];
        $data['category'] = $category;
        $data['is_show_apple_button'] = 1;
        $data['unread_message'] = $unread_message;

        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }
}
