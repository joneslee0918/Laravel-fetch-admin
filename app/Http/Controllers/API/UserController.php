<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Hash;
use Storage;
use App\Models\User;
use DB;

class UserController extends Controller
{
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request){
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $success['token'] =  $user->createToken($user->id)->accessToken;
            $success['user'] =  $user;
            return $response = array('success' => true, 'data' => $success);
        }
        else
            return $response = array('success' => false, 'data' => '');
    }
}
