<?php

namespace Leafwrap\SocialConnects\Providers;

use Exception;
use Illuminate\Support\Facades\Http;
use Leafwrap\SocialConnects\Contracts\ProviderContract;
use Leafwrap\SocialConnects\Traits\Helper;

class Google extends BaseProvider implements ProviderContract
{
    use Helper;

    public function __construct($credentials)
    {
        $this->tokenizer($credentials);
        $this->urlGen();
    }

    public function tokenizer($credentials)
    {
        $this->credentials = [
            'appId'       => trim($credentials['client_id']),
            'appSecret'   => trim($credentials['client_secret']),
            'redirectUrl' => trim($credentials['redirect_url']),
        ];
    }

    public function urlGen()
    {
        $this->urls = [
            'auth'     => 'https://accounts.google.com/o/oauth2/v2/auth',
            'token'    => 'https://oauth2.googleapis.com/token',
            'userInfo' => 'https://www.googleapis.com/oauth2/v3/userinfo',
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

        return $this->leafwrapResponse(false, true, 'success', 200, 'Please redirect to this link', $url);
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
            if (!$client->successful()) {
                return $this->leafwrapResponse(true, false, 'error', 400, 'Something went wrong', $client->json());
            }

            $payload = $client->json();
            return $this->getUserInfo($payload['access_token']);
        } catch (Exception $e) {
            $this->leafwrapResponse(true, false, 'serverError', 400, $e->getMessage());
        }
    }

    public function getUserInfo($key)
    {
        try {
            $client = Http::get("{$this->urls['userInfo']}?access_token={$key}");

            if (!$client->successful()) {
                return $this->leafwrapResponse(true, false, 'error', 400, 'Something went wrong', $client->json());
            }

            return $this->leafwrapResponse(false, true, 'success', 200, 'User information fetch successfully', $this->userInfo($client->json(), 'google'));
        } catch (Exception $e) {
            return $this->leafwrapResponse(true, false, 'serverError', 400, $e->getMessage());
        }
    }
}
