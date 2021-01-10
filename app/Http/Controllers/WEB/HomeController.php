<?php

namespace App\Http\Controllers\WEB;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ads;
use App\Models\Category;
use App\Models\Breed;

use Analytics;
use Spatie\Analytics\Period;

class HomeController extends Controller {
    /**
    * Show the application dashboard.
    *
    * @return \Illuminate\View\View
    */

    public function index() {
        $data = array();

        //Retrieve Most Visited Pages
        $pages = Analytics::fetchMostVisitedPages( Period::days( 10 ) );

        //retrieve visitors and pageview data for the current day and the last fifteen days
        $visitors = Analytics::fetchVisitorsAndPageViews( Period::days( 15 ) );

        // Retrieve Total Visitors and Page Views
        $total_visitors = Analytics::fetchTotalVisitorsAndPageViews( Period::days( 7 ) );

        // Retrieve Top Referrers
        $top_referrers = Analytics::fetchTopReferrers( Period::days( 7 ) );

        // Retrieve User Types
        $user_types = Analytics::fetchUserTypes( Period::days( 7 ) );

        //Retrieve Top Browsers
        $top_browser = Analytics::fetchTopBrowsers( Period::days( 7 ) );

        //retrieve sessions and pageviews with yearMonth dimension since 1 year ago
        $analyticsData = Analytics::performQuery(
            Period::years( 1 ),
            'ga:sessions',
            [
                'metrics' => 'ga:sessions, ga:pageviews',
                'dimensions' => 'ga:yearMonth'
            ]
        );

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
