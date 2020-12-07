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
use App\Models\Order;
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

    public function sell() {
        $data = array();
        $message = '';
        $success = true;

        $category = Category::get();
        $breed = Breed::get();

        $data['category'] = $category;
        $data['breed'] = $breed;

        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }

    public function create( Request $request ) {
        $data = array();
        $message = '';
        $success = true;

        $user_id = Auth::user()->id;
        $category_id = Category::where( 'name', $request->category )->value( 'id' );
        $breed_id = Breed::where( 'name', $request->breed )->value( 'id' );

        $newAds = new Ads;
        $newAds->id_user = $user_id;
        $newAds->id_category = $category_id;
        $newAds->id_breed = $breed_id;
        $newAds->gender = $request->gender;
        $newAds->age = $request->age;
        $newAds->price = $request->price;
        $newAds->lat = $request->lat;
        $newAds->long = $request->long;
        $newAds->description = $request->description;
        $newAds->status = 1;
        $newAds->save();

        $newAdsId = $newAds->id;

        $targetDir = public_path( 'uploads/ads/' );
        $targetDir .= $user_id;
        if ( !is_dir( $targetDir ) ) {
            mkDir( $targetDir );
        }
        $targetDir .= '/'.$newAdsId;
        mkDir( $targetDir );

        $image_key = $request->image_key;
        $uploadedImages = [];
        foreach ( $request->file( $image_key ) as $key => $file ) {
            $sourceFile = 'ad_image_'.( $key+1 ).'.'.$file->extension();
            $dest_path = '/uploads/ads/'.$user_id.'/'.$newAdsId.'/'.$sourceFile;
            $file->move( $targetDir, $sourceFile );
            $uploadedImages[] = $dest_path;
        }

        foreach ( $uploadedImages as $key => $value ) {
            $ads_meta = new AdsMeta;
            $ads_meta->id_ads = $newAdsId;
            $ads_meta->meta_key = '_ad_image';
            $ads_meta->meta_value = $value;
            $ads_meta->save();
        }

        $message = 'Your ads successfully created';

        return $response = array( 'success' => $success, 'data' => '', 'message' => $message );
    }

    public function order( Request $request ) {
        $data = array();
        $message  = '';
        $success = true;

        $request['id_order_user'] = Auth::user()->id;
        Order::create( $request->all() );

        $exist = UserMeta::where( ['meta_key' => '_ad_favourite', 'meta_value' => $request->id_ads] )->count();
        if ( $exist == 0 ) {
            $user_meta = new UserMeta;
            $user_meta->id_user = $request->id_order_user;
            $user_meta->meta_key = '_ad_favourite';
            $user_meta->meta_value = $request->id_ads;
            $user_meta->save();
        }

        $message = 'Ads order successfully requested.';
        return $response = array( 'success' => $success, 'data' => '', 'message' => $message );
    }
}
