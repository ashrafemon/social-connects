<?php

namespace Leafwrap\SocialConnects\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SocialConnectTracker extends Model
{
    protected $fillable = ['unique_id', 'gateway', 'code', 'response'];

    protected $casts = ['response' => 'array'];

    public static function boot()
    {
        parent::boot();
        self::created(function ($tracker) {
            $tracker->unique_id = Str::random();
        });
    }

}
