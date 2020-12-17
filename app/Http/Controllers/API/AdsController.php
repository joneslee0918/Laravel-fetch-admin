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
use App\Models\Notification;
use DB;

class AdsController extends Controller {
    private $notification;

    public function __construct() {
        $this->notification = new NotificationController;
    }

    public function adDetail( Request $request ) {

        $data = array();
        $success = false;
        $message = '';

        $ads = Ads::where( 'id', $request->ad_id )->first();
        $user = $ads->user;
        $ads->category;
        $ads->breed;
        $ads->meta;
        $user->meta;
        $ads['user'] = $user;

        if ( $request->view == true && $ads->user->id != Auth::user()->id ) {
            DB::select( 'UPDATE ads SET views = views + 1 WHERE id = '.$ads->id );
        }

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

            DB::select( 'UPDATE ads SET likes = likes + 1 WHERE id = '.$request->ad_id );

            $message = 'Ads successfully added on your favourite.';
        } else {
            UserMeta::where( ['meta_key' => '_ad_favourite', 'meta_value' => $request->ad_id] )->delete();
            DB::select( 'UPDATE ads SET likes = likes - 1 WHERE id = '.$request->ad_id );
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

        $targetDir = public_path( 'uploads' );
        if ( !is_dir( $targetDir ) ) {
            mkDir( $targetDir, 0777, true );
        }
        $targetDir .= '/ads';
        if ( !is_dir( $targetDir ) ) {
            mkDir( $targetDir, 0777, true );
        }
        $targetDir .= '/'.$user_id;
        if ( !is_dir( $targetDir ) ) {
            mkDir( $targetDir, 0777, true );
        }
        $targetDir .= '/'.$newAdsId;
        if ( !is_dir( $targetDir ) ) {
            mkDir( $targetDir, 0777, true );
        }

        $image_key = $request->image_key;
        $uploadedImages = [];
        foreach ( $request->file( $image_key ) as $key => $file ) {
            $sourceFile = 'ad_image_'.( $key+1 ).time().'.'.$file->extension();
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

        $message = 'Your ads successfully posted.';

        return $response = array( 'success' => $success, 'data' => '', 'message' => $message );
    }

    public function edit( Request $request ) {
        $data = array();
        $message = '';
        $success = true;

        $user_id = Auth::user()->id;
        $ad_id = $request->ad_id;
        $category_id = Category::where( 'name', $request->category )->value( 'id' );
        $breed_id = Breed::where( 'name', $request->breed )->value( 'id' );

        Ads::where( 'id', $ad_id )->update( ['id_category' => $category_id, 'id_breed' => $breed_id, 'gender' => $request->gender, 'age' => $request->age, 'price' => $request->price, 'lat' => $request->lat, 'long' => $request->long, 'description' => $request->description] );

        if ( $request->is_edit_image == true ) {
            $ads_images = AdsMeta::where( ['id_ads' => $ad_id, 'meta_key' => '_ad_image'] )->get();
            foreach ( $ads_images as $key => $value ) {
                $file_path = substr( $value->meta_value, 1 );
                unlink( $file_path );
            }

            AdsMeta::where( ['id_ads' => $ad_id, 'meta_key' => '_ad_image'] )->delete();

            $targetDir = public_path( 'uploads' );
            if ( !is_dir( $targetDir ) ) {
                mkDir( $targetDir, 0777, true );
            }
            $targetDir .= '/ads';
            if ( !is_dir( $targetDir ) ) {
                mkDir( $targetDir, 0777, true );
            }
            $targetDir .= '/'.$user_id;
            if ( !is_dir( $targetDir ) ) {
                mkDir( $targetDir, 0777, true );
            }
            $targetDir .= '/'.$ad_id;
            if ( !is_dir( $targetDir ) ) {
                mkDir( $targetDir, 0777, true );
            }

            $image_key = $request->image_key;
            $uploadedImages = [];
            foreach ( $request->file( $image_key ) as $key => $file ) {
                $sourceFile = 'ad_image_'.( $key+1 ).time().'.'.$file->extension();
                $dest_path = '/uploads/ads/'.$user_id.'/'.$ad_id.'/'.$sourceFile;
                $file->move( $targetDir, $sourceFile );
                $uploadedImages[] = $dest_path;
            }

            foreach ( $uploadedImages as $key => $value ) {
                $ads_meta = new AdsMeta;
                $ads_meta->id_ads = $ad_id;
                $ads_meta->meta_key = '_ad_image';
                $ads_meta->meta_value = $value;
                $ads_meta->save();
            }
        }

        $message = 'Your ads successfully updated.';
        return $response = array( 'success' => $success, 'data' => '', 'message' => $message );
    }

    public function activeAds() {
        $data = array();
        $success = true;
        $message = '';

        $ads = Ads::where( ['id_user' => Auth::user()->id, 'status' => 1] )->orderby( 'updated_at', 'DESC' )->get();
        if ( count( $ads ) == 0 ) {
            $message = 'Ads Not Found.';
            $data['ads'] = [];
        } else {
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

            $data['ads'] = $ads;
        }
        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }

    public function closedAds() {
        $data = array();
        $success = true;
        $message = '';

        $ads = Ads::where( ['id_user' => Auth::user()->id, 'status' => 0] )->orderby( 'updated_at', 'DESC' )->get();
        if ( count( $ads ) == 0 ) {
            $message = 'Ads Not Found.';
            $data['ads'] = [];
        } else {
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

            $data['ads'] = $ads;
        }
        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }

    public function deleteImage( Request $request )
 {
        $ad_id = AdsMeta::where( 'id', $request->id )->value( 'id_ads' );
        $image = AdsMeta::where( 'id', $request->id )->value( 'meta_value' );

        $file_path = substr( $image, 1 );
        unlink( $file_path );
        AdsMeta::where( 'id', $request->id )->delete();

        $data = array();
        $success = false;
        $message = '';

        $ads = Ads::where( 'id', $ad_id )->first();
        $ads->meta;
        $data['ads'] = $ads;
        $success = true;

        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }
}
