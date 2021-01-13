<?php

namespace App\Http\Controllers\WEB;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ads;
use App\Models\Category;
use App\Models\Breed;

class HomeController extends Controller {
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

        $active_ads = Ads::where( 'status', 1 )->count();
        $closed_ads = Ads::where( 'status', 0 )->count();
        $data['active_ads'] = $active_ads;
        $data['closed_ads'] = $closed_ads;

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

        return view( 'dashboard', ['data' => $data] );
    }
    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function privacy() {
        return view( 'privacy' );
    }

    public function terms() {
        return view( 'terms' );
    }
}
