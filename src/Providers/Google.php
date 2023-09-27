<?php

namespace Leafwrap\SocialConnects\Providers;

use Exception;
use Leafwrap\SocialConnects\Contracts\ProviderContract;

class Google extends BaseProvider implements ProviderContract
{
    public function __construct($credentials)
    {
        $this->tokenizer($credentials);
        $this->urlGen();
    }

    public function tokenizer($credentials)
    {
        $this->credentials = [
            'appId'         => trim($credentials['client_id']),
            'appSecret'     => trim($credentials['client_secret']),
            'redirectUrl'   => trim($credentials['redirect_url'])
        ];
    }

    public function urlGen()
    {
        $this->urls = [
            'auth'      => 'https://accounts.google.com/o/oauth2/v2/auth',
            'token'     => 'https://oauth2.googleapis.com/token',
            'userInfo'  => 'https://www.googleapis.com/oauth2/v3/userinfo?access_token='
        ];
    }

    public function authRequest()
    {
        $url = $this->urls['auth'];
        $url .= '?scope=https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email';
        $url .= '&access_type=offline';
        $url .= '&include_granted_scopes=true';
        $url .= '&response_type=code';
        $url .= '&state=state_parameter_passthrough_value';
        $url .= "&redirect_uri={$this->credentials['redirectUrl']}";
        $url .= "&client_id={$this->credentials['appId']}";
        return $url;
    }

    public function authResponse($data)
    {
        try {
            $url = $this->urls['token'];
            $url .= "?code={$data}";
            $url .= "&client_id={$this->credentials['appId']}";
            $url .= "&client_secret={$this->credentials['appSecret']}";
            $url .= "&redirect_uri={$this->credentials['redirectUrl']}";
            $url .= "&grant_type=authorization_code";

            $client = Http::withHeaders(['Content-Type' => 'application/x-www-form-urlencoded'])->asForm()->post($url);
            $payload = $client->json();

            $this->getUserInfo($payload['access_token']);
        } catch (Exception $e) {
        }
    }

    public function getUserInfo($key)
    {
        try {
            $client = Http::get("{$this->urls['userInfo']}={$key}");
        } catch (Exception $e) {
        }
    }
}
