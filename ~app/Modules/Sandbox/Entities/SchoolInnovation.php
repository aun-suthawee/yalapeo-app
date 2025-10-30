<?php

namespace Modules\Sandbox\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolInnovation extends Model
{
    protected $fillable = [
        'school_id',
        'title',
        'description',
        'image_path',
        'category',
        'year',
        'is_active'
    ];

    protected $casts = [
        'year' => 'integer',
        'is_active' => 'boolean',
        // Don't cast image_path to array here - we'll handle it in accessor
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['image_paths'];

    /**
     * Get image paths as array
     * Handles both single string and JSON array format
     */
    public function getImagePathsAttribute()
    {
        $imagePath = $this->attributes['image_path'] ?? null;
        
        if (empty($imagePath)) {
            return [];
        }

        // If it's a JSON string, decode it
        if (is_string($imagePath) && (str_starts_with($imagePath, '[') || str_starts_with($imagePath, '{'))) {
            $decoded = json_decode($imagePath, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        // Single path as string
        return [$imagePath];
    }

    /**
     * Get first image path for backward compatibility
     */
    public function getFirstImagePathAttribute()
    {
        $paths = $this->image_paths;
        return !empty($paths) ? $paths[0] : null;
    }

    /**
     * Get the school that owns this innovation
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Scope to get only active innovations
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope to filter by year
     */
    public function scopeByYear($query, $year)
    {
        return $query->where('year', $year);
    }
}