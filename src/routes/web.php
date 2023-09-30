<?php

use Illuminate\Support\Facades\Route;
use Leafwrap\SocialConnects\Http\Controllers\SocialConnectController;
use Leafwrap\SocialConnects\Http\Controllers\SocialGatewayController;

Route::middleware('api')->prefix('api/v1')->group(function () {
    Route::apiResource('social-gateways', SocialGatewayController::class)->except(['create', 'edit']);

    Route::prefix('social-connects')->group(function () {
        Route::post('auth-request', [SocialConnectController::class, 'socialCall']);
        Route::post('auth-user', [SocialConnectController::class, 'socialUser']);
    });
});

Route::view('social-connects/redirect', 'social-connects::pages.social-connect-redirect');
