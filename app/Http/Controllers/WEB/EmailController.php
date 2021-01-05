<?php

namespace App\Http\Controllers\WEB;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Email;
use App\Models\User;
use App\Models\SendedMail;

class EmailController extends Controller {
    //

    public function sendMail( $email, $type, $value ) {
        $title = Email::where( 'type', $type )->value( 'title' );
        $content = Email::where( 'type', $type )->value( 'content' );

        if($type == 0){
            $content = str_replace("{password}", $value, $content);
        }else if($type == 3){
            $content = str_replace("{verify_code}", $value, $content);
        }else if($type == 5){
            $content = str_replace("{status}", $value, $content);
        }

        $this->sendBasicMail( $email, $title, $content );
    }

    private function sendBasicMail( $email, $subject, $message ) {
        Mail::to( $email )->send( new SendMail( $subject, $message ) );
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function index() {
        //
        return view( 'email.index', ['default_mail' => ( new Email )->get(), 'sended_mail' => ( new SendedMail )->orderby( 'created_at', 'desc' )->get()] );
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function create() {
        //
        return view( 'email.create', ['users' => ( new User )::where( ['role' => 0, 'active' => 1] )->where( 'is_social', '!=', -1 )->get()] );
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */

    public function store( Request $request ) {
        //
        $user = new User;
        foreach ( $request->email as $value ) {
            $email = $user->where( 'id', $value )->value( 'email' );
            $this->sendBasicMail( $email, $request->title, $request->content );
            $model = new SendedMail;
            $model->create( $request->merge( ['userid'=>$value] )->all() );
        }
        return redirect()->route( 'email.index' )->withStatus( __( 'Email successfully sended.' ) );
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

    public function edit( Email $email ) {
        return view( 'email.edit', compact( 'email' ) );
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function update( Request $request, $id ) {
        ( new Email )->where( 'id', $id )->update( ['title'=>$request->title, 'content' => $request->content] );
        return redirect()->route( 'email.index' )->withStatus( __( 'Email successfully updated.' ) );
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function destroy( $id ) {
        //
        $model = new SendedMail;
        $model->where( 'id', $id )->delete();
        return back()->withStatus( __( 'Email successfully deleted.' ) );
    }
}