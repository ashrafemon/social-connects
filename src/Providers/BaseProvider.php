<?php

namespace Leafwrap\SocialConnects\Providers;

class BaseProvider
{
    protected array $credentials = [];
    protected array $urls        = [];

    protected function userInfo($data, $gateway)
    {
        $payload = ['gateway' => $gateway];

        if ($gateway === 'google') {
            $payload = array_merge($payload, [
                'id'      => $data['sub'] ?? '',
                'name'    => $data['name'] ?? '',
                'email'   => $data['email'] ?? '',
                'picture' => $data['picture'] ?? '',
            ]);
        } elseif ($gateway === 'facebook') {
            $payload = array_merge($payload, [
                'id'      => $data['id'] ?? '',
                'name'    => $data['name'] ?? '',
                'email'   => $data['email'] ?? '',
                'picture' => $data['picture']['data']['url'] ?? '',
            ]);
        }
        return $payload;
    }
}
