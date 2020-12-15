<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ads;
use App\Models\Category;
use App\Models\Breed;

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
        $data = array();

        $activated_users = User::where( 'active', 1 )->count();
        $deactivated_users = User::where( 'active', 0 )->count();
        $data['activated_users'] = $activated_users;
        $data['deactivated_users'] = $deactivated_users;

        $ads = Ads::groupby( 'id_category' )->get();
        $category_ads = [];

        $item = [];
        $item[] = 'Category';
        $item[] = 'Count';
        $category_ads[] = $item;

        foreach ( $ads as $key => $value ) {
            $item = [];
            $value->category;
            $item[] = $value['category']['name'];
            $item[] = Ads::where( 'id_category', $value->id_category )->count();
            $category_ads[] = $item;
        }
        $data['category_ads'] = $category_ads;

        $ads = Ads::groupby( 'id_breed' )->get();
        $breed_ads = [];

        $item = [];
        $item[] = 'Breed';
        $item[] = 'Count';
        $breed_ads[] = $item;

        foreach ( $ads as $key => $value ) {
            $item = [];
            $value->breed;
            $item[] = $value['breed']['name'];
            $item[] = Ads::where( 'id_breed', $value->id_breed )->count();
            $breed_ads[] = $item;
        }
        $data['breed_ads'] = $breed_ads;

        $status_ads = [];

        $item = [];
        $item[] = 'Status Ads';
        $item[] = 'Count';
        $status_ads[] = $item;

        $item = [];
        $item[] = 'Active Ads';
        $item[] = Ads::where( 'status', 1 )->count();
        $status_ads[] = $item;

        $item = [];
        $item[] = 'Closed Ads';
        $item[] = Ads::where( 'status', 0 )->count();
        $status_ads[] = $item;

        $data['status_ads'] = $status_ads;

        return view( 'dashboard', ['data'=>$data] );
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
