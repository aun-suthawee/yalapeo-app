<?php

namespace Modules\Sandbox\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolVisionVideo extends Model
{
    protected $fillable = [
        'school_id',
        'video_url',
        'video_type',
        'title',
        'description',
        'is_active',
        'order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Get the school that owns this vision video
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get embed URL for the video based on video type
     */
    public function getEmbedUrlAttribute(): string
    {
        return $this->parseVideoUrl($this->video_url, $this->video_type);
    }

    /**
     * Parse video URL to embed URL
     */
    private function parseVideoUrl(string $url, string $type): string
    {
        switch ($type) {
            case 'youtube':
                return $this->parseYoutubeUrl($url);
            case 'facebook':
                return $this->parseFacebookUrl($url);
            case 'tiktok':
                return $this->parseTiktokUrl($url);
            case 'google_drive':
                return $this->parseGoogleDriveUrl($url);
            default:
                return $url;
        }
    }

    /**
     * Parse YouTube URL to embed URL
     */
    private function parseYoutubeUrl(string $url): string
    {
        // Handle youtu.be short URLs
        if (strpos($url, 'youtu.be/') !== false) {
            preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $url, $matches);
            if (isset($matches[1])) {
                return 'https://www.youtube.com/embed/' . $matches[1];
            }
        }
        
        // Handle youtube.com/watch URLs
        if (strpos($url, 'youtube.com/watch') !== false) {
            preg_match('/[?&]v=([a-zA-Z0-9_-]+)/', $url, $matches);
            if (isset($matches[1])) {
                return 'https://www.youtube.com/embed/' . $matches[1];
            }
        }
        
        // Handle youtube.com/embed URLs (already embed format)
        if (strpos($url, 'youtube.com/embed/') !== false) {
            return $url;
        }
        
        // Handle youtube.com/shorts URLs
        if (strpos($url, 'youtube.com/shorts/') !== false) {
            preg_match('/shorts\/([a-zA-Z0-9_-]+)/', $url, $matches);
            if (isset($matches[1])) {
                return 'https://www.youtube.com/embed/' . $matches[1];
            }
        }
        
        return $url;
    }

    /**
     * Parse Google Drive URL to embeddable preview URL
     */
    private function parseGoogleDriveUrl(string $url): string
    {
        $patterns = [
            '/\/file\/d\/([a-zA-Z0-9_-]+)/',
            '/[?&]id=([a-zA-Z0-9_-]+)/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches) && isset($matches[1])) {
                return 'https://drive.google.com/file/d/' . $matches[1] . '/preview';
            }
        }

        return $url;
    }

    /**
     * Parse Facebook URL to embed URL
     */
    private function parseFacebookUrl(string $url): string
    {
        // Facebook video embed uses plugin
        $encodedUrl = urlencode($url);
        return "https://www.facebook.com/plugins/video.php?href={$encodedUrl}&show_text=false&width=734";
    }

    /**
     * Parse TikTok URL to embed URL
     */
    private function parseTiktokUrl(string $url): string
    {
        // TikTok embed format
        if (strpos($url, 'tiktok.com') !== false) {
            // Extract video ID if possible
            preg_match('/\/video\/(\d+)/', $url, $matches);
            if (isset($matches[1])) {
                return "https://www.tiktok.com/embed/v2/{$matches[1]}";
            }
        }
        
        return $url;
    }

    /**
     * Scope to get only active videos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by custom order field
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('created_at', 'desc');
    }
}
