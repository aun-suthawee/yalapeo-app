<?php

namespace Modules\Sandbox\Services;

use Modules\Sandbox\Entities\School;
use Modules\Sandbox\Entities\AcademicResult;
use Modules\Sandbox\Entities\Experiment;
use Modules\Sandbox\Entities\ExperimentScenario;
use Modules\Sandbox\Entities\ExperimentResult;
use Illuminate\Support\Collection;

class AnalyticsService
{
    /**
     * Create a snapshot of current data for experiment
     *
     * @param int $year
     * @param array $filters
     * @return array
     */
    public function createDataSnapshot(int $year, array $filters = []): array
    {
        $query = School::with(['academicResults' => function($q) use ($year) {
            $q->where('academic_year', $year)->whereNotNull('submitted_at');
        }]);

        // Apply filters
        if (!empty($filters['department'])) {
            $query->where('department', $filters['department']);
        }

        if (!empty($filters['district'])) {
            $query->where('district', $filters['district']);
        }

        if (!empty($filters['school_type'])) {
            $query->where('school_type', $filters['school_type']);
        }

        $schools = $query->get();
        
        $totalStudents = 0;
        $totalTeachers = 0;
        
        foreach ($schools as $school) {
            $totalStudents += ($school->male_students ?? 0) + ($school->female_students ?? 0);
            $totalTeachers += ($school->male_teachers ?? 0) + ($school->female_teachers ?? 0);
        }

        return [
            'schools_count' => $schools->count(),
            'total_students' => $totalStudents,
            'total_teachers' => $totalTeachers,
            'average_class_size' => $this->calculateAverageClassSize($schools),
            'teacher_student_ratio' => $this->calculateTeacherStudentRatio($schools),
            'academic_scores' => $this->aggregateAcademicScores($schools, $year),
            'schools_data' => $schools->toArray(),
        ];
    }

    /**
     * Run What-If scenario calculations
     *
     * @param array $baseDataSnapshot
     * @param array $parameters
     * @return array
     */
    public function runScenarioCalculations(array $baseDataSnapshot, array $parameters): array
    {
        $results = [];
        
        // Get base scores from snapshot (support both old and new structure)
        $baseScores = $baseDataSnapshot['academic_scores'] ?? [];
        if (empty($baseScores) && isset($baseDataSnapshot['statistics'])) {
            // Convert from ExperimentService structure to AnalyticsService structure
            $stats = $baseDataSnapshot['statistics'];
            $baseScores = [
                'nt_scores' => $stats['nt_scores']['averages'] ?? ['math' => 0, 'thai' => 0],
                'rt_scores' => $stats['rt_scores']['averages'] ?? ['reading' => 0, 'comprehension' => 0],
                'onet_scores' => $stats['onet_scores']['averages'] ?? ['math' => 0, 'thai' => 0, 'english' => 0, 'science' => 0],
                'nt_average' => round(
                    (($stats['nt_scores']['averages']['math'] ?? 0) + 
                     ($stats['nt_scores']['averages']['thai'] ?? 0)) / 2, 
                    2
                ),
                'rt_average' => round(
                    (($stats['rt_scores']['averages']['reading'] ?? 0) + 
                     ($stats['rt_scores']['averages']['comprehension'] ?? 0)) / 2, 
                    2
                ),
                'onet_average' => round(
                    (($stats['onet_scores']['averages']['math'] ?? 0) + 
                     ($stats['onet_scores']['averages']['thai'] ?? 0) + 
                     ($stats['onet_scores']['averages']['english'] ?? 0) + 
                     ($stats['onet_scores']['averages']['science'] ?? 0)) / 4, 
                    2
                ),
            ];
        }
        
        // If no parameters (baseline scenario), return base scores directly
        if (empty($parameters)) {
            $baseOverallAvg = round(
                (($baseScores['nt_average'] ?? 0) + 
                 ($baseScores['rt_average'] ?? 0) + 
                 ($baseScores['onet_average'] ?? 0)) / 3, 
                2
            );
            
            return [
                'nt_scores' => $baseScores['nt_scores'] ?? ['math' => 0, 'thai' => 0],
                'rt_scores' => $baseScores['rt_scores'] ?? ['reading' => 0, 'comprehension' => 0],
                'onet_scores' => $baseScores['onet_scores'] ?? ['math' => 0, 'thai' => 0, 'english' => 0, 'science' => 0],
                'nt_average' => $baseScores['nt_average'] ?? 0,
                'rt_average' => $baseScores['rt_average'] ?? 0,
                'onet_average' => $baseScores['onet_average'] ?? 0,
                'overall_average' => $baseOverallAvg,
                'avg_score' => $baseOverallAvg,
                'improvement_percent' => 0,
            ];
        }
        
        // Convert absolute values to percentage changes if needed
        $convertedParams = [];
        
        // Teacher ratio - convert from absolute value to % change
        $baseTeacherRatio = $baseDataSnapshot['statistics']['avg_student_teacher_ratio'] 
            ?? $baseDataSnapshot['teacher_student_ratio'] 
            ?? 20;

        if (isset($parameters['teacher_ratio'])) {
            // Get current ratio from statistics or fallback
            $currentRatio = $baseTeacherRatio;
            $newRatio = max(1, (float) $parameters['teacher_ratio']);
            $convertedParams['teacher_ratio_change'] = $currentRatio > 0 
                ? (($newRatio - $currentRatio) / $currentRatio) * 100 
                : 0;
            $convertedParams['teacher_ratio_target'] = $newRatio;
            $convertedParams['teacher_ratio_delta'] = $newRatio - $currentRatio;
            $convertedParams['teacher_ratio_current'] = $currentRatio;
        } elseif (isset($parameters['teacher_ratio_change'])) {
            $currentRatio = $baseTeacherRatio;
            $percentChange = (float) $parameters['teacher_ratio_change'];
            $newRatio = max(1, $currentRatio * (1 + $percentChange / 100));
            $convertedParams['teacher_ratio_change'] = $percentChange;
            $convertedParams['teacher_ratio_target'] = $newRatio;
            $convertedParams['teacher_ratio_delta'] = $newRatio - $currentRatio;
            $convertedParams['teacher_ratio_current'] = $currentRatio;
        }
        
        // Budget - convert from absolute value to % change
        if (isset($parameters['budget_per_student'])) {
            // Assume base budget is 10000 if not in snapshot
            $baseBudget = 10000;
            $newBudget = (float) $parameters['budget_per_student'];
            $convertedParams['budget_change_percentage'] = (($newBudget - $baseBudget) / $baseBudget) * 100;
        } elseif (isset($parameters['budget_change_percentage'])) {
            $convertedParams['budget_change_percentage'] = (float) $parameters['budget_change_percentage'];
        }
        
        // Training hours - use as-is
        if (isset($parameters['training_hours'])) {
            $convertedParams['training_hours'] = (float) $parameters['training_hours'];
        }

        // Apply teacher ratio changes
        if (isset($convertedParams['teacher_ratio_change'])) {
            $results['teacher_student_ratio'] = $this->calculateTeacherRatioImpact(
                $baseDataSnapshot,
                $convertedParams
            );
        }

        // Apply budget changes
        if (isset($convertedParams['budget_change_percentage'])) {
            $results['budget_impact'] = $this->calculateBudgetImpact(
                $baseDataSnapshot,
                $convertedParams['budget_change_percentage']
            );
        }

        // Apply training hours impact
        if (isset($convertedParams['training_hours'])) {
            $results['training_impact'] = $this->calculateTrainingImpact(
                $baseDataSnapshot,
                $convertedParams['training_hours']
            );
        }

        // Predict score improvements (use converted parameters AND base scores)
        $predictedScores = $this->predictScoreImprovements(
            $baseScores, // Converted base scores
            $convertedParams,
            $baseDataSnapshot
        );
        
        // Merge predicted scores into results
        $results = array_merge($results, $predictedScores);
        
        // Calculate overall average and improvement
        $baseOverallAvg = round(
            (($baseScores['nt_average'] ?? 0) + 
             ($baseScores['rt_average'] ?? 0) + 
             ($baseScores['onet_average'] ?? 0)) / 3, 
            2
        );
        
        $predictedOverallAvg = round(
            (($predictedScores['nt_average'] ?? 0) + 
             ($predictedScores['rt_average'] ?? 0) + 
             ($predictedScores['onet_average'] ?? 0)) / 3, 
            2
        );
        
        $results['overall_average'] = $predictedOverallAvg;
        $results['base_overall_average'] = $baseOverallAvg;
        $results['avg_score'] = $predictedOverallAvg;
        $results['improvement_percent'] = $baseOverallAvg > 0 
            ? round((($predictedOverallAvg - $baseOverallAvg) / $baseOverallAvg) * 100, 2) 
            : 0;

        return $results;
    }

    /**
     * Calculate teacher-student ratio impact
     *
     * @param array $baseData
    * @param array $parameters
     * @return array
     */
    protected function calculateTeacherRatioImpact(array $baseData, array $parameters): array
    {
        $currentRatio = $parameters['teacher_ratio_current']
            ?? ($baseData['statistics']['avg_student_teacher_ratio']
                ?? $baseData['teacher_student_ratio']
                ?? 0);
        $targetRatio = $parameters['teacher_ratio_target'] ?? ($currentRatio ?: 0);
        $ratioChange = $parameters['teacher_ratio_change'] ?? 0;
        $ratioDelta = $parameters['teacher_ratio_delta'] ?? ($targetRatio - $currentRatio);

        $elasticities = $this->calculateScoreElasticities($baseData);
        $ntSlope = $elasticities['nt_scores']['math'] + $elasticities['nt_scores']['thai'];
        $rtSlope = $elasticities['rt_scores']['reading'] + $elasticities['rt_scores']['comprehension'];
        $onetSlope = $elasticities['onet_scores']['math']
            + $elasticities['onet_scores']['thai']
            + $elasticities['onet_scores']['english']
            + $elasticities['onet_scores']['science'];

        $totalSlope = $ntSlope + $rtSlope + $onetSlope;
        $subjectsCount = 8; // total subjects considered across NT, RT, O-NET
        $avgSlope = $subjectsCount > 0 ? $totalSlope / $subjectsCount : 0;

        $baselineAvg = $this->calculateBaselineAverageFromSnapshot($baseData);

        if ($baselineAvg > 0 && $avgSlope < -1e-6) {
            $scoreShift = $avgSlope * $ratioDelta; // negative slope with negative delta yields positive shift
            $scoreImprovement = round(($scoreShift / $baselineAvg) * 100, 2);
        } else {
            $scoreImprovement = (-$ratioChange / 10) * 2.2;
        }
        $scoreImprovement = max(-15, min(15, $scoreImprovement));

        return [
            'current_ratio' => round($currentRatio, 2),
            'new_ratio' => round($targetRatio, 2),
            'change' => round($ratioDelta, 2),
            'predicted_score_improvement' => round($scoreImprovement, 2),
        ];
    }

    /**
     * Calculate budget impact on resources
     *
     * @param array $baseData
     * @param float $budgetChangePercentage
     * @return array
     */
    protected function calculateBudgetImpact(array $baseData, float $budgetChangePercentage): array
    {
    $adjustedChange = max(-50.0, min(200.0, (float) $budgetChangePercentage));

        // Allocate changes proportionally but allow negative values when budget decreases
        $teachersIncrease = $adjustedChange * 0.35; // 35% of budget to staffing
        $facilitiesImprovement = $adjustedChange * 0.35; // 35% to infrastructure
        $materialsImprovement = $adjustedChange * 0.30; // 30% to materials & tools

        $scoreImpact = $this->applySignedDiminishing(
            $adjustedChange,
            11.0,
            0.035,
            9.0
        );

        return [
            'budget_change_percentage' => round($adjustedChange, 2),
            'teachers_increase' => round($teachersIncrease, 2),
            'facilities_improvement' => round($facilitiesImprovement, 2),
            'materials_improvement' => round($materialsImprovement, 2),
            'predicted_score_improvement' => round($scoreImpact, 2),
        ];
    }

    /**
     * Calculate training impact
     *
     * @param array $baseData
     * @param int $trainingHours
     * @return array
     */
    protected function calculateTrainingImpact(array $baseData, int $trainingHours): array
    {
        $effectPercent = $this->applySignedDiminishing(
            max(0, (float) $trainingHours),
            9.5,
            0.028
        );

        $totalTeachers = $baseData['statistics']['total_teachers'] 
            ?? $baseData['total_teachers'] 
            ?? 0;

        $basePackageHours = 40;
        $basePackageCost = 5000; // THB per teacher for 40 hours
        $costPerTeacher = $basePackageHours > 0
            ? ($trainingHours / $basePackageHours) * $basePackageCost
            : 0;
        $totalCost = $totalTeachers * $costPerTeacher;

        return [
            'training_hours' => $trainingHours,
            'predicted_score_improvement' => round($effectPercent, 2),
            'estimated_cost_per_teacher' => round($costPerTeacher, 2),
            'total_teachers' => $totalTeachers,
            'total_cost' => round($totalCost, 2),
        ];
    }

    /**
     * Predict score improvements based on multiple factors
     *
     * @param array $baseScores Already converted base scores with nt_scores, rt_scores, onet_scores
     * @param array $parameters
     * @return array
     */
    protected function predictScoreImprovements(array $baseScores, array $parameters, array $baseDataSnapshot): array
    {
        $elasticities = $this->calculateScoreElasticities($baseDataSnapshot);

        $ratioDelta = $parameters['teacher_ratio_delta'] ?? 0.0;
        $ratioPercentChange = $parameters['teacher_ratio_change'] ?? 0.0;

        $ratioEffects = [
            'nt_scores' => [
                'math' => $this->calculateRatioEffect(
                    $baseScores['nt_scores']['math'] ?? 0,
                    $elasticities['nt_scores']['math'] ?? 0,
                    $ratioDelta,
                    $ratioPercentChange
                ),
                'thai' => $this->calculateRatioEffect(
                    $baseScores['nt_scores']['thai'] ?? 0,
                    $elasticities['nt_scores']['thai'] ?? 0,
                    $ratioDelta,
                    $ratioPercentChange
                ),
            ],
            'rt_scores' => [
                'reading' => $this->calculateRatioEffect(
                    $baseScores['rt_scores']['reading'] ?? 0,
                    $elasticities['rt_scores']['reading'] ?? 0,
                    $ratioDelta,
                    $ratioPercentChange
                ),
                'comprehension' => $this->calculateRatioEffect(
                    $baseScores['rt_scores']['comprehension'] ?? 0,
                    $elasticities['rt_scores']['comprehension'] ?? 0,
                    $ratioDelta,
                    $ratioPercentChange
                ),
            ],
            'onet_scores' => [
                'math' => $this->calculateRatioEffect(
                    $baseScores['onet_scores']['math'] ?? 0,
                    $elasticities['onet_scores']['math'] ?? 0,
                    $ratioDelta,
                    $ratioPercentChange
                ),
                'thai' => $this->calculateRatioEffect(
                    $baseScores['onet_scores']['thai'] ?? 0,
                    $elasticities['onet_scores']['thai'] ?? 0,
                    $ratioDelta,
                    $ratioPercentChange
                ),
                'english' => $this->calculateRatioEffect(
                    $baseScores['onet_scores']['english'] ?? 0,
                    $elasticities['onet_scores']['english'] ?? 0,
                    $ratioDelta,
                    $ratioPercentChange
                ),
                'science' => $this->calculateRatioEffect(
                    $baseScores['onet_scores']['science'] ?? 0,
                    $elasticities['onet_scores']['science'] ?? 0,
                    $ratioDelta,
                    $ratioPercentChange
                ),
            ],
        ];

        $trainingEffectPercent = 0.0;
        if (!empty($parameters['training_hours'])) {
            $trainingEffectPercent = $this->applySignedDiminishing(
                max(0, (float) $parameters['training_hours']),
                9.5,
                0.028
            );
        }

        $budgetEffectPercent = 0.0;
        if (isset($parameters['budget_change_percentage'])) {
            $budgetEffectPercent = $this->applySignedDiminishing(
                (float) $parameters['budget_change_percentage'],
                11.0,
                0.035,
                9.0
            );
        }

        $digitalEffectPercent = 0.0;
        if (isset($parameters['digital_readiness'])) {
            $digitalDelta = (float) $parameters['digital_readiness'] - 50;
            $digitalEffectPercent = $this->applySignedDiminishing(
                $digitalDelta,
                6.0,
                0.05,
                6.0
            );
        }

        $infrastructureEffectPercent = 0.0;
        if (isset($parameters['infrastructure_score'])) {
            $infrastructureDelta = (float) $parameters['infrastructure_score'] - 50;
            $infrastructureEffectPercent = $this->applySignedDiminishing(
                $infrastructureDelta,
                7.0,
                0.045,
                7.0
            );
        }

        $percentageImpact = $trainingEffectPercent + $budgetEffectPercent + $digitalEffectPercent + $infrastructureEffectPercent;
        $percentageImpact = max(-25, min(25, $percentageImpact));
        $percentageMultiplier = 1 + ($percentageImpact / 100);

        $predictedNT = [];
        $predictedRT = [];
        $predictedONET = [];

        foreach ($ratioEffects['nt_scores'] as $subject => $delta) {
            $base = $baseScores['nt_scores'][$subject] ?? 0;
            $score = ($base + $delta) * $percentageMultiplier;
            $predictedNT[$subject] = $this->clampScore($score);
        }

        foreach ($ratioEffects['rt_scores'] as $subject => $delta) {
            $base = $baseScores['rt_scores'][$subject] ?? 0;
            $score = ($base + $delta) * $percentageMultiplier;
            $predictedRT[$subject] = $this->clampScore($score);
        }

        foreach ($ratioEffects['onet_scores'] as $subject => $delta) {
            $base = $baseScores['onet_scores'][$subject] ?? 0;
            $score = ($base + $delta) * $percentageMultiplier;
            $predictedONET[$subject] = $this->clampScore($score);
        }

        $predictedNT['math'] = $predictedNT['math'] ?? 0;
        $predictedNT['thai'] = $predictedNT['thai'] ?? 0;
        $predictedRT['reading'] = $predictedRT['reading'] ?? 0;
        $predictedRT['comprehension'] = $predictedRT['comprehension'] ?? 0;
        $predictedONET['math'] = $predictedONET['math'] ?? 0;
        $predictedONET['thai'] = $predictedONET['thai'] ?? 0;
        $predictedONET['english'] = $predictedONET['english'] ?? 0;
        $predictedONET['science'] = $predictedONET['science'] ?? 0;

        $ntAverage = round(($predictedNT['math'] + $predictedNT['thai']) / 2, 2);
        $rtAverage = round(($predictedRT['reading'] + $predictedRT['comprehension']) / 2, 2);
        $onetAverage = round(($predictedONET['math'] + $predictedONET['thai'] + $predictedONET['english'] + $predictedONET['science']) / 4, 2);

        $baseOverallAvg = round(
            (($baseScores['nt_average'] ?? 0) + ($baseScores['rt_average'] ?? 0) + ($baseScores['onet_average'] ?? 0)) / 3,
            2
        );

        $overallAvg = round(($ntAverage + $rtAverage + $onetAverage) / 3, 2);
        $improvementPercent = $baseOverallAvg > 0
            ? round((($overallAvg - $baseOverallAvg) / $baseOverallAvg) * 100, 2)
            : 0;

        return [
            'nt_scores' => $predictedNT,
            'rt_scores' => $predictedRT,
            'onet_scores' => $predictedONET,
            'nt_average' => $ntAverage,
            'rt_average' => $rtAverage,
            'onet_average' => $onetAverage,
            'base_nt_average' => $baseScores['nt_average'] ?? 0,
            'base_rt_average' => $baseScores['rt_average'] ?? 0,
            'base_onet_average' => $baseScores['onet_average'] ?? 0,
            'improvement_percentage' => $improvementPercent,
        ];
    }

    /**
     * Helper: Calculate average class size
     *
     * @param Collection $schools
     * @return float
     */
    protected function calculateAverageClassSize(Collection $schools): float
    {
        $totalStudents = $schools->sum('total_students');
        $totalSchools = $schools->count();
        
        return $totalSchools > 0 ? round($totalStudents / $totalSchools, 2) : 0;
    }

    /**
     * Estimate how strongly teacher-student ratio impacts each subject using the current dataset
     */
    protected function calculateScoreElasticities(array $baseDataSnapshot): array
    {
        $schools = $baseDataSnapshot['schools_data'] ?? $baseDataSnapshot['schools'] ?? [];

        $elasticities = [
            'nt_scores' => ['math' => 0.0, 'thai' => 0.0],
            'rt_scores' => ['reading' => 0.0, 'comprehension' => 0.0],
            'onet_scores' => ['math' => 0.0, 'thai' => 0.0, 'english' => 0.0, 'science' => 0.0],
        ];

        $subjectsMap = [
            'nt_scores' => ['math' => 'nt_math', 'thai' => 'nt_thai'],
            'rt_scores' => ['reading' => 'rt_reading', 'comprehension' => 'rt_comprehension'],
            'onet_scores' => [
                'math' => 'onet_math',
                'thai' => 'onet_thai',
                'english' => 'onet_english',
                'science' => 'onet_science',
            ],
        ];

        $subjectData = [];
        foreach ($subjectsMap as $group => $subjectKeys) {
            foreach ($subjectKeys as $subject => $scoreKey) {
                $subjectData[$group][$subject] = ['x' => [], 'y' => []];
            }
        }

        foreach ($schools as $school) {
            $ratio = $school['ratio']
                ?? $school['teacher_student_ratio']
                ?? $school['student_teacher_ratio']
                ?? null;

            if (!is_numeric($ratio) || $ratio <= 0) {
                continue;
            }

            $scores = $school['scores'] ?? null;
            if (!is_array($scores)) {
                continue;
            }

            foreach ($subjectsMap as $group => $subjectKeys) {
                foreach ($subjectKeys as $subject => $scoreKey) {
                    if (!isset($scores[$scoreKey]) || !is_numeric($scores[$scoreKey])) {
                        continue;
                    }

                    $subjectData[$group][$subject]['x'][] = (float) $ratio;
                    $subjectData[$group][$subject]['y'][] = (float) $scores[$scoreKey];
                }
            }
        }

        foreach ($subjectData as $group => $subjectEntries) {
            foreach ($subjectEntries as $subject => $data) {
                $elasticities[$group][$subject] = $this->computeSlope($data['x'], $data['y']);
            }
        }

        return $elasticities;
    }

    /**
     * Estimate the direct score delta from a teacher ratio change
     */
    protected function calculateRatioEffect(float $baseScore, float $slope, float $ratioDelta, float $percentChange): float
    {
        if (abs($ratioDelta) < 1e-6) {
            return 0.0;
        }

        if ($slope < -1e-6) {
            return $slope * $ratioDelta;
        }

        // Fallback: assume every 10% reduction in ratio yields ~2.2% score gain
        $fallbackPercent = (-$percentChange / 10) * 2.2;
        return $baseScore * ($fallbackPercent / 100);
    }

    /**
     * Apply diminishing returns curve that keeps improvements bounded
     */
    protected function applySignedDiminishing(float $value, float $maxPositive, float $sensitivity, ?float $maxNegative = null): float
    {
        if (abs($value) < 1e-6) {
            return 0.0;
        }

        $maxNegative = $maxNegative ?? $maxPositive;

        if ($value > 0) {
            return $maxPositive * (1 - exp(-$sensitivity * $value));
        }

        return -$maxNegative * (1 - exp(-$sensitivity * abs($value)));
    }

    protected function clampScore(float $value, float $min = 0.0, float $max = 100.0): float
    {
        return round(max($min, min($max, $value)), 2);
    }

    protected function calculateBaselineAverageFromSnapshot(array $snapshot): float
    {
        if (isset($snapshot['statistics'])) {
            $stats = $snapshot['statistics'];
            $ntAvg = (($stats['nt_scores']['averages']['math'] ?? 0) + ($stats['nt_scores']['averages']['thai'] ?? 0)) / 2;
            $rtAvg = (($stats['rt_scores']['averages']['reading'] ?? 0) + ($stats['rt_scores']['averages']['comprehension'] ?? 0)) / 2;
            $onetAvg = (
                ($stats['onet_scores']['averages']['math'] ?? 0)
                + ($stats['onet_scores']['averages']['thai'] ?? 0)
                + ($stats['onet_scores']['averages']['english'] ?? 0)
                + ($stats['onet_scores']['averages']['science'] ?? 0)
            ) / 4;

            return round(($ntAvg + $rtAvg + $onetAvg) / 3, 2);
        }

        if (isset($snapshot['academic_scores'])) {
            $scores = $snapshot['academic_scores'];
            $ntAvg = ($scores['nt_average'] ?? 0);
            $rtAvg = ($scores['rt_average'] ?? 0);
            $onetAvg = ($scores['onet_average'] ?? 0);

            return round(($ntAvg + $rtAvg + $onetAvg) / 3, 2);
        }

        return 0.0;
    }

    protected function computeSlope(array $x, array $y): float
    {
        $count = count($x);
        if ($count !== count($y) || $count < 2) {
            return 0.0;
        }

        $meanX = array_sum($x) / $count;
        $meanY = array_sum($y) / $count;

        $covariance = 0.0;
        $variance = 0.0;

        for ($i = 0; $i < $count; $i++) {
            $dx = $x[$i] - $meanX;
            $covariance += $dx * ($y[$i] - $meanY);
            $variance += $dx * $dx;
        }

        return $variance > 0 ? $covariance / $variance : 0.0;
    }

    /**
     * Helper: Calculate teacher-student ratio
     *
     * @param Collection $schools
     * @return float
     */
    protected function calculateTeacherStudentRatio(Collection $schools): float
    {
        $totalTeachers = $schools->sum('total_teachers');
        $totalStudents = $schools->sum('total_students');
        
        return $totalTeachers > 0 ? round($totalStudents / $totalTeachers, 2) : 0;
    }

    /**
     * Helper: Aggregate academic scores
     *
     * @param Collection $schools
     * @param int|null $year
     * @return array
     */
    protected function aggregateAcademicScores(Collection $schools, ?int $year = null): array
    {
        $ntMath = [];
        $ntThai = [];
        $rtReading = [];
        $rtComprehension = [];
        $onetMath = [];
        $onetThai = [];
        $onetEnglish = [];
        $onetScience = [];

        foreach ($schools as $school) {
            $result = $school->academicResults->first();
            if ($result) {
                if ($result->nt_math_score) $ntMath[] = (float) $result->nt_math_score;
                if ($result->nt_thai_score) $ntThai[] = (float) $result->nt_thai_score;
                if ($result->rt_reading_score) $rtReading[] = (float) $result->rt_reading_score;
                if ($result->rt_comprehension_score) $rtComprehension[] = (float) $result->rt_comprehension_score;
                if ($result->onet_math_score) $onetMath[] = (float) $result->onet_math_score;
                if ($result->onet_thai_score) $onetThai[] = (float) $result->onet_thai_score;
                if ($result->onet_english_score) $onetEnglish[] = (float) $result->onet_english_score;
                if ($result->onet_science_score) $onetScience[] = (float) $result->onet_science_score;
            }
        }

        $ntAvg = [
            'math' => count($ntMath) > 0 ? round(array_sum($ntMath) / count($ntMath), 2) : 0,
            'thai' => count($ntThai) > 0 ? round(array_sum($ntThai) / count($ntThai), 2) : 0,
        ];
        
        $rtAvg = [
            'reading' => count($rtReading) > 0 ? round(array_sum($rtReading) / count($rtReading), 2) : 0,
            'comprehension' => count($rtComprehension) > 0 ? round(array_sum($rtComprehension) / count($rtComprehension), 2) : 0,
        ];
        
        $onetAvg = [
            'math' => count($onetMath) > 0 ? round(array_sum($onetMath) / count($onetMath), 2) : 0,
            'thai' => count($onetThai) > 0 ? round(array_sum($onetThai) / count($onetThai), 2) : 0,
            'english' => count($onetEnglish) > 0 ? round(array_sum($onetEnglish) / count($onetEnglish), 2) : 0,
            'science' => count($onetScience) > 0 ? round(array_sum($onetScience) / count($onetScience), 2) : 0,
        ];

        return [
            'nt_scores' => $ntAvg,
            'rt_scores' => $rtAvg,
            'onet_scores' => $onetAvg,
            'nt_average' => round(($ntAvg['math'] + $ntAvg['thai']) / 2, 2),
            'rt_average' => round(($rtAvg['reading'] + $rtAvg['comprehension']) / 2, 2),
            'onet_average' => round(($onetAvg['math'] + $onetAvg['thai'] + $onetAvg['english'] + $onetAvg['science']) / 4, 2),
        ];
    }
}
