<?php

namespace Modules\Sandbox\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcademicResult extends Model
{
    protected $fillable = [
        'school_id',
        'academic_year',
        // Test availability flags
        'has_nt_test',
        'has_rt_test',
        'has_onet_test',
        // NT
        'nt_math_score',
        'nt_thai_score',
        // RT
        'rt_reading_score',
        'rt_comprehension_score',
        // O-NET
        'onet_math_score',
        'onet_thai_score',
        'onet_english_score',
        'onet_science_score',
        // Metadata
        'notes',
        'submitted_at'
    ];

    protected $casts = [
        'academic_year' => 'integer',
        'has_nt_test' => 'boolean',
        'has_rt_test' => 'boolean',
        'has_onet_test' => 'boolean',
        'nt_math_score' => 'decimal:2',
        'nt_thai_score' => 'decimal:2',
        'rt_reading_score' => 'decimal:2',
        'rt_comprehension_score' => 'decimal:2',
        'onet_math_score' => 'decimal:2',
        'onet_thai_score' => 'decimal:2',
        'onet_english_score' => 'decimal:2',
        'onet_science_score' => 'decimal:2',
        'submitted_at' => 'datetime',
    ];

    /**
     * Get the school that owns the academic result
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Check if NT scores are submitted
     */
    public function hasNtScores(): bool
    {
        // If school doesn't have NT test, return true (considered as "submitted" - N/A)
        if (!$this->has_nt_test) {
            return true;
        }
        
        return !is_null($this->nt_math_score) || !is_null($this->nt_thai_score);
    }

    /**
     * Check if RT scores are submitted
     */
    public function hasRtScores(): bool
    {
        // If school doesn't have RT test, return true (considered as "submitted" - N/A)
        if (!$this->has_rt_test) {
            return true;
        }
        
        return !is_null($this->rt_reading_score) || !is_null($this->rt_comprehension_score);
    }

    /**
     * Check if O-NET scores are submitted
     */
    public function hasOnetScores(): bool
    {
        // If school doesn't have O-NET test, return true (considered as "submitted" - N/A)
        if (!$this->has_onet_test) {
            return true;
        }
        
        return !is_null($this->onet_math_score) || 
               !is_null($this->onet_thai_score) || 
               !is_null($this->onet_english_score) || 
               !is_null($this->onet_science_score);
    }

    /**
     * Check if any scores are submitted (only for tests that are applicable)
     */
    public function hasAnyScores(): bool
    {
        return $this->hasNtScores() || $this->hasRtScores() || $this->hasOnetScores();
    }
    
    /**
     * Check if all required test scores are submitted
     */
    public function isComplete(): bool
    {
        // NT is complete if: no NT test OR has NT scores
        $ntComplete = !$this->has_nt_test || $this->hasNtScores();
        
        // RT is complete if: no RT test OR has RT scores
        $rtComplete = !$this->has_rt_test || $this->hasRtScores();
        
        // O-NET is complete if: no O-NET test OR has O-NET scores
        $onetComplete = !$this->has_onet_test || $this->hasOnetScores();
        
        return $ntComplete && $rtComplete && $onetComplete;
    }

    /**
     * Get NT average score
     */
    public function getNtAverageAttribute(): ?float
    {
        $scores = array_filter([$this->nt_math_score, $this->nt_thai_score]);
        return count($scores) > 0 ? round(array_sum($scores) / count($scores), 2) : null;
    }

    /**
     * Get RT average score
     */
    public function getRtAverageAttribute(): ?float
    {
        $scores = array_filter([$this->rt_reading_score, $this->rt_comprehension_score]);
        return count($scores) > 0 ? round(array_sum($scores) / count($scores), 2) : null;
    }

    /**
     * Get O-NET average score
     */
    public function getOnetAverageAttribute(): ?float
    {
        $scores = array_filter([
            $this->onet_math_score,
            $this->onet_thai_score,
            $this->onet_english_score,
            $this->onet_science_score
        ]);
        return count($scores) > 0 ? round(array_sum($scores) / count($scores), 2) : null;
    }

    /**
     * Scope: Filter by academic year
     */
    public function scopeForYear($query, $year)
    {
        return $query->where('academic_year', $year);
    }

    /**
     * Scope: Only submitted results
     */
    public function scopeSubmitted($query)
    {
        return $query->whereNotNull('submitted_at');
    }

    /**
     * Scope: Not submitted results
     */
    public function scopeNotSubmitted($query)
    {
        return $query->whereNull('submitted_at');
    }
}
