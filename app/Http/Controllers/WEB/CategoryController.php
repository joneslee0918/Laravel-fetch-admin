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
        Category::create( $request->all() );
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

    public function edit( $id ) {
        //
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function update( Request $request, $id ) {
        //
        $exist = Category::where( 'name', $request->edit_name )->count();
        if ( $exist > 0 ) {
            return back()->withError( __( 'Category add failed. Category already registered.' ) );
        }
        Category::where( 'id', $request->id_category )->update( ['name' => $request->edit_name, 'etc' => $request->edit_etc] );

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
        Category::where( 'id', $id )->delete();
        return redirect()->route( 'category.index' )->withStatus( __( 'Category successfully deleted.' ) );
    }
}
