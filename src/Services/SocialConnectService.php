<?php

namespace Leafwrap\SocialConnects\Services;

use Exception;

class SocialConnectService extends BaseService
{
    public function __construct($gateway)
    {
        try {
            BaseService::$gateway = strtolower($gateway);
            $this->verifyCredentials();
        } catch (Exception $e) {
        }
    }

    public function connect()
    {
        if (!$service = $this->getSocialProvider()) {
        }

        $service->authRequest();
    }

    public function user($code)
    {
        if (!$service = $this->getSocialProvider()) {
        }

        $service->authResponse($code);
    }
}
