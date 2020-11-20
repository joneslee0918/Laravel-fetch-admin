<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Storage;
use App\Models\User;
use App\Models\Ads;
use App\Models\Category;
use App\Models\Breed;
use DB;

class HomeController extends Controller {
    public function home() {

        $data = array();
        $success = false;
        $message = '';

        $ads = Ads::orderby( 'updated_at' )->get();
        foreach ( $ads as $key => $item ) {
            $item->user;
            $item->category;
            $item->breed;
            $item->meta;
        }

        $breed = Breed::orderby( 'order' )->get();
        $category = Category::orderby( 'order' )->get();

        $data['category'] = $category;
        $data['breed'] = $breed;
        $data['ads'] = $ads;

        $success = true;

        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }

    public function filter( Request $request ) {
        $data = array();
        $message = '';
        $success = false;

        $id_category = $request->id_category;
        $id_breed = $request->id_breed;
        $gender = $request->gender;
        $price = $request->price;

        $ads = Ads::where( ['id_category' => $id_category, 'id_breed' => $id_breed, 'gender' => $gender] )->where( 'price', '>=', $price['min'] )->where( 'price', '<=', $price['max'] )->orderby( 'updated_at' )->get();
        if ( count( $ads ) == 0 ) {
            $message = 'No found Ads.';
        } else {
            $data['ads'] = $ads;
        }
        $success = true;

        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }
}
