<?php

namespace Modules\Sandbox\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Modules\User\Entities\User;

class Experiment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'type',
        'created_by',
        'base_year',
        'base_data_snapshot',
        'baseline_filters',
        'settings',
        'is_public',
        'share_token',
        'status',
        'completed_at',
    ];

    protected $casts = [
        'base_data_snapshot' => 'array',
        'baseline_filters' => 'array',
        'settings' => 'array',
        'is_public' => 'boolean',
        'completed_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($experiment) {
            if (!$experiment->share_token) {
                $experiment->share_token = Str::random(32);
            }
        });
    }

    /**
     * Get the user who created this experiment
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all scenarios for this experiment
     */
    public function scenarios(): HasMany
    {
        return $this->hasMany(ExperimentScenario::class)->orderBy('order');
    }

    /**
     * Get all results for this experiment
     */
    public function results(): HasMany
    {
        return $this->hasMany(ExperimentResult::class);
    }

    /**
     * Scope: Only public experiments
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope: Experiments by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: User's experiments
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('created_by', $userId);
    }

    /**
     * Check if experiment is editable
     */
    public function isEditable(): bool
    {
        return in_array($this->status, ['draft', 'running', 'completed']);
    }

    /**
     * Generate shareable link
     */
    public function getShareLinkAttribute(): string
    {
        return route('sandbox.experiments.share', $this->share_token);
    }
}
