<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class Helper extends Controller
{
    public  static function getUserCookie(){

        $cookie = Cookie::get('__userAuth', '');

        if (empty($cookie)) {
            return null;
        }

        return $cookie;

    }

    public static function itemChecker($order_id, $user_id)
    {
        $order = Orders::where('id', $order_id)->where('user_id', $user_id)->first();

        if (empty($order)) {
            return false;
        }

        return $order;
    }
}
