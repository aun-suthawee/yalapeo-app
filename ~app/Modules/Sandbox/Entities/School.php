<?php

namespace Modules\Sandbox\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class School extends Model
{
    protected $fillable = [
        'school_code',
        'name',
        'department',
        'ministry_affiliation',
        'bureau_affiliation',
        'education_area',
        'school_type',
        'male_students',
        'female_students', 
        'male_teachers',
        'female_teachers',
        'address',
        'subdistrict',
        'district',
        'phone',
        'email',
        'latitude',
        'longitude'
    ];

    protected $casts = [
        'male_students' => 'integer',
        'female_students' => 'integer',
        'male_teachers' => 'integer',
        'female_teachers' => 'integer',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    protected $appends = [
        'total_students',
        'total_teachers',
        'active_innovations_count'
    ];

    /**
     * Get all innovations for this school
     */
    public function innovations(): HasMany
    {
        return $this->hasMany(SchoolInnovation::class);
    }

    /**
     * Get all vision videos for this school
     */
    public function visionVideos(): HasMany
    {
        return $this->hasMany(SchoolVisionVideo::class);
    }

    /**
     * Get all galleries for this school
     */
    public function galleries(): HasMany
    {
        return $this->hasMany(SchoolGallery::class);
    }

    /**
     * Get all academic results for this school
     */
    public function academicResults(): HasMany
    {
        return $this->hasMany(AcademicResult::class);
    }

    /**
     * Get academic result for specific year
     */
    public function getAcademicResultForYear(int $year): ?AcademicResult
    {
        return $this->academicResults()->forYear($year)->first();
    }

    /**
     * Check if school has submitted results for a specific year
     */
    public function hasSubmittedResultsForYear(int $year): bool
    {
        $result = $this->getAcademicResultForYear($year);
        return $result && $result->submitted_at !== null;
    }

    /**
     * Get total students count
     */
    public function getTotalStudentsAttribute(): int
    {
        return $this->male_students + $this->female_students;
    }

    /**
     * Get total teachers count
     */
    public function getTotalTeachersAttribute(): int
    {
        return $this->male_teachers + $this->female_teachers;
    }

    /**
     * Get active innovations count
     */
    public function getActiveInnovationsCountAttribute(): int
    {
        return $this->innovations()->where('is_active', true)->count();
    }
}