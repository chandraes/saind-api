<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Vendor extends Model
{
     use HasApiTokens;

    protected $fillable = ['name', 'allowed_ips', 'bypass_ip_whitelist', 'is_active', 'saind_id'];

    protected $casts = [
        'allowed_ips'         => 'array',
        'bypass_ip_whitelist' => 'boolean',
        'is_active'           => 'boolean',
    ];
}
