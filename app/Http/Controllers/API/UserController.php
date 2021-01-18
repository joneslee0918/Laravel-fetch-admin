<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Controllers\WEB\EmailController;
use Illuminate\Http\Request;
use Validator;
use Hash;
use Storage;
use App\Models\User;
use App\Models\UserMeta;
use App\Models\Ads;
use App\Models\Follower;
use App\Models\Review;
use DB;

class UserController extends Controller {
    private $email;

    public function __construct() {
        $this->email = new EmailController;
    }

    public function login( Request $request ) {

        $data = array();
        $success = false;
        $message = '';

        $email = $request->email;
        $password = $request->password;
        if ( $request->guest ) {
            $email = 'guest@guest.com';
            $password = 'fetch';
        }

        if ( Auth::attempt( ['email' => $email, 'password' => $password] ) ) {
            $user = Auth::user();
            $user['token'] =  $user->createToken( $user->id )->accessToken;
            $user->meta;
            $data['user'] =  $user;
            if ( !$request->guest ) {
                if ( $request->device_token != null ) {
                    User::where( 'id', Auth::user()->id )->update( ['device_token' => $request->device_token] );
                } else {
                    User::where( 'id', Auth::user()->id )->update( ['iphone_device_token' => $request->iphone_device_token] );
                }
            }
            $message = 'Login Success.';
            $success = true;
            if ( $user->active == 0 ) {
                $message = 'Your account has been deactivated.';
                $success = false;
            }
        } else {
            $message = 'Login Failed.';
        }

        if ( $request->guest ) {
            $message  = '';
        }

        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }

    public function signup( Request $request ) {

        $data = array();
        $success = false;
        $message = '';

        $email = $request->email;
        $request['password'] = Hash::make( $request->password );

        $exist = User::where( 'email', $email )->count();
        if ( $exist > 0 ) {
            $message = 'Register failed. Your email already registered.';
            $success = false;
        } else {
            $user = User::create( $request->all() );
            $user['token'] =  $user->createToken( $user->id )->accessToken;
            $message = 'Register success.';
            $success = true;

            $this->email->sendMail( $user->email, 1, null );

            $user_meta = new UserMeta;
            $user_meta->id_user = $user->id;
            $user_meta->meta_key = '_show_notification';
            $user_meta->meta_value = 1;
            $user_meta->save();

            $user_meta = new UserMeta;
            $user_meta->id_user = $user->id;
            $user_meta->meta_key = '_show_ads_notification';
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

        try {
            $user = User::where( 'id', $request->user_id )->first();
            $user->meta;
            $user->follower;
            $user->following;
            $user->review;
            $user->ads;

            $is_follow = Follower::where( ['id_user' => $user->id, 'id_follow_user' => Auth::user()->id] )->count();
            if ( $is_follow > 0 ) {
                $is_follow = true;
            } else {
                $is_follow = false;
            }

            $data['is_follow'] = $is_follow;

            if ( $request->inventory == true ) {
                $ads = Ads::where( 'id_user', $request->user_id )->orderby( 'updated_at', 'DESC' )->get();
                if ( count( $ads ) == 0 ) {
                    $message = 'This user does not have any ads.';
                    $data['ads'] = [];
                } else {
                    foreach ( $ads as $key => $item ) {
                        $item_user = $item->user;
                        $item->category;
                        $item->breed;
                        $item->meta;
                        $item_user->meta;
                        $item['user'] = $item_user;

                        $exsit_fav = UserMeta::where( ['id_user' => Auth::user()->id, 'meta_key' => '_ad_favourite', 'meta_value' => $item['id']] )->count();
                        $is_fav = $exsit_fav == 0 ? false : true;
                        $item['is_fav'] = $is_fav;
                    }

                    $data['ads'] = $ads;
                }
            }

            $data['user'] = $user;
        } catch ( \Throwable $th ) {
            $data = array();
            $success = false;
            $message = '';
        }

        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }

    public function edit( Request $request ) {
        $data = array();
        $message = '';
        $success = true;

        try {
            $exist = User::where( 'email', $request->email )->count();
            if ( $exist > 1 ) {
                $message = 'Profile update failed. Your email already registered.';
                $success = false;
                return $response = array( 'success' => $success, 'data' => '', 'message' => $message );
            }

            User::where( 'id', Auth::user()->id )->update( ['name' => $request->name, 'email' => $request->email, 'phonenumber' => $request->phonenumber] );

            if ( $request->change_image_status > 0 ) {
                $avatar = Auth::user()->avatar;
                if ( $avatar != '' ) {
                    $file_path = substr( $avatar, 1 );
                    if ( file_exists( $file_path ) ) {
                        unlink( $file_path );
                    }

                    User::where( 'id', Auth::user()->id )->update( ['avatar' => null] );
                }

                $dest_path = '';
                if ( $request->change_image_status == 1 ) {
                    $targetDir = base_path( 'uploads' );
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
        } catch ( \Throwable $th ) {
            $data = array();
            $message = '';
            $success = false;
        }

        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }

    public function changePassword( Request $request ) {
        $data = array();
        $message = '';
        $success = true;

        try {
            if ( !Hash::check( $request->currentPwd, Auth::user()->password ) ) {
                $message = 'Please input current password correctly.';
                $success = false;
            } else {
                Auth::user()->update( ['password' => Hash::make( $request->password )] );
                $success = true;
                $message = 'Password changed successfully.';

                $this->email->sendMail( Auth::user()->email, 2, null );
            }
        } catch ( \Throwable $th ) {
            $data = array();
            $message = '';
            $success = false;
        }

        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }

    public function setUserMeta( Request $request ) {
        $data = array();
        $message = '';
        $success = true;

        try {
            UserMeta::updateOrCreate( ['id_user' => Auth::user()->id, 'meta_key' => $request->key], ['id_user' => Auth::user()->id, 'meta_key' => $request->key, 'meta_value' => $request->value] );

            $user = User::where( 'id', Auth::user()->id )->first();
            $user->meta;
            $user['token'] =  $user->createToken( $user->id )->accessToken;

            $data['user'] = $user;
        } catch ( \Throwable $th ) {
            $data = array();
            $message = '';
            $success = false;
        }

        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }

    public function setDeviceToken( Request $request ) {
        $data = array();
        $message = '';
        $success = true;

        try {
            if ( $request->platform == 'android' ) {
                User::where( 'id', Auth::user()->id )->update( ['device_token' => $request->token] );
            } else {
                User::where( 'id', Auth::user()->id )->update( ['iphone_device_token' => $request->token] );
            }
        } catch ( \Throwable $th ) {
            $data = array();
            $message = '';
            $success = false;
        }

        return $response = array( 'success' => $success, 'data' => '', 'message' => $message );
    }

    public function forgotpassword( Request $request ) {

    }

    public function accountStatus() {
        $data = array();
        $message = '';
        $success = true;

        try {
            $data['status'] = Auth::user()->active;

            if ( $data['status'] == 0 ) {
                $message = 'Your account has been deactivated.';
                $success = false;
            }
        } catch ( \Throwable $th ) {
            $data = array();
            $message = '';
            $success = false;
        }

        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }

    public function followUser( Request $request ) {
        $data = array();
        $message = '';
        $success = true;

        try {
            $exist = Follower::where( ['id_user' => $request->id, 'id_follow_user' => Auth::user()->id] )->count();
            if ( $exist > 0 ) {
                Follower::where( ['id_user' => $request->id, 'id_follow_user' => Auth::user()->id] )->delete();
                $message = "You don't follow this user anymore.";
            } else {
                $follower = new Follower;
                $follower->id_user = $request->id;
                $follower->id_follow_user = Auth::user()->id;
                $follower->save();
                $message = 'You follow this user from now.';
            }
        } catch ( \Throwable $th ) {
            $data = array();
            $message = '';
            $success = false;
        }

        return $response = array( 'success' => $success, 'data' => '', 'message' => $message );
    }
}