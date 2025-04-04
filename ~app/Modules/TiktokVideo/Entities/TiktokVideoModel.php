<?php

namespace Modules\TiktokVideo\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TiktokVideoModel extends Model
{
    protected $table = 'tiktok_videos'; 
    
    protected $fillable = [
        'title',
        'url',
        'video_id',
        'detail',
        'view',
        'is_active',
        'slug'
    ];

    // Auto-generate slug from title
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($tiktokVideo) {
            if (empty($tiktokVideo->slug)) {
                $tiktokVideo->slug = Str::slug($tiktokVideo->title);
            }
        });
    }

    // Extract video ID from TikTok URL if not provided
    public function setUrlAttribute($value)
    {
        $this->attributes['url'] = $value;
        
        // Auto-extract video ID if not already set
        if (empty($this->video_id)) {
            $pattern = '/(?:https?:\/\/)?(?:www\.)?(?:tiktok\.com\/@[\w.-]+\/video\/)([\d]+)/';
            preg_match($pattern, $value, $matches);
            if (isset($matches[1])) {
                $this->attributes['video_id'] = $matches[1];
            }
        }
    }
}
