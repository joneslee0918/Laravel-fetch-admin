<?php

namespace App\Http\Controllers\WEB;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Ads;

class CategoryController extends Controller {
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function index() {
        //
        return view( 'category.index', ['categories' => Category::get()] );
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function create() {
        //
        return view( 'category.create' );
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */

    public function store( Request $request ) {
        //
        $exist = Category::where( 'name', $request->name )->count();
        if ( $exist > 0 ) {
            return back()->withError( __( 'Category add failed. Category already registered.' ) );
        }

        $category = Category::create( $request->all() );

        $file = $request->file( 'photo_path' );
        if ( $file != null ) {
            $targetDir = base_path( 'uploads' );
            if ( !is_dir( $targetDir ) ) {
                mkDir( $targetDir, 0777, true );
            }
            $targetDir .= '/category';
            if ( !is_dir( $targetDir ) ) {
                mkDir( $targetDir, 0777, true );
            }
            $sourceFile = $category->id.time().'.'.$file->extension();
            $file->move( $targetDir, $sourceFile );
            $dest_path = '/uploads/category/'.$sourceFile;
            Category::where( 'id', $category->id )->update( ['icon' => $dest_path] );
        }

        return redirect()->route( 'category.index' )->withStatus( __( 'Category successfully created.' ) );
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

    public function edit( Category $category ) {
        //
        return view( 'category.edit', ['category' => $category] );
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function update( Request $request, Category $category ) {
        //
        $exist = Category::where( 'name', $request->name )->count();
        if ( $exist > 1 ) {
            return back()->withError( __( 'Category add failed. Category already registered.' ) );
        }
        Category::where( 'id', $category->id )->update( ['name' => $request->name, 'etc' => $request->etc] );

        $file = $request->file( 'photo_path' );

        if ( $file ) {
            $icon = $category->icon;
            if ( $icon != '' ) {
                $file_path = substr( $icon, 1 );
                if ( file_exists( $file_path ) ) {
                    unlink( $file_path );
                }

                Category::where( 'id', $category->id )->update( ['icon' => null] );
            }

            $targetDir = base_path( 'uploads' );
            if ( !is_dir( $targetDir ) ) {
                mkDir( $targetDir, 0777, true );
            }
            $targetDir .= '/category';
            if ( !is_dir( $targetDir ) ) {
                mkDir( $targetDir, 0777, true );
            }

            $sourceFile = $category->id.time().'.'.$file->extension();
            $file->move( $targetDir, $sourceFile );
            $dest_path = '/uploads/category/'.$sourceFile;

            $category->update( ['icon' => $dest_path] );
        }

        return redirect()->route( 'category.index' )->withStatus( __( 'Category successfully updated.' ) );
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function destroy( $id ) {
        //
        $exist = Ads::where( 'id_category', $id )->count();
        if ( $exist > 0 ) {
            return back()->withError( __( 'Category cannot be deleted. Ads already registered on this category.' ) );
        }
        $icon = Category::where( 'id', $id )->value( 'icon' );
        if ( $icon != '' ) {
            $file_path = substr( $icon, 1 );
            if ( file_exists( $file_path ) ) {
                unlink( $file_path );
            }
        }
        Category::where( 'id', $id )->delete();
        return redirect()->route( 'category.index' )->withStatus( __( 'Category successfully deleted.' ) );
    }
}
