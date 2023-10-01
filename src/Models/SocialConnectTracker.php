<?php

namespace Leafwrap\SocialConnects\Models;

use Illuminate\Database\Eloquent\Model;

class SocialConnectTracker extends Model
{
    protected $fillable = ['unique_id', 'gateway', 'code', 'response'];

    protected $casts = ['response' => 'array'];
}
