<?php

namespace Modules\Sandbox\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Publication extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'pdf_path',
        'original_filename',
        'file_size',
        'display_order',
    ];

    protected $casts = [
        'display_order' => 'integer',
        'file_size' => 'integer',
    ];

    /**
     * Scope a query to order publications by display order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc')
                     ->orderBy('created_at', 'desc');
    }

    /**
     * Get the full URL for the PDF file.
     */
    public function getPdfUrlAttribute()
    {
        return asset('storage/publications/' . $this->pdf_path);
    }

    /**
     * Get the filename from the path.
     */
    public function getPdfFilenameAttribute()
    {
        return $this->original_filename ?? basename($this->pdf_path);
    }

    /**
     * Get formatted file size.
     */
    public function getFormattedFileSizeAttribute()
    {
        $bytes = $this->file_size;
        
        if ($bytes === 0) return '0 Bytes';
        
        $k = 1024;
        $sizes = ['Bytes', 'KB', 'MB', 'GB'];
        $i = floor(log($bytes) / log($k));
        
        return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
    }
}
