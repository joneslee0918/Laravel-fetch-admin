<?php

namespace App\Http\Controllers\WEB;
use App\Http\Controllers\Controller;

use App\Http\Requests\ProfileRequest;
use App\Http\Requests\PasswordRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller {
    /**
    * Show the form for editing the profile.
    *
    * @return \Illuminate\View\View
    */

    public function edit() {
        return view( 'profile.edit' );
    }

    /**
    * Update the profile
    *
    * @param  \App\Http\Requests\ProfileRequest  $request
    * @return \Illuminate\Http\RedirectResponse
    */

    public function update( ProfileRequest $request ) {
        $exist = User::where( 'email', $request->email )->count();
        if ( $exist > 1 ) {
            return back()->withError( __( 'Update failed. Email already registered.' ) );
        }
        
        $file = $request->file( 'photo_path' );
        $path = '';
        if ( $file != null ) {
            $targetDir = public_path( 'uploads' );
            if ( !is_dir( $targetDir ) ) {
                mkDir( $targetDir, 0777, true );
            }
            $targetDir .= '/avatars';
            if ( !is_dir( $targetDir ) ) {
                mkDir( $targetDir, 0777, true );
            }

            $sourceFile = auth()->user()->id.time().'.'.$file->extension();
            $file->move( $targetDir, $sourceFile );
            $dest_path = '/public/uploads/avatars/'.$sourceFile;

            auth()->user()->update( ['avatar' => $dest_path] );
        }

        auth()->user()->update( $request->all() );
        return back()->withStatus( __( 'Profile successfully updated.' ) );
    }

    /**
    * Change the password
    *
    * @param  \App\Http\Requests\PasswordRequest  $request
    * @return \Illuminate\Http\RedirectResponse
    */

    public function password( PasswordRequest $request ) {
        auth()->user()->update( ['password' => Hash::make( $request->get( 'password' ) )] );

        return back()->withStatusPassword( __( 'Password successfully updated.' ) );
    }
}
