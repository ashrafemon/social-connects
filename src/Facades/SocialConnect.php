<?php

namespace Leafwrap\SocialConnects\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Leafwrap\SocialConnects\SocialConnect connect(string $gateway)
 * @method static \Leafwrap\SocialConnects\SocialConnect user(string $gateway, string $code)
 */
class SocialConnect extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'SocialConnect';
    }
}
