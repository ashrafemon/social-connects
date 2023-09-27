<?php

namespace Leafwrap\SocialConnects\Models;

use Illuminate\Database\Eloquent\Model;

class SocialGateway extends Model
{
    protected $fillable = ['gateway', 'credentials', 'additional', 'status'];

    protected $casts = ['credentials' => 'array', 'additional' => 'array'];
}
