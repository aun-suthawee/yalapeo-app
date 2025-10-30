<?php

namespace Modules\Sandbox\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SchoolGallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'title',
        'description',
        'drive_folder_id',
        'drive_folder_url',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    /**
     * Get the school that owns the gallery
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Extract folder ID from Google Drive URL
     */
    public static function extractFolderId($url)
    {
        // Pattern: https://drive.google.com/drive/folders/FOLDER_ID or FOLDER_ID?usp=sharing
        if (preg_match('/\/folders\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return $matches[1];
        }
        
        // If already just the ID
        if (preg_match('/^[a-zA-Z0-9_-]+$/', $url)) {
            return $url;
        }
        
        return null;
    }

    /**
     * Get embed URL for iframe
     */
    public function getEmbedUrlAttribute()
    {
        return "https://drive.google.com/embeddedfolderview?id={$this->drive_folder_id}#grid";
    }

    /**
     * Get full folder URL
     */
    public function getFolderUrlAttribute()
    {
        if ($this->drive_folder_url) {
            return $this->drive_folder_url;
        }
        return "https://drive.google.com/drive/folders/{$this->drive_folder_id}";
    }

    /**
     * Scope to get only active galleries
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by display order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc')->orderBy('created_at', 'desc');
    }
}
