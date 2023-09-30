<?php

namespace Leafwrap\SocialConnects\Services;

use Exception;
use Leafwrap\SocialConnects\Models\SocialGateway;
use Leafwrap\SocialConnects\Providers\Facebook;
use Leafwrap\SocialConnects\Providers\Google;
use Leafwrap\SocialConnects\Traits\Helper;

class BaseService
{
    use Helper;

    static string $gateway;
    static mixed $socialGateway;
    static array $socialFeedback;

    protected function verifyCredentials()
    {
        try {
            if (!BaseService::$socialGateway = SocialGateway::query()->where(['gateway' => self::$gateway])->first()) {
                return $this->leafwrapResponse(true, false, 'error', 404, 'Social gateway not found');
            }

            return $this->leafwrapResponse(false, true, 'success', 200, 'Social gateway found');
        } catch (Exception $e) {
            return $this->leafwrapResponse(true, false, 'serverError', 500, $e->getMessage());
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

    public function feedback(): array
    {
        return self::$socialFeedback;
    }

    public function setFeedback($data): void
    {
        self::$socialFeedback = $data;
    }
}
