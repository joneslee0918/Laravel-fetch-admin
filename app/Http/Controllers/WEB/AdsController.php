<?php

namespace App\Http\Controllers\WEB;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Ads;
use App\Models\AdsMeta;
use App\Models\Category;
use App\Models\Breed;
use App\Models\User;
use App\Models\UserMeta;
use App\Models\Room;
use App\Models\Chat;
use App\Models\Notification;

class AdsController extends Controller {
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function index() {
        //
        $ads = Ads::orderby( 'updated_at', 'DESC' )->get();
        foreach ( $ads as $key => $value ) {
            $value->meta;
            foreach ( $value['meta'] as $item_key => $item_value ) {
                if ( $item_value->meta_key == '_ad_image' ) {
                    $value['ad_image'] = $item_value->meta_value;
                    break;
                }
            }
        }
        return view( 'ads.index', ['ads_data' => $ads] );
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function create() {
        //
        $user = User::get();
        $category = Category::get();
        $breed = Breed::get();
        return view( 'ads.create', ['user' => $user, 'breed' => $breed, 'category' => $category] );
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */

    public function store( Request $request ) {
        //
        if ( !$request->file( 'photo_path' ) || count( $request->file( 'photo_path' ) ) < 1 || count( $request->file( 'photo_path' ) ) > 5 ) {
            return back()->withError( __( 'Ads post failed. Please add at least one pet images. You can select up to 5 pet images.' ) );
        }
        $ads = Ads::create( $request->all() );
        $user_id = $request->id_user;
        $ad_id = $ads->id;

        $targetDir = base_path( 'uploads' );
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

        $uploadedImages = [];
        foreach ( $request->file( 'photo_path' ) as $key => $file ) {
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

        return redirect()->route( 'ads.index' )->withStatus( __( 'Ads successfully posted.' ) );
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
        $ads = Ads::where( 'id', $id )->first();
        $ads->meta;
        $ad_images = [];
        foreach ( $ads['meta'] as $item_key => $item_value ) {
            if ( $item_value->meta_key == '_ad_image' ) {
                $ad_images[] = $item_value;
            }
        }
        $user = User::get();
        $category = Category::get();
        $breed = Breed::get();
        return view( 'ads.edit', ['ads' => $ads, 'user' => $user, 'breed' => $breed, 'category' => $category, 'ad_images' => $ad_images] );
    }

    public function deleteImage( Request $request ) {
        $meta = AdsMeta::where( 'id', $request->id )->first();
        $exist = AdsMeta::where( ['id_ads' => $meta->id_ads, 'meta_key' => '_ad_image'] )->count();
        if ( $exist == 1 ) {
            return 'failed';
        }

        $file_path = substr( $meta->meta_value, 1 );
        if ( file_exists( $file_path ) ) {
            unlink( $file_path );
        }
        AdsMeta::where( 'id', $request->id )->delete();

        return 'success';
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function update( Request $request, $ad_id ) {
        //
        $user_id = $request->user;

        $exist = AdsMeta::where( ['id_ads' => $ad_id, 'meta_key' => '_ad_image'] )->count();

        $targetDir = '';
        if ( $request->file( 'photo_path' ) ) {
            $targetDir = base_path( 'uploads' );
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

            $uploadedImages = [];
            foreach ( $request->file( 'photo_path' ) as $key => $file ) {
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
        } else if ( $request->file( 'photo_path' ) ) {
            return back()->withError( 'Please add over 5 images.' );
        }

        Ads::where( 'id', $ad_id )->update( [ 'id_user' => $user_id, 'id_category' => $request->category, 'id_breed' => $request->breed, 'gender' => $request->gender, 'age' => $request->age, 'price' => $request->price, 'lat' => $request->lat, 'long' => $request->long, 'description' => $request->description, 'status' => $request->status] );

        return redirect()->route( 'ads.index' )->withStatus( __( 'Ads successfully updated.' ) );
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function destroy( $id ) {
        //
        $id_user = Ads::where( 'id', $id )->value( 'id_user' );

        UserMeta::where( ['meta_value' => $id, 'meta_key' => '_ad_favourite'] )->delete();
        $ads_meta = AdsMeta::where( 'id_ads', $id )->get();
        foreach ( $ads_meta as $meta_key => $meta_value ) {
            if ( $meta_value->meta_key == '_ad_image' ) {
                $file_path = substr( $meta_value->meta_value, 1 );
                if ( file_exists( $file_path ) ) {
                    unlink( $file_path );
                }
            }
        }
        $targetDir = base_path( 'uploads/ads/'.$id_user.'/'.$id );
        if ( is_dir( $targetDir ) && rmdir( $targetDir ) ) {
        }

        AdsMeta::where( 'id_ads', $id )->delete();
        Room::where( 'id_ads', $id )->delete();
        Notification::where( ['type' => 0, 'id_type' => $id] )->delete();
        Ads::where( 'id', $id )->delete();

        return back()->withStatus( __( 'Ads successfully deleted.' ) );
    }
}