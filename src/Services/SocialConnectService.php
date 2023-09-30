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
        if (!BaseService::$socialFeedback['isError']) {
            return;
        }

        if (!$service = $this->getSocialProvider()) {
            return $this->leafwrapResponse(true, false, 'error', 404, 'Social gateway not found');
        }

        $service->authRequest();
    }

    public function user($code)
    {
        if (!BaseService::$socialFeedback['isError']) {
            return;
        }

        if (!$service = $this->getSocialProvider()) {
            return $this->leafwrapResponse(true, false, 'error', 404, 'Social gateway not found');
        }

        $service->authResponse($code);
    }
}
