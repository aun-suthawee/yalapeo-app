<?php

namespace Modules\Sandbox\Services;

use Modules\Sandbox\Entities\Experiment;
use Modules\Sandbox\Entities\School;
use Modules\Sandbox\Entities\AcademicResult;
use Illuminate\Support\Facades\DB;

class ExperimentService
{
    /**
     * Create snapshot of current data for experiment baseline
     *
     * @param int $year
     * @param array $filters
     * @return array
     */
    public function createDataSnapshot(int $year, array $filters = []): array
    {
        // Get schools with filters
        $query = School::with(['academicResults' => function($q) use ($year) {
            $q->where('academic_year', $year)->whereNotNull('submitted_at');
        }]);

        // Apply filters
        if (!empty($filters['school_id'])) {
            $query->where('id', $filters['school_id']);
        }
        if (!empty($filters['department'])) {
            $query->where('department', $filters['department']);
        }
        if (!empty($filters['district'])) {
            $query->where('district', $filters['district']);
        }
        if (!empty($filters['subdistrict'])) {
            $query->where('subdistrict', $filters['subdistrict']);
        }

        $schools = $query->get();

        $snapshot = [
            'year' => $year,
            'snapshot_date' => now()->toDateTimeString(),
            'total_schools' => $schools->count(),
            'schools_with_data' => $schools->filter(fn($s) => $s->academicResults->isNotEmpty())->count(),
            'statistics' => [],
            'schools' => [],
        ];

        // Calculate overall statistics
        $allResults = AcademicResult::where('academic_year', $year)
            ->whereNotNull('submitted_at')
            ->get();

        $snapshot['statistics'] = [
            'total_students' => $schools->sum(fn($s) => $s->male_students + $s->female_students),
            'total_teachers' => $schools->sum(fn($s) => $s->male_teachers + $s->female_teachers),
            'avg_student_teacher_ratio' => $this->calculateAverageRatio($schools),
            'nt_scores' => $this->calculateTestAverages($allResults, 'nt'),
            'rt_scores' => $this->calculateTestAverages($allResults, 'rt'),
            'onet_scores' => $this->calculateTestAverages($allResults, 'onet'),
        ];

        // Store school-level data
        foreach ($schools as $school) {
            $result = $school->academicResults->first();
            
            $snapshot['schools'][] = [
                'id' => $school->id,
                'name' => $school->name,
                'district' => $school->district,
                'students' => $school->male_students + $school->female_students,
                'teachers' => $school->male_teachers + $school->female_teachers,
                'ratio' => $this->calculateRatio($school),
                'scores' => $result ? [
                    'nt_math' => $result->nt_math_score,
                    'nt_thai' => $result->nt_thai_score,
                    'rt_reading' => $result->rt_reading_score,
                    'rt_comprehension' => $result->rt_comprehension_score,
                    'onet_math' => $result->onet_math_score,
                    'onet_thai' => $result->onet_thai_score,
                    'onet_english' => $result->onet_english_score,
                    'onet_science' => $result->onet_science_score,
                ] : null,
            ];
        }

        return $snapshot;
    }

    /**
     * Get comparison data for charts
     *
     * @param Experiment $experiment
     * @return array
     */
    public function getComparisonData(Experiment $experiment): array
    {
        $scenarios = $experiment->scenarios()->with('scenarioResults')->orderBy('order')->get();
        
        $comparisonData = [
            'labels' => [],
            'datasets' => [
                'nt_math' => [],
                'nt_thai' => [],
                'rt_reading' => [],
                'rt_comprehension' => [],
                'onet_math' => [],
                'onet_thai' => [],
                'onet_english' => [],
                'onet_science' => [],
                'avg_score' => [],
                'improvement_percent' => [],
            ],
            'scenarios' => [],
        ];

        $baselineAvg = null;
        $baseSnapshot = $experiment->base_data_snapshot;

        foreach ($scenarios as $index => $scenario) {
            $result = $scenario->scenarioResults ? $scenario->scenarioResults->first() : null;
            
            $comparisonData['labels'][] = $scenario->name;
            $comparisonData['scenarios'][] = [
                'id' => $scenario->id,
                'name' => $scenario->name,
                'description' => $scenario->description,
                'parameters' => $scenario->parameters,
                'order' => $scenario->order,
            ];

            // Check if this is baseline scenario (first scenario with empty parameters)
            $isBaseline = ($index === 0 && empty($scenario->parameters));
            
            if ($isBaseline && $baseSnapshot && isset($baseSnapshot['statistics'])) {
                // Use base snapshot data for baseline scenario
                $ntScores = $baseSnapshot['statistics']['nt_scores']['averages'] ?? [];
                $rtScores = $baseSnapshot['statistics']['rt_scores']['averages'] ?? [];
                $onetScores = $baseSnapshot['statistics']['onet_scores']['averages'] ?? [];
                
                $comparisonData['datasets']['nt_math'][] = $ntScores['math'] ?? 0;
                $comparisonData['datasets']['nt_thai'][] = $ntScores['thai'] ?? 0;
                $comparisonData['datasets']['rt_reading'][] = $rtScores['reading'] ?? 0;
                $comparisonData['datasets']['rt_comprehension'][] = $rtScores['comprehension'] ?? 0;
                $comparisonData['datasets']['onet_math'][] = $onetScores['math'] ?? 0;
                $comparisonData['datasets']['onet_thai'][] = $onetScores['thai'] ?? 0;
                $comparisonData['datasets']['onet_english'][] = $onetScores['english'] ?? 0;
                $comparisonData['datasets']['onet_science'][] = $onetScores['science'] ?? 0;
                
                // Calculate baseline average
                $baselineAvg = round((
                    ($ntScores['math'] ?? 0) + ($ntScores['thai'] ?? 0) +
                    ($rtScores['reading'] ?? 0) + ($rtScores['comprehension'] ?? 0) +
                    ($onetScores['math'] ?? 0) + ($onetScores['thai'] ?? 0) +
                    ($onetScores['english'] ?? 0) + ($onetScores['science'] ?? 0)
                ) / 8, 2);
                
                $comparisonData['datasets']['avg_score'][] = $baselineAvg;
                $comparisonData['datasets']['improvement_percent'][] = 0;
                
            } elseif ($result && $result->metrics) {
                $metrics = $result->metrics;
                
                // Individual subject scores
                $comparisonData['datasets']['nt_math'][] = $metrics['nt_scores']['math'] ?? 0;
                $comparisonData['datasets']['nt_thai'][] = $metrics['nt_scores']['thai'] ?? 0;
                $comparisonData['datasets']['rt_reading'][] = $metrics['rt_scores']['reading'] ?? 0;
                $comparisonData['datasets']['rt_comprehension'][] = $metrics['rt_scores']['comprehension'] ?? 0;
                $comparisonData['datasets']['onet_math'][] = $metrics['onet_scores']['math'] ?? 0;
                $comparisonData['datasets']['onet_thai'][] = $metrics['onet_scores']['thai'] ?? 0;
                $comparisonData['datasets']['onet_english'][] = $metrics['onet_scores']['english'] ?? 0;
                $comparisonData['datasets']['onet_science'][] = $metrics['onet_scores']['science'] ?? 0;
                
                // Calculate average across all subjects
                $avgScore = $metrics['overall_average'] ?? 0;
                $comparisonData['datasets']['avg_score'][] = round($avgScore, 2);
                
                // Calculate improvement percentage
                if ($baselineAvg === null) {
                    $baselineAvg = $avgScore;
                    $comparisonData['datasets']['improvement_percent'][] = 0;
                } else {
                    $improvement = $baselineAvg > 0 ? (($avgScore - $baselineAvg) / $baselineAvg) * 100 : 0;
                    $comparisonData['datasets']['improvement_percent'][] = round($improvement, 2);
                }
            } else {
                // No results yet
                foreach ($comparisonData['datasets'] as $key => $value) {
                    $comparisonData['datasets'][$key][] = 0;
                }
            }
        }

        return $comparisonData;
    }

    /**
     * Calculate average student-teacher ratio
     */
    private function calculateAverageRatio($schools): float
    {
        $ratios = [];
        foreach ($schools as $school) {
            $ratio = $this->calculateRatio($school);
            if ($ratio > 0) {
                $ratios[] = $ratio;
            }
        }
        return count($ratios) > 0 ? round(array_sum($ratios) / count($ratios), 2) : 0;
    }

    /**
     * Calculate ratio for single school
     */
    private function calculateRatio($school): float
    {
        $students = $school->male_students + $school->female_students;
        $teachers = $school->male_teachers + $school->female_teachers;
        return $teachers > 0 ? round($students / $teachers, 2) : 0;
    }

    /**
     * Calculate test averages
     */
    private function calculateTestAverages($results, $testType): array
    {
        $scores = [
            'count' => 0,
            'averages' => [],
        ];

        if ($testType === 'nt') {
            $mathScores = $results->pluck('nt_math_score')->filter()->values();
            $thaiScores = $results->pluck('nt_thai_score')->filter()->values();
            
            $scores['count'] = max($mathScores->count(), $thaiScores->count());
            $scores['averages'] = [
                'math' => $mathScores->count() > 0 ? round($mathScores->average(), 2) : 0,
                'thai' => $thaiScores->count() > 0 ? round($thaiScores->average(), 2) : 0,
            ];
        } elseif ($testType === 'rt') {
            $readingScores = $results->pluck('rt_reading_score')->filter()->values();
            $compScores = $results->pluck('rt_comprehension_score')->filter()->values();
            
            $scores['count'] = max($readingScores->count(), $compScores->count());
            $scores['averages'] = [
                'reading' => $readingScores->count() > 0 ? round($readingScores->average(), 2) : 0,
                'comprehension' => $compScores->count() > 0 ? round($compScores->average(), 2) : 0,
            ];
        } elseif ($testType === 'onet') {
            $mathScores = $results->pluck('onet_math_score')->filter()->values();
            $thaiScores = $results->pluck('onet_thai_score')->filter()->values();
            $engScores = $results->pluck('onet_english_score')->filter()->values();
            $sciScores = $results->pluck('onet_science_score')->filter()->values();
            
            $scores['count'] = max(
                $mathScores->count(),
                $thaiScores->count(),
                $engScores->count(),
                $sciScores->count()
            );
            $scores['averages'] = [
                'math' => $mathScores->count() > 0 ? round($mathScores->average(), 2) : 0,
                'thai' => $thaiScores->count() > 0 ? round($thaiScores->average(), 2) : 0,
                'english' => $engScores->count() > 0 ? round($engScores->average(), 2) : 0,
                'science' => $sciScores->count() > 0 ? round($sciScores->average(), 2) : 0,
            ];
        }

        return $scores;
    }
}
