<?php

namespace App\Http\Controllers\WEB;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Breed;
use App\Models\Ads;

class BreedController extends Controller {
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function index() {
        //
        return view( 'breed.index', ['breeds' => Breed::get()] );
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
        $exist = Breed::where( 'name', $request->name )->count();
        if ( $exist > 0 ) {
            return back()->withError( __( 'Breed add failed. Breed already registered.' ) );
        }
        Breed::create( $request->all() );
        return redirect()->route( 'breed.index' )->withStatus( __( 'Breed successfully created.' ) );
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
        $exist = Breed::where( 'name', $request->edit_name )->count();
        if ( $exist > 0 ) {
            return back()->withError( __( 'Breed add failed. Breed already registered.' ) );
        }
        Breed::where( 'id', $request->id_breed )->update( ['name' => $request->edit_name, 'etc' => $request->edit_etc] );

        return redirect()->route( 'breed.index' )->withStatus( __( 'Breed successfully updated.' ) );
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function destroy( $id ) {
        //
        $exist = Ads::where( 'id_breed', $id )->count();
        if ( $exist > 0 ) {
            return back()->withError( __( 'Breed cannot be deleted. Ads already registered on this breed.' ) );
        }
        Breed::where( 'id', $id )->delete();
        return redirect()->route( 'breed.index' )->withStatus( __( 'Breed successfully deleted.' ) );
    }
}
