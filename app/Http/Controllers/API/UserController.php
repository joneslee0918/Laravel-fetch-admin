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

        $data = array();
        $success = false;
        $message = '';

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $user['token'] =  $user->createToken($user->id)->accessToken;
            $data['user'] =  $user;
            $message = 'Login Success';
            $success = true;
        }
        else{
            $message = 'Login Failed';
        }
            return $response = array('success' => $success, 'data' => $data, 'message' => $message);
    }

    public function signup(Request $request){

        $data = array();
        $success = false;
        $message = '';

        $username = $request->username;
        $email = $request->email;
        $password = $request->password;
        $term = $request->term;
        $exist = User::where('email', $email)->count();
        if($exist > 0)
        {
            $message = 'Register failed. Your email already registered.';
            $success = false;
        }
        else {
            $user = User::create($request->all());
            $user['token'] =  $user->createToken($user->id)->accessToken;
            $data['user'] = $user;
            $message = 'Register success';
            $success = true;
        }
        return $response = array('success' => $success, 'data' => $data, 'message' => $message);
    }
}
