<?php

namespace App\Http\Controllers\WEB;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Hash;
use App\Models\User;
use App\Models\UserMeta;
use App\Models\Ads;
use App\Models\AdsMeta;
use App\Models\Chat;
use App\Models\Notification;

class UserController extends Controller {
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function __construct() {
        $this->middleware( 'auth' );
    }

    public function index() {
        //
        $data = array();
        $users = User::orderby( 'created_at', 'ASC' )->orderby( 'role', 'DESC' )->get();
        $data['users'] = $users;

        return view( 'user.index', ['data' => $data] );
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function create() {
        //
        return view( 'user.create' );
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */

    public function store( Request $request ) {
        //
        $email = $request->email;
        $request['password'] = Hash::make( $request->password );

        $exist = User::where( 'email', $email )->count();
        if ( $exist > 0 ) {
            return back()->withStatus( __( 'Register failed. Email already registered.' ) );
        } else {
            $user = User::create( $request->all() );

            $user_meta = new UserMeta;
            $user_meta->id_user = $user->id;
            $user_meta->meta_key = '_show_notification';
            $user_meta->meta_value = $request->_show_notification;
            $user_meta->save();

            $user_meta = new UserMeta;
            $user_meta->id_user = $user->id;
            $user_meta->meta_key = '_show_phone_on_ads';
            $user_meta->meta_value = $request->_show_phone_on_ads;
            $user_meta->save();

            $file = $request->file( 'photo_path' );
            if ( $file != null ) {
                $targetDir = public_path( 'uploads' );
                if ( !is_dir( $targetDir ) ) {
                    mkDir( $targetDir, 0777, true );
                }
                $targetDir .= '/avatars';
                if ( !is_dir( $targetDir ) ) {
                    mkDir( $targetDir, 0777, true );
                }
                $sourceFile = $user->id.time().'.'.$file->extension();
                $file->move( $targetDir, $sourceFile );
                $dest_path = '/uploads/avatars/'.$sourceFile;
                User::where( 'id', $user->id )->update( ['avatar' => $dest_path] );
            }

            return redirect()->route( 'user.index' )->withStatus( __( 'User successfully created.' ) );
        }
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function show( $id ) {
        //
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function edit( User $user ) {
        //
        $user->meta;
        $user_meta = array();
        foreach ( $user['meta'] as $key => $value ) {
            if ( $value->meta_key == '_show_notification' || $value->meta_key == '_show_phone_on_ads' )
            $user_meta[$value->meta_key] = $value->meta_value;
        }
        return view( 'user.edit', ['user' => $user, 'user_meta' => $user_meta] );
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function update( Request $request, User $user ) {
        //
        $password = $request->get( 'password' );
        if ( $password == '' )
        $request->offsetUnset( 'password' );
        else
        $request->merge( ['password' => Hash::make( $password )] );

        $file = $request->file( 'photo_path' );

        if ( $file != null ) {
            $avatar = $user->avatar;
            if ( $avatar != '' ) {
                $file_path = substr( $avatar, 1 );
                unlink( $file_path );

                User::where( 'id', $user->id )->update( ['avatar' => null] );
            }

            $targetDir = public_path( 'uploads' );
            if ( !is_dir( $targetDir ) ) {
                mkDir( $targetDir, 0777, true );
            }
            $targetDir .= '/avatars';
            if ( !is_dir( $targetDir ) ) {
                mkDir( $targetDir, 0777, true );
            }

            $sourceFile = $user->id.time().'.'.$file->extension();
            $file->move( $targetDir, $sourceFile );
            $dest_path = '/uploads/avatars/'.$sourceFile;

            $user->update( ['avatar' => $dest_path] );
        }
        $user->update( $request->all() );

        UserMeta::where( ['id_user' => $user->id, 'meta_key' => '_show_notification'] )->update( ['meta_value' => $request->_show_notification] );
        UserMeta::where( ['id_user' => $user->id, 'meta_key' => '_show_phone_on_ads'] )->update( ['meta_value' => $request->_show_phone_on_ads] );
        // ( new EmailController )->sendMail( $user->email, 4, null );
        // $key = $this->firebase->push( 'Alarm', md5( 'user'.$user->id ) );
        // $this->firebase->delete( 'Alarm/'.$key );
        return redirect()->route( 'user.index' )->withStatus( __( 'User successfully updated.' ) );
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function destroy( $id ) {
        //
        $avatar = User::where( 'id', $id )->value( 'avatar' );
        if ( $avatar != '' ) {
            $file_path = substr( $avatar, 1 );
            unlink( $file_path );
        }
        User::where( 'id', $id )->delete();
        UserMeta::where( 'id_user', $id )->delete();

        $ads = Ads::where( 'id_user', $id )->get();
        $targetDir = '/uploads/ads/'.$id;
        foreach ( $ads as $key => $value ) {
            UserMeta::where( ['meta_value' => $value->id, 'meta_key' => '_ad_favourite'] )->delete();
            $ads_meta = AdsMeta::where( 'id_ads', $value->id )->get();
            foreach ( $ads_meta as $meta_key => $meta_value ) {
                if ( $meta_value->meta_key == '_ad_image' ) {
                    $file_path = substr( $meta_value->meta_value, 1 );
                    unlink( $file_path );
                }
            }
            rmdir( $targetDir.'/'.$value->id );

            AdsMeta::where( 'id_ads', $value->id )->delete();
            Chat::where( 'id_ads', $value->id )->delete();
            Notification::where( ['type' => 0, 'id_type' => $value->id] )->delete();
        }
        Ads::where( 'id_user', $id )->delete();
        return back()->withStatus( __( 'User successfully deleted.' ) );
    }
}