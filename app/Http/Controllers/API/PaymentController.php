<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Models\User;
use App\Models\UserMeta;
use App\Models\AppSetting;
use App\Models\Transaction;
use App\Models\Boost;
use Stripe;

class PaymentController extends Controller {
    //

    public function config() {
        $data = array();
        $success = true;
        $message = '';

        $stripe_pk = AppSetting::where( 'meta_key', 'stripe_pk' )->value( 'meta_value' );
        $google_merchantId = AppSetting::where( 'meta_key', 'google_merchantId' )->value( 'meta_value' );
        $apple_merchantId = AppSetting::where( 'meta_key', 'apple_merchantId' )->value( 'meta_value' );
        $data['stripe_pk'] = $stripe_pk;
        $data['google_merchantId'] = $google_merchantId;
        $data['apple_merchantId'] = $apple_merchantId;

        return $response = array( 'success' => $success, 'data' => $data, 'message' => $message );
    }

    public function checkout( Request $request ) {
        $data = array();
        $success = false;
        $message = '';

        $card = $request->card;
        $stripeToken = $request->stripeToken;

        try {
            $customer_id = Auth::user()->customer_id;
            if ( $customer_id == null || $customer_id == '' ) {
                $customer_id = $this->createCustomer( $stripeToken, Auth::user()->email );
                User::where( 'id', Auth::user()->id )->update( ['customer_id' => $customer_id] );
            }

            $description = '';
            if ( $card['checkout_type'] == 0 ) {
                $description = 'Your Local Pet Marketplace | Fetch <=====> Subscription';
            } else if ( $card['checkout_type'] == 1 ) {
                $description = 'Your Local Pet Marketplace | Fetch <=====> Boost Ads';
            }

            $res = $this->stripeCharge( floatval( $card['amount'] ), $card['currency'], $description, $customer_id );
            if ( !$res || $res['status'] != 'succeeded' ) {
                $success = false;
                return $response = array( 'success' => $success, 'data' => '', 'message' => $message );
            }
        } catch ( \Throwable $th ) {
            $success = false;
            return $response = array( 'success' => $success, 'data' => '', 'message' => $message );
        }
        $success = true;

        if ( $card['checkout_type'] == 0 ) {
            $transaction = new Transaction;
        } else if ( $card['checkout_type'] == 1 ) {
            $nowDate = now();

            $boost = new Boost;
            $boost->id_ads = $request->ad_id;
            $boost->type = $card['type'];
            $boost->started_at = $nowDate;

            if ( $card['type'] == 0 ) {
                $nowDate->addDays( 1 );
            } else if ( $card['type'] == 1 ) {
                $nowDate->addWeeks( 1 );
            } else if ( $card['type'] == 2 ) {
                $nowDate->addMonths( 1 );
            }

            $boost->expired_at = $nowDate;
            $boost->save();

            $transaction = new Transaction;
            $transaction->id_customer = $customer_id;
            $transaction->type = 1;
            $transaction->id_type = $boost->id;
            $transaction->description = $description;
            $transaction->save();
        }
        return $response = array( 'success' => $success, 'data' => '', 'message' => $message );
    }

    public function createCustomer( $token, $email ) {
        $stripe_sk = AppSetting::where( 'meta_key', 'stripe_sk' )->value( 'meta_value' );
        Stripe\Stripe::setApiKey( $stripe_sk );
        return Stripe\Customer::create( [
            'email' => $email,
            'source' => $token,
        ] )->id;
    }

    public function stripeCharge( $amount, $currency, $description, $customer_id ) {
        $stripe_sk = AppSetting::where( 'meta_key', 'stripe_sk' )->value( 'meta_value' );
        Stripe\Stripe::setApiKey( $stripe_sk );
        return Stripe\Charge::create ( [
            'amount' => $amount * 100,
            'currency' => $currency,
            'description' => $description,
            'customer' => $customer_id,
        ] );
    }
}