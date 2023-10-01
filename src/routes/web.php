<?php

use Illuminate\Support\Facades\Route;
use Leafwrap\SocialConnects\Http\Controllers\SocialConnectController;
use Leafwrap\SocialConnects\Http\Controllers\SocialGatewayController;

Route::middleware('api')->prefix('api/v1')->group(function () {
    Route::apiResource('social-gateways', SocialGatewayController::class)->except(['create', 'edit']);

    Route::prefix('social-connects')->group(function () {
        Route::post('auth-request', [SocialConnectController::class, 'socialCall']);
    });
});

Route::get('social-connects/redirect', [SocialConnectController::class, 'socialCallback']);
