<?php

namespace Leafwrap\SocialConnects;

use Leafwrap\SocialConnects\Services\SocialConnectService;

class SocialConnect
{
    public function connect($gateway): void
    {
        $service = new SocialConnectService($gateway);
        $service->connect();
    }

    public function user($gateway, $code): void
    {
        $service = new SocialConnectService($gateway);
        $service->user($code);
    }
}
