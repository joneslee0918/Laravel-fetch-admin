<?php

namespace App\Http\Controllers\WEB;
use App\Http\Controllers\Controller;
use App\User;
use App\Group;
use App\News;
use App\Contact;

class HomeController extends Controller {
    /**
    * Create a new controller instance.
    *
    * @return void
    */

    public function __construct() {
        $this->middleware( 'auth' );
    }

    /**
    * Show the application dashboard.
    *
    * @return \Illuminate\View\View
    */

    public function index() {
        $data = [];
        $user = new User;
        $news = new News;
        $group = new Group;
        $contact = new Contact;
        $contact->where( 'id', '!=', 0 )->update( ['read' => 1] );
        $data['admin'] = $user->where( 'role', 2 )->orwhere( 'role', 7 )->count();
        $data['otheruser'] = $user->where( 'role', '!=', 2 )->where( 'role', '!=', 7 )->count();
        $data['news'] = $news->count();
        $data['group'] = $group->count();

        $data['partner'] = $user->where( 'role', 3 )->count();
        $data['user'] = $user->where( 'role', 5 )->count();
        $data['superuser'] = $user->where( 'role', 4 )->count();
        $data['external'] = $user->where( 'role', 6 )->count();

        $data['clubnews'] = $news->where( 'type', 0 )->count();
        $data['partnernews'] = $news->where( 'type', 1 )->count();
        $data['othernews'] = $news->where( 'type', 2 )->count();
        $data['draft'] = $news->where( 'type', -1 )->count();
        $data['contact'] = $contact->orderby( 'created_at', 'desc' )->get();
        return view( 'dashboard', ['dashboard'=>$data] );
    }
    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function destroy( $id ) {
        ( new Contact )->where( 'id', $id )->delete();
        return back()->withStatus( __( 'Contact data successfully deleted.' ) );
    }
}
