<?php

namespace Leafwrap\SocialConnects;

use Leafwrap\SocialConnects\Services\SocialConnectService;

class SocialConnect
{
    public function connect($gateway)
    {
        $service = new SocialConnectService($gateway);
        $service->connect();
        return $service->feedback();
    }

    public function user($gateway, $code)
    {
        $service = new SocialConnectService($gateway);
        $service->user($code);
        return $service->feedback();
    }
}
