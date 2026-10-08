<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'fcm_token',
        'language',
        'platform',
        'device_name',
        'app_version',
        'last_seen',
    ];

    protected $casts = [
        'last_seen' => 'datetime',
    ];
}
