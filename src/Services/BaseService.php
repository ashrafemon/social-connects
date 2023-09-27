<?php

namespace Leafwrap\SocialConnects\Services;

use Exception;
use Leafwrap\SocialConnects\Models\SocialGateway;
use Leafwrap\SocialConnects\Providers\Facebook;
use Leafwrap\SocialConnects\Providers\Google;

class BaseService
{
    static string $gateway;
    static mixed $socialGateway;

    protected function verifyCredentials()
    {
        try {
            if (!BaseService::$socialGateway = SocialGateway::query()->where(['gateway' => self::$gateway])->first()) {
            }
        } catch (Exception $e) {
        }
    }

    protected function getSocialProvider()
    {
        return match (BaseService::$gateway) {
            'facebook' => new Facebook(self::$socialGateway['credentials']),
            'google' => new Google(self::$socialGateway['credentials']),
            default => null
        };
    }
}
