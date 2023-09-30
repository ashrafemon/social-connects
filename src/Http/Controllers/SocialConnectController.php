<?php

namespace Leafwrap\SocialConnects\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Leafwrap\SocialConnects\Facades\SocialConnect;
use Leafwrap\SocialConnects\Traits\Helper;

class SocialConnectController extends Controller
{
    use Helper;

    public function socialCall()
    {
        try {
            $gateway = request()->input('gateway');

            $res = SocialConnect::connect($gateway);

            if ($res['isError']) {
                return $this->leafwrapMessage($res['message'], $res['statusCode'], $res['status']);
            }

            return $this->leafwrapEntity($res['data'], $res['statusCode'], $res['status'], $res['message']);
        } catch (Exception $e) {
            return $this->leafwrapServerError($e);
        }
    }

    public function socialUser()
    {
        try {
            $gateway = request()->input('gateway') ?? '';
            $code    = request()->input('code') ?? '';

            $res = SocialConnect::user($gateway, $code);

            if ($res['isError']) {
                return $this->leafwrapMessage($res['message'], $res['statusCode'], $res['status']);
            }

            return $this->leafwrapEntity($res['data'], $res['statusCode'], $res['status'], $res['message']);
        } catch (Exception $e) {
            return $this->leafwrapServerError($e);
        }
    }
}
