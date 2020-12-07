<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Storage;
use App\Models\User;
use App\Models\UserMeta;
use App\Models\Ads;
use App\Models\AdsMeta;
use App\Models\Category;
use App\Models\Breed;
use DB;

class HomeController extends Controller {
    public function home() {

        $data = array();
        $success = true;
        $message = '';

        $ads = Ads::get();
        if ( count( $ads ) == 0 ) {
            $message = 'Ads Not Found.';
            $data['ads'] = [];
        } else {
            foreach ( $ads as $key => $item ) {
                $item->user;
                $item->category;
                $item->breed;
                $item->meta;

                $exsit_fav = UserMeta::where( ['id_user' => Auth::user()->id, 'meta_key' => '_ad_favourite', 'meta_value' => $item['id']] )->count();
                $is_fav = $exsit_fav == 0 ? false : true;
                $item['is_fav'] = $is_fav;
            }

            $data['ads'] = $ads;
        }

        $breed = Breed::orderby( 'order' )->get();
        $category = Category::orderby( 'order' )->get();

        $data['category'] = $category;
        $data['breed'] = $breed;
        $data['ads'] = $ads;

        $success = true;

        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }

    public function filter_category( Request $request ) {
        $data = array();
        $success = true;
        $message = '';

        $searchText = $request->searchText;
        $ads_ids = [];
        if($searchText != '')
        {
            $searchText = "'%".$request->searchText."%'";
            $strQuery = "SELECT a.id AS id FROM ads AS a LEFT JOIN category AS b ON a.`id_category` = b.`id` LEFT JOIN breed AS c ON a.`id_breed` = c.`id` WHERE c.`name` LIKE ".$searchText." OR b.`name` LIKE ".$searchText;
            $result = DB::select($strQuery);
            for ($i=0; $i < count($result); $i++) {
                $ads_ids[] = $result[$i]->id;
            }
        }
        if ( $request->id_category == -1 ) {
            $ads = Ads::get();
            if(count($ads_ids) > 0)
                $ads = Ads::whereIn('id', $ads_ids)->get();
        } else {
            $ads = Ads::where( 'id_category', $request->id_category )->get();
            if(count($ads_ids) > 0)
               $ads = Ads::where( 'id_category', $request->id_category )->whereIn('id', $ads_ids)->get();
        }

        if ( count( $ads ) == 0 ) {
            $message = 'Ads Not Found.';
            $data['ads'] = [];
        } else {
            foreach ( $ads as $key => $item ) {
                $item->user;
                $item->category;
                $item->breed;
                $item->meta;

                $exsit_fav = UserMeta::where( ['id_user' => Auth::user()->id, 'meta_key' => '_ad_favourite', 'meta_value' => $item['id']] )->count();
                $is_fav = $exsit_fav == 0 ? false : true;
                $item['is_fav'] = $is_fav;
            }

            $data['ads'] = $ads;
        }

        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }

    public function filter( Request $request ) {
        $data = array();
        $message = '';
        $success = true;

        $id_category = $request->id_category;
        $id_breed = $request->id_breed;
        $gender = $request->gender;
        $price = $request->price;

        $ads = Ads::where( ['id_category' => $id_category, 'id_breed' => $id_breed, 'gender' => $gender] )->where( 'price', '>=', $price['min'] )->where( 'price', '<=', $price['max'] )->orderby( 'updated_at' )->get();
        if ( count( $ads ) == 0 ) {
            $message = 'Ads Not Found.';
        } else {
            foreach ( $ads as $key => $item ) {
                $item->user;
                $item->category;
                $item->breed;
                $item->meta;

                $exsit_fav = UserMeta::where( ['id_user' => Auth::user()->id, 'meta_key' => '_ad_favourite', 'meta_value' => $item['id']] )->count();
                $is_fav = $exsit_fav == 0 ? false : true;
                $item['is_fav'] = $is_fav;
            }

            $data['ads'] = $ads;
        }

        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }
}
