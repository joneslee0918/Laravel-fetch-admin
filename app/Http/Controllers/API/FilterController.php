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

        $max_price = Ads::max( 'price' );

        $data['max_price'] = $max_price;
        $data['user_data'] = $user;
        $data['category_data'] = $category;
        $data['breed_data'] = $breed;

        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }

    public function getData( Request $request ) {
        $data = array();
        $success = true;
        $message = '';

        $ads = new Ads;
        if ( $request->category['id'] > 0 ) {
            $ads = $ads->where( 'id_category', $request->category['id'] );
        }
        if ( $request->breed['id'] > 0 ) {
            $ads = $ads->where( 'id_breed', $request->breed['id'] );
        }
        if ( $request->gender['id'] > -1 ) {
            $ads = $ads->where( 'gender', $request->gender['id'] );
        }
        if ( $request->price['min'] != '' && $request->price['min'] != null ) {
            $ads = $ads->where( 'price', '>=', $request->price['min'] );
        }
        if ( $request->price['max'] != '' && $request->price['max'] != null ) {
            $ads = $ads->where( 'price', '<=', $request->price['max'] );
        }
        if ( $request->age > '0' ) {
            $ads = $ads->where( 'age', $request->age );
        }
        // if ( $request->searchText != '' && $request->searchText != null ) {
        //     $ads = $ads->where( 'age', 'like', '' $request->age );
        // }
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
