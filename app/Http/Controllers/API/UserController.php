<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Hash;
use Storage;
use App\Models\User;
use App\Models\UserMeta;
use App\Models\Ads;
use DB;

class UserController extends Controller {

    public function login( Request $request ) {

        $data = array();
        $success = false;
        $message = '';

        if ( Auth::attempt( ['email' => $request->email, 'password' => $request->password] ) ) {
            $user = Auth::user();
            $user['token'] =  $user->createToken( $user->id )->accessToken;
            $user->meta;
            $data['user'] =  $user;
            if ( $request->device_token != null ) {
                User::where( 'id', Auth::user()->id )->update( ['device_token' => $request->device_token] );
            } else {
                User::where( 'id', Auth::user()->id )->update( ['iphone_device_token' => $request->iphone_device_token] );
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
            $message = 'Register success';
            $success = true;

            $user_meta = new UserMeta;
            $user_meta->id_user = $user->id;
            $user_meta->meta_key = '_show_notification';
            $user_meta->meta_value = 1;
            $user_meta->save();

            $user_meta = new UserMeta;
            $user_meta->id_user = $user->id;
            $user_meta->meta_key = '_show_phone_on_ads';
            $user_meta->meta_value = 1;
            $user_meta->save();

            $user->meta;
            $data['user'] = $user;
        }
        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }

    public function profile( Request $request ) {
        $data = array();
        $success = true;
        $message = '';

        $user = User::where( 'id', $request->user_id )->first();
        $user->meta;

        if ( $request->inventory == true ) {
            $ads = Ads::where( 'id_user', $request->user_id )->orderby( 'updated_at', 'DESC' )->get();
            if ( count( $ads ) == 0 ) {
                $message = 'This user does not have any ads.';
                $data['ads'] = [];
            } else {
                foreach ( $ads as $key => $item ) {
                    $user = $item->user;
                    $item->category;
                    $item->breed;
                    $item->meta;
                    $user->meta;
                    $item['user'] = $user;

                    $exsit_fav = UserMeta::where( ['id_user' => Auth::user()->id, 'meta_key' => '_ad_favourite', 'meta_value' => $item['id']] )->count();
                    $is_fav = $exsit_fav == 0 ? false : true;
                    $item['is_fav'] = $is_fav;
                }

                $data['ads'] = $ads;
            }
        }

        $data['user'] = $user;

        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }

    public function edit( Request $request ) {
ini_set('max_execution_time', 3000);

        $data = array();
        $message = '';
        $success = true;

        User::where( 'id', Auth::user()->id )->update( ['name' => $request->name, 'email' => $request->email, 'phonenumber' => $request->phonenumber] );

        if ( $request->change_image_status > 0 ) {
            $avatar = Auth::user()->avatar;
            if ( $avatar != '' ) {
                $file_path = substr( $avatar, 1 );
                unlink( $file_path );

                User::where( 'id', Auth::user()->id )->update( ['avatar' => null] );
            }

            $dest_path = '';
            if ( $request->change_image_status == 1 ) {
                $targetDir = public_path( 'uploads' );
                if ( !is_dir( $targetDir ) ) {
                    mkDir( $targetDir, 0777, true );
                }
                $targetDir .= '/avatars';
                if ( !is_dir( $targetDir ) ) {
                    mkDir( $targetDir, 0777, true );
                }

                $file = $request->file( 'profile_image' );
                $sourceFile = Auth::user()->id.time().'.'.$file->extension();
                $file->move( $targetDir, $sourceFile );
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

    public function changePassword( Request $request ) {
        $data = array();
        $message = '';
        $success = true;

        if ( !Hash::check( $request->currentPwd, Auth::user()->password ) ) {
            $message = 'Please input current password correctly.';
            $success = false;
        } else {
            Auth::user()->update( ['password' => Hash::make( $request->password )] );
            $success = true;
            $message = 'Password changed successfully.';
        }
        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }

    public function setUserMeta( Request $request ) {
        $data = array();
        $message = '';
        $success = true;

        UserMeta::where( ['id_user' => Auth::user()->id, 'meta_key' => $request->key] )->update( ['meta_value' => $request->value] );

        $user = User::where( 'id', Auth::user()->id )->first();
        $user->meta;
        $user['token'] =  $user->createToken( $user->id )->accessToken;

        $data['user'] = $user;

        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }
}