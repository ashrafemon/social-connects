<?php

return [
    'frontend_url' => env('SOCIAL_CONNECT_FRONTEND_URL', ((app()->runningInConsole() === false) ? request()?->getSchemeAndHttpHost() : env('APP_URL'))),
    'login_url'    => env('SOCIAL_CONNECT_LOGIN_URL', '/login'),
];
