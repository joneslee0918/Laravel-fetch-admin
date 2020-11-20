<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Storage;
use App\Models\User;
use App\Models\UserMeta;
use App\Models\Ads;
use App\Models\Category;
use App\Models\Breed;
use DB;

class AdsController extends Controller {
    public function adDetail( Request $request ) {

        $data = array();
        $success = false;
        $message = '';

        $ads = Ads::where( 'id', $request->ad_id )->first();
        $ads->user;
        $ads->category;
        $ads->breed;
        $ads->meta;

        $exsit_fav = UserMeta::where( ['id_user' => Auth::user()->id, 'meta_key' => '_ad_favourite', 'meta_value' => $request->ad_id] )->count();
        $is_fav = $exsit_fav == 0 ? false : true;

        $ads['is_fav'] = $is_fav;

        $data['ads'] = $ads;
        $success = true;

        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }

    public function adFavourite( Request $request ) {
        $data = array();
        $message = '';
        $success = false;

        if ( $request->is_fav == true ) {
            $user_meta = new UserMeta;
            $user_meta->id_user = Auth::user()->id;
            $user_meta->meta_key = '_ad_favourite';
            $user_meta->meta_value = $request->ad_id;
            $user_meta->save();

            $message = 'Ads successfully added on your favourite.';
        } else {
            UserMeta::where( ['meta_key' => '_ad_favourite', 'meta_value' => $request->ad_id] )->delete();
            $message = 'Ads successfully removed on your favourite.';
        }
        $success = true;

        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }
}
