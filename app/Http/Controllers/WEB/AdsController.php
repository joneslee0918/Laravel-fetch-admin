<?php

namespace App\Http\Controllers\WEB;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Ads;
use App\Models\AdsMeta;
use App\Models\Category;
use App\Models\Breed;

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
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */

    public function store( Request $request ) {
        //
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
        $category = Category::get();
        $breed = Breed::get();
        return view( 'ads.edit', ['ads' => $ads, 'breed' => $breed, 'category' => $category, 'ad_images' => $ad_images] );
    }

    public function deleteImage( Request $request ) {
        $meta = AdsMeta::where( 'id', $request->id )->first();
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

    public function update( Request $request, Ads $ads ) {
        //
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