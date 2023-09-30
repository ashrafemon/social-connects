<?php

namespace Leafwrap\SocialConnects\Providers;

use Exception;
use Leafwrap\SocialConnects\Contracts\ProviderContract;
use Leafwrap\SocialConnects\Traits\Helper;

class Facebook extends BaseProvider implements ProviderContract
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
            'auth'     => 'https://www.facebook.com/v18.0/dialog/oauth',
            'token'    => 'https://graph.facebook.com/v18.0/oauth/access_token',
            'userInfo' => 'https://graph.facebook.com/me',
        ];
    }

    public function authRequest()
    {
        $url = $this->urls['auth'];
        $url .= "?client_id={$this->credentials['appId']}";
        $url .= "&redirect_uri={$this->credentials['redirectUrl']}";
        $url .= "&scope=email";
        $url .= "&state={st=state123abc,ds=123456789}";

        $this->leafwrapResponse(false, true, 'success', 200, 'Please redirect to this link', $url);
    }

    public function authResponse($data)
    {
        try {
            $url = $this->urls['token'];
            $url .= "?client_id={$this->credentials['appId']}";
            $url .= "?client_secret={$this->credentials['appSecret']}";
            $url .= "&redirect_uri={$this->credentials['redirectUrl']}";
            $url .= "&code={$data}";

            $client = Http::get($url);

            if (!$client->successful) {
                $this->leafwrapResponse(true, false, 'error', 400, 'Something went wrong', $client->json());
                return;
            }

            $payload = $client->json();
            $this->getUserInfo($payload['access_token']);
        } catch (Exception $e) {
            $this->leafwrapResponse(true, false, 'serverError', 400, $e->getMessage());
        }
    }

    public function getUserInfo($key)
    {
        try {
            $url = $this->urls['userInfo'];
            $url .= '?fields=id,name,email,picture';
            $url .= "&client_id={$this->credentials['appId']}";
            $url .= "&access_token={$key}";

            $client = Http::get($url);

            if (!$client->successful) {
                $this->leafwrapResponse(true, false, 'error', 400, 'Something went wrong', $client->json());
                return;
            }

            $this->leafwrapResponse(false, true, 'success', 200, 'User information fetch successfully', $client->json());
        } catch (Exception $e) {
            $this->leafwrapResponse(true, false, 'serverError', 400, $e->getMessage());
        }
    }
}
