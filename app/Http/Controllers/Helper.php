<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use PHPUnit\Framework\Attributes\IgnoreFunctionForCodeCoverage;

class Helper extends Controller
{
    public  static function getUserCookie(){

        $cookie = Cookie::get('__userAuth', '');

        if (empty($cookie)) {
            return false;
        }

        $user_data = User::where('id', '=', $cookie['id'])->first();

        if (empty($user_data)) {
            return false;
        }

        return $cookie;

    }

    // Validate order
    public static function ValidateOrder($order_id){
        $order = Orders::where('id', $order_id)->first();

        if (empty($order)) {
            return false;
        }

        return $order;
    }

    // Validate Payment Method
    public static function ValidatePaymentMethod($payment_method_id){
        $payment_method = PaymentMethod::where('id', $payment_method_id)->first();

        if (empty($payment_method)) {
            return false;
        }

        return $payment_method;
    }


}
