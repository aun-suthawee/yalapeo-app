<?php

namespace Modules\Sandbox\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExperimentScenario extends Model
{
    protected $fillable = [
        'experiment_id',
        'name',
        'description',
        'order',
        'parameters',
        'changes',
        'results',
        'calculated_at',
    ];

    protected $casts = [
        'parameters' => 'array',
        'changes' => 'array',
        'results' => 'array',
        'calculated_at' => 'datetime',
    ];

    /**
     * Get the experiment this scenario belongs to
     */
    public function experiment(): BelongsTo
    {
        return $this->belongsTo(Experiment::class);
    }

    /**
     * Get all results for this scenario
     */
    public function scenarioResults(): HasMany
    {
        return $this->hasMany(ExperimentResult::class, 'scenario_id');
    }

    /**
     * Get all results for this scenario (alias for scenarioResults)
     */
    public function results(): HasMany
    {
        return $this->hasMany(ExperimentResult::class, 'scenario_id');
    }

    /**
     * Check if scenario has been calculated
     */
    public function isCalculated(): bool
    {
        return !is_null($this->calculated_at);
    }

    /**
     * Get parameter value
     */
    public function getParameter(string $key, $default = null)
    {
        return data_get($this->parameters, $key, $default);
    }

    /**
     * Set parameter value
     */
    public function setParameter(string $key, $value): void
    {
        $parameters = $this->parameters ?? [];
        data_set($parameters, $key, $value);
        $this->parameters = $parameters;
    }
}
