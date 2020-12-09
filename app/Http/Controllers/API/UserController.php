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

class UserController extends Controller {

    public function login( Request $request ) {

        $data = array();
        $success = false;
        $message = '';

        if ( Auth::attempt( ['email' => $request->email, 'password' => $request->password] ) ) {
            $user = Auth::user();
            $user['token'] =  $user->createToken( $user->id )->accessToken;
            $data['user'] =  $user;
            if ( $request->device_token != null ) {
                User::where( 'id', Auth::user()->id )->update( ['device_token' => $request->device_token] );
            } else {
                User::where( 'id', Auth::user()->id )->update( ['iphone_device_token' => $request->device_token] );
            }
            $message = 'Login Success';
            $success = true;
        } else {
            $message = 'Login Failed';
        }

        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }

    public function signup( Request $request ) {

        $data = array();
        $success = false;
        $message = '';

        $username = $request->username;
        $email = $request->email;
        $request['password'] = Hash::make( $request->password );
        $term = $request->term;

        $exist = User::where( 'email', $email )->count();
        if ( $exist > 0 ) {
            $message = 'Register failed. Your email already registered.';
            $success = false;
        } else {
            $user = User::create( $request->all() );
            $user['token'] =  $user->createToken( $user->id )->accessToken;
            $data['user'] = $user;
            $message = 'Register success';
            $success = true;
        }
        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }

    public function profile( Request $request ) {
        $data = array();
        $success = true;
        $message = '';

        $user = User::where( 'id', $request->user_id )->first();
        $user->meta;

        $data['user'] = $user;

        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }

    public function edit( Request $request ) {
        $data = array();
        $message = '';
        $success = true;

        User::where( 'id', Auth::user()->id )->update( ['name' => $request->name, 'email' => $request->email, 'phonenumber' => $request->phonenumber] );

        if ( $request->change_image_status > 0 ) {
            $avatar = Auth::user()->avatar;
            if ( $avatar != '' ) {
                $file_path = substr( $avatar, 1 );
                unlink( $file_path );
            }

            $dest_path = '';
            if ( $request->change_image_status == 1 ) {
                $file = $request->file( 'profile_image' );
                $sourceFile = Auth::user()->id.'.'.$file->extension();
                $file->move( public_path( 'uploads/avatars/' ), $sourceFile );
                $dest_path = '/uploads/avatars/'.$sourceFile;
            }

            User::where( 'id', Auth::user()->id )->update( ['avatar' => $dest_path] );
        }

        $user = User::where( 'id', Auth::user()->id )->first();
        $user->meta;
        $user['token'] =  $user->createToken( $user->id )->accessToken;

        $data['user'] = $user;
        $message = 'Your profile successfully updated.';

        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }
}
