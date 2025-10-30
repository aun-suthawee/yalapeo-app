<?php

namespace Modules\Sandbox\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExperimentResult extends Model
{
    protected $fillable = [
        'experiment_id',
        'scenario_id',
        'metric_name',
        'metric_type',
        'base_value',
        'predicted_value',
        'change_value',
        'change_percentage',
        'group_by',
        'group_value',
        'metadata',
        'metrics',
        'calculated_at',
    ];

    protected $casts = [
        'base_value' => 'decimal:2',
        'predicted_value' => 'decimal:2',
        'change_value' => 'decimal:2',
        'change_percentage' => 'decimal:2',
        'metadata' => 'array',
        'metrics' => 'array',
        'calculated_at' => 'datetime',
    ];

    /**
     * Get the experiment this result belongs to
     */
    public function experiment(): BelongsTo
    {
        return $this->belongsTo(Experiment::class);
    }

    /**
     * Get the scenario this result belongs to
     */
    public function scenario(): BelongsTo
    {
        return $this->belongsTo(ExperimentScenario::class, 'scenario_id');
    }

    /**
     * Scope: Filter by metric
     */
    public function scopeByMetric($query, string $metricName)
    {
        return $query->where('metric_name', $metricName);
    }

    /**
     * Scope: Filter by group
     */
    public function scopeByGroup($query, string $groupBy, string $groupValue)
    {
        return $query->where('group_by', $groupBy)
                    ->where('group_value', $groupValue);
    }

    /**
     * Get formatted change with sign
     */
    public function getFormattedChangeAttribute(): string
    {
        $sign = $this->change_value >= 0 ? '+' : '';
        return $sign . number_format($this->change_value, 2);
    }

    /**
     * Get formatted percentage with sign
     */
    public function getFormattedPercentageAttribute(): string
    {
        $sign = $this->change_percentage >= 0 ? '+' : '';
        return $sign . number_format($this->change_percentage, 2) . '%';
    }
}
