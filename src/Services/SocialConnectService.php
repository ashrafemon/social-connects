<?php

namespace Leafwrap\SocialConnects\Services;

use Exception;

class SocialConnectService extends BaseService
{
    public function __construct($gateway)
    {
        try {
            BaseService::$gateway = strtolower($gateway);
            $this->setFeedback($this->verifyCredentials());
        } catch (Exception $e) {
        }
    }

    public function connect()
    {
        if (BaseService::$socialFeedback['isError']) {
            return;
        }

        if (!$service = $this->getSocialProvider()) {
            $this->setFeedback($this->leafwrapResponse(true, false, 'error', 404, 'Social gateway not found'));
            return;
        }

        $this->setFeedback($service->authRequest());
    }

    public function user($code)
    {
        if (BaseService::$socialFeedback['isError']) {
            return;
        }

        if (!$service = $this->getSocialProvider()) {
            $this->setFeedback($this->leafwrapResponse(true, false, 'error', 404, 'Social gateway not found'));
            return;
        }

        $this->setFeedback($service->authResponse($code));
    }
}
