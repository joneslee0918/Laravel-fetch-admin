<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Storage;
use App\Models\User;
use App\Models\Ads;
use DB;

class AdsController extends Controller {
    public function latest() {

        $data = array();
        $success = false;
        $message = '';

        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }
}
