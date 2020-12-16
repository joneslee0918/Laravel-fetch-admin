<?php

namespace App\Http\Controllers\WEB;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Ads;
use App\Models\AdsMeta;
use App\Models\Category;
use App\Models\Breed;
use App\Models\User;

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
        if ( !$request->file( 'photo_path' ) ) {
            return back()->withError( __( 'Ads post failed. Please add ads images.' ) );
        }
        $ads = Ads::create($request->all());
        $user_id = $request->id_user;
        $ad_id = $ads->id;

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
        if ( $exist == 1 )
        return 'failed';

        $file_path = substr( $meta->meta_value, 1 );
        unlink( $file_path );
        AdsMeta::where( 'id', $request->id )->delete();

        return 'success';
    }

    public function removeEmptyDirs( $path, $checkUpdated = false, $report = false ) {
        $dirs = glob( $path . '/*', GLOB_ONLYDIR );

        foreach ( $dirs as $dir ) {
            $files = glob( $dir . '/*' );
            $innerDirs = glob( $dir . '/*', GLOB_ONLYDIR );
            if ( empty( $files ) ) {
                if ( !rmdir( $dir ) )
                echo 'Err: ' . $dir . '<br />';
                elseif ( $report )
                echo $dir . ' - removed!' . '<br />';
            } elseif ( !empty( $innerDirs ) ) {
                removeEmptyDirs( $dir, $checkUpdated, $report );
                if ( $checkUpdated )
                removeEmptyDirs( $path, $checkUpdated, $report );
            }
        }
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

        Ads::where( 'id', $ad_id )->update( [ 'id_user' => $user_id, 'id_category' => $request->category, 'id_breed' => $request->breed, 'gender' => $request->gender, 'age' => $request->age, 'price' => $request->price, 'lat' => $request->lat, 'long' => $request->long, 'description' => $request->description, 'status' => $request->status] );

        $targetDir = '';
        if ( $request->file( 'photo_path' ) ) {
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
        }

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
    }
}