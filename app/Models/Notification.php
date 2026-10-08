<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_en',
        'title_bn',
        'short_description_en',
        'short_description_bn',
        'content_en',
        'content_bn',
        'image',
        'banner_image',
        'notification_type',
        'is_active',
        'publish_date',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'publish_date' => 'datetime',
    ];

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) return null;
        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }
        $path = ltrim($this->image, '/');
        if (str_starts_with($path, 'storage/')) {
            return asset($path);
        }
        return asset('storage/' . $path);
    }

    public function getBannerImageUrlAttribute(): ?string
    {
        if (!$this->banner_image) return null;
        if (str_starts_with($this->banner_image, 'http://') || str_starts_with($this->banner_image, 'https://')) {
            return $this->banner_image;
        }
        $path = ltrim($this->banner_image, '/');
        if (str_starts_with($path, 'storage/')) {
            return asset($path);
        }
        return asset('storage/' . $path);
    }

    public function logs()
    {
        return $table = $this->hasMany(NotificationLog::class);
    }
}
