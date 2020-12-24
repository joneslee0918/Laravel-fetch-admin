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
use DB;

class FilterController extends Controller {
    //

    public function index() {
        $data = array();
        $success = true;
        $message = '';

        $user_ids = Ads::where( 'id_user', '!=', Auth::user()->id )->groupby( 'id_user' )->pluck( 'id_user' )->toArray();
        $user = User::whereIn( 'id', $user_ids )->get();
        $category = Category::get();
        $breed = Breed::get();

        $data['user_data'] = $user;
        $data['category_data'] = $category;
        $data['breed_data'] = $breed;

        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }

    public function getData( Request $request ) {
        $data = array();
        $success = true;
        $message = '';

        $ads = Ads::where( 'gender', $request->gender['id'] );
        if ( $request->user['id'] > 0 ) {
            $ads = $ads->where( 'id_user', $request->user['id'] );
        }
        if ( $request->category['id'] > 0 ) {
            $ads = $ads->where( 'id_category', $request->category['id'] );
        }
        if ( $request->breed['id'] > 0 ) {
            $ads = $ads->where( 'id_breed', $request->breed['id'] );
        }
        if ( $request->price['min'] != '' && $request->price['min'] != null ) {
            $ads = $ads->where( 'price', '>=', $request->price['min'] );
        }
        if ( $request->price['max'] != '' && $request->price['max'] != null ) {
            $ads = $ads->where( 'price', '<=', $request->price['max'] );
        }
        if ( $request->age['min']['num'] != '' && $request->age['min']['num'] != null ) {
            $ads = $ads->where( 'age', '>=', $request->age['min']['num'] )->where( 'unit', $request->age['min']['unit'] );
        }
        if ( $request->age['max']['num'] != '' && $request->age['max']['num'] != null ) {
            $ads = $ads->where( 'age', '<=', $request->age['max']['num'] )->where( 'unit', $request->age['max']['unit'] );
        }
        if ( $request->sortBy['type'] == 'Post Date' ) {
            $ads = $ads->orderby( 'updated_at', $request->sortBy['direction'] );
        }
        if ( $request->sortBy['type'] == 'Price' ) {
            $ads = $ads->orderby( 'price', $request->sortBy['direction'] );
        }
        $ads = $ads->get();

        foreach ( $ads as $key => $item ) {
            $user = $item->user;
            $item->category;
            $item->breed;
            $item->meta;
            $user->meta;
            $item['user'] = $user;

            $exsit_fav = UserMeta::where( ['id_user' => Auth::user()->id, 'meta_key' => '_ad_favourite', 'meta_value' => $item['id']] )->count();
            $is_fav = $exsit_fav == 0 ? false : true;
            $item['is_fav'] = $is_fav;
        }

        $data['pets'] = $ads;
        if ( count( $ads ) == 0 ) {
            $message = 'Ads Not Found.';
        }

        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }
}
