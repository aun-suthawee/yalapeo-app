<?php

namespace Modules\Sandbox\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Sandbox\Entities\School;
use Modules\Sandbox\Entities\AcademicResult;
use Modules\Sandbox\Services\ExportService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AcademicResultsController extends Controller
{
    /**
     * Normalize education area label (trim and fallback for empty values)
     */
    private function normalizeEducationArea(?string $educationArea): string
    {
        if ($educationArea === null) {
            return 'ไม่ระบุ';
        }

        $trimmed = trim($educationArea);

        return $trimmed !== '' ? $trimmed : 'ไม่ระบุ';
    }

    /**
     * Shorten education area names for display
     */
    private function shortenEducationArea(string $educationArea): string
    {
        $mapping = [
            'สำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 1' => 'สพป.ยะลา เขต 1',
            'สำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 2' => 'สพป.ยะลา เขต 2',
            'สำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 3' => 'สพป.ยะลา เขต 3',
            'สำนักงานคณะกรรมการส่งเสริมการศึกษาเอกชน' => 'สช.',
            'เทศบาลนครยะลา' => 'เทศบาลนครยะลา',
            'องค์การบริหารส่วนจังหวัดยะลา' => 'อบจ.ยะลา',
            'ไม่ระบุ' => 'ไม่ระบุ',
        ];

        return $mapping[$educationArea] ?? $educationArea;
    }
    
    /**
     * Display a listing of schools with their academic results status
     */
    public function index(Request $request)
    {
        $currentYear = 2025; // ปีการศึกษาปัจจุบัน (ค.ศ.)
        $selectedYear = $request->get('year', $currentYear);
        $filter = $request->get('filter', 'all'); // all, not_submitted, submitted
        
        // Get additional filters
        $educationArea = $request->get('education_area');
        $ministry = $request->get('ministry');
        $bureau = $request->get('bureau');
        $district = $request->get('district');
        $subdistrict = $request->get('subdistrict');
        
        // Get all schools with their results for the selected year
        $query = School::with(['academicResults' => function ($q) use ($selectedYear) {
            $q->forYear($selectedYear);
        }]);

        // Apply additional filters
        if ($educationArea) {
            $query->where(function($subQuery) use ($educationArea) {
                if ($educationArea === 'ไม่ระบุ') {
                    $subQuery->whereNull('education_area')
                        ->orWhereRaw("TRIM(education_area) = ''");
                } else {
                    $subQuery->whereRaw('TRIM(education_area) = ?', [$educationArea]);
                }
            });
        }
        if ($ministry) {
            $query->where('ministry_affiliation', $ministry);
        }
        if ($bureau) {
            $query->where('bureau_affiliation', $bureau);
        }
        if ($district) {
            $query->where('district', $district);
        }
        if ($subdistrict) {
            $query->where('subdistrict', $subdistrict);
        }

        // Apply submission status filter
        if ($filter === 'not_submitted') {
            $query->whereDoesntHave('academicResults', function ($q) use ($selectedYear) {
                $q->forYear($selectedYear)->submitted();
            })->orderBy('name');
        } elseif ($filter === 'submitted') {
            $query->whereHas('academicResults', function ($q) use ($selectedYear) {
                $q->forYear($selectedYear)->submitted();
            })->orderBy('name');
        } else {
            // Sort: Not submitted first, then submitted (both sorted by name)
            $query->leftJoin('academic_results', function($join) use ($selectedYear) {
                $join->on('schools.id', '=', 'academic_results.school_id')
                     ->where('academic_results.academic_year', '=', $selectedYear)
                     ->whereNotNull('academic_results.submitted_at');
            })
            ->select('schools.*')
            ->orderByRaw('CASE WHEN academic_results.submitted_at IS NULL THEN 0 ELSE 1 END')
            ->orderBy('schools.name');
        }

        $schools = $query->paginate(20);

        // Statistics
        $totalSchools = School::count();
        $submittedCount = School::whereHas('academicResults', function ($q) use ($selectedYear) {
            $q->forYear($selectedYear)->submitted();
        })->count();
        $notSubmittedCount = $totalSchools - $submittedCount;

        // Totals by education area for overview table
        $normalizedEducationAreaSql = "CASE WHEN TRIM(COALESCE(education_area, '')) = '' THEN 'ไม่ระบุ' ELSE TRIM(education_area) END";

        $totalsByEducationArea = School::selectRaw("$normalizedEducationAreaSql AS education_area_label, COUNT(*) AS total")
            ->groupBy('education_area_label')
            ->orderBy('education_area_label')
            ->get();

        $submittedByEducationArea = AcademicResult::selectRaw("$normalizedEducationAreaSql AS education_area_label, COUNT(DISTINCT schools.id) AS submitted_total")
            ->join('schools', 'academic_results.school_id', '=', 'schools.id')
            ->where('academic_results.academic_year', $selectedYear)
            ->whereNotNull('academic_results.submitted_at')
            ->groupBy('education_area_label')
            ->get()
            ->pluck('submitted_total', 'education_area_label');

        $educationAreaSummaries = $totalsByEducationArea
            ->map(function ($row) use ($submittedByEducationArea) {
                $label = $row->education_area_label;
                $total = (int) $row->total;
                $submitted = (int) ($submittedByEducationArea[$label] ?? 0);

                return [
                    'label' => $label,
                    'short_label' => $this->shortenEducationArea($label),
                    'total' => $total,
                    'submitted' => $submitted,
                    'pending' => max(0, $total - $submitted),
                ];
            })
            ->sortByDesc('total')
            ->values();

        // Get available years (only current year: 2568 BE = 2025 CE)
        $availableYears = [$currentYear]; // เฉพาะปีปัจจุบัน
        
        // Get filter options
        $educationAreas = School::pluck('education_area')
            ->map(fn($value) => $this->normalizeEducationArea($value))
            ->unique()
            ->sort()
            ->values();
        $ministries = School::distinct()->whereNotNull('ministry_affiliation')->orderBy('ministry_affiliation')->pluck('ministry_affiliation');
        $bureaus = School::distinct()->whereNotNull('bureau_affiliation')->orderBy('bureau_affiliation')->pluck('bureau_affiliation');
        $districts = School::distinct()->whereNotNull('district')->orderBy('district')->pluck('district');
        
        // Get subdistricts based on selected district
        $subdistricts = collect();
        if ($district) {
            $subdistricts = School::distinct()->where('district', $district)->whereNotNull('subdistrict')->orderBy('subdistrict')->pluck('subdistrict');
        }

        return view('sandbox::academic-results.index', compact(
            'schools',
            'selectedYear',
            'currentYear',
            'filter',
            'totalSchools',
            'submittedCount',
            'notSubmittedCount',
            'availableYears',
            'educationAreas',
            'ministries',
            'bureaus',
            'districts',
            'subdistricts',
            'educationArea',
            'ministry',
            'bureau',
            'district',
            'subdistrict',
            'educationAreaSummaries'
        ));
    }

    /**
     * Show the form for creating or editing academic result
     */
    public function edit(School $school, Request $request)
    {
        $year = $request->get('year', 2025); // ค.ศ.
        
        // Get or create academic result for this school and year
        $academicResult = AcademicResult::firstOrCreate(
            [
                'school_id' => $school->id,
                'academic_year' => $year
            ],
            [
                'has_nt_test' => true,
                'has_rt_test' => true,
                'has_onet_test' => true,
                'nt_math_score' => null,
                'nt_thai_score' => null,
                'rt_reading_score' => null,
                'rt_comprehension_score' => null,
                'onet_math_score' => null,
                'onet_thai_score' => null,
                'onet_english_score' => null,
                'onet_science_score' => null,
                'notes' => null,
                'submitted_at' => null
            ]
        );

        return view('sandbox::academic-results.form', compact('school', 'academicResult', 'year'));
    }

    /**
     * Update academic result
     */
    public function update(Request $request, School $school)
    {
        $year = $request->input('academic_year', 2025); // ค.ศ.

        // Validation rules
        $request->validate([
            'academic_year' => 'required|integer|min:2015|max:2100', // ค.ศ.
            'has_nt_test' => 'nullable|boolean',
            'has_rt_test' => 'nullable|boolean',
            'has_onet_test' => 'nullable|boolean',
            'nt_math_score' => 'nullable|numeric|min:0|max:100',
            'nt_thai_score' => 'nullable|numeric|min:0|max:100',
            'rt_reading_score' => 'nullable|numeric|min:0|max:100',
            'rt_comprehension_score' => 'nullable|numeric|min:0|max:100',
            'onet_math_score' => 'nullable|numeric|min:0|max:100',
            'onet_thai_score' => 'nullable|numeric|min:0|max:100',
            'onet_english_score' => 'nullable|numeric|min:0|max:100',
            'onet_science_score' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Get or create academic result
        $academicResult = AcademicResult::firstOrNew([
            'school_id' => $school->id,
            'academic_year' => $year
        ]);

        // Update test availability flags
        $academicResult->has_nt_test = $request->has('has_nt_test');
        $academicResult->has_rt_test = $request->has('has_rt_test');
        $academicResult->has_onet_test = $request->has('has_onet_test');

        // Update scores (set to null if test is not available)
        if ($academicResult->has_nt_test) {
            $academicResult->nt_math_score = $request->input('nt_math_score');
            $academicResult->nt_thai_score = $request->input('nt_thai_score');
        } else {
            $academicResult->nt_math_score = null;
            $academicResult->nt_thai_score = null;
        }

        if ($academicResult->has_rt_test) {
            $academicResult->rt_reading_score = $request->input('rt_reading_score');
            $academicResult->rt_comprehension_score = $request->input('rt_comprehension_score');
        } else {
            $academicResult->rt_reading_score = null;
            $academicResult->rt_comprehension_score = null;
        }

        if ($academicResult->has_onet_test) {
            $academicResult->onet_math_score = $request->input('onet_math_score');
            $academicResult->onet_thai_score = $request->input('onet_thai_score');
            $academicResult->onet_english_score = $request->input('onet_english_score');
            $academicResult->onet_science_score = $request->input('onet_science_score');
        } else {
            $academicResult->onet_math_score = null;
            $academicResult->onet_thai_score = null;
            $academicResult->onet_english_score = null;
            $academicResult->onet_science_score = null;
        }

        $academicResult->notes = $request->input('notes');

        // Mark as submitted if all required tests are complete
        if ($academicResult->isComplete()) {
            $academicResult->submitted_at = now();
        } else {
            $academicResult->submitted_at = null;
        }

        $academicResult->save();

        return redirect()
            ->route('sandbox.academic-results.index', ['year' => $year])
            ->with('success', 'บันทึกผลสัมฤทธิ์ทางการเรียนเรียบร้อยแล้ว');
    }

    /**
     * Mark result as submitted
     */
    public function submit(Request $request, School $school)
    {
        $year = $request->input('year', 2025); // ค.ศ.

        $academicResult = AcademicResult::where('school_id', $school->id)
            ->where('academic_year', $year)
            ->first();

        if ($academicResult && $academicResult->hasAnyScores()) {
            $academicResult->submitted_at = now();
            $academicResult->save();

            return back()->with('success', 'ยืนยันการส่งข้อมูลเรียบร้อยแล้ว');
        }

        return back()->with('error', 'กรุณากรอกข้อมูลคะแนนก่อนยืนยันการส่ง');
    }

    /**
     * Delete academic result
     */
    public function destroy(School $school, Request $request)
    {
        $year = $request->input('year', 2025); // ค.ศ.

        $academicResult = AcademicResult::where('school_id', $school->id)
            ->where('academic_year', $year)
            ->first();

        if ($academicResult) {
            $academicResult->delete();
            return back()->with('success', 'ลบข้อมูลเรียบร้อยแล้ว');
        }

        return back()->with('error', 'ไม่พบข้อมูลที่ต้องการลบ');
    }
    
    /**
     * Get chart data for dashboard
     */
    public function getChartData(Request $request)
    {
        $year = $request->get('year', 2025);
        $educationAreaFilter = $request->get('education_area', 'all');

        // Backward compatibility with legacy "department" query parameter
        if ($educationAreaFilter === 'all' && $request->filled('department')) {
            $educationAreaFilter = $request->get('department', 'all');
        }
        
        // Base query for academic results
        $query = AcademicResult::with('school')
            ->where('academic_year', $year)
            ->whereNotNull('submitted_at');
        
        // Filter by education area if specified
        if ($educationAreaFilter !== 'all') {
            $query->whereHas('school', function($q) use ($educationAreaFilter) {
                if ($educationAreaFilter === 'ไม่ระบุ') {
                    $q->whereNull('education_area')
                        ->orWhereRaw("TRIM(education_area) = ''");
                } else {
                    $q->whereRaw('TRIM(education_area) = ?', [$educationAreaFilter]);
                }
            });
        }
        
        $results = $query->get();
        
        // Calculate statistics
        $ntScores = [];
        $rtScores = [];
        $onetScores = [];
        
        foreach ($results as $result) {
            // NT Scores
            if ($result->has_nt_test && $result->hasNtScores()) {
                if ($result->nt_math_score) $ntScores[] = (float) $result->nt_math_score;
                if ($result->nt_thai_score) $ntScores[] = (float) $result->nt_thai_score;
            }
            
            // RT Scores
            if ($result->has_rt_test && $result->hasRtScores()) {
                if ($result->rt_reading_score) $rtScores[] = (float) $result->rt_reading_score;
                if ($result->rt_comprehension_score) $rtScores[] = (float) $result->rt_comprehension_score;
            }
            
            // O-NET Scores
            if ($result->has_onet_test && $result->hasOnetScores()) {
                if ($result->onet_math_score) $onetScores[] = (float) $result->onet_math_score;
                if ($result->onet_thai_score) $onetScores[] = (float) $result->onet_thai_score;
                if ($result->onet_english_score) $onetScores[] = (float) $result->onet_english_score;
                if ($result->onet_science_score) $onetScores[] = (float) $result->onet_science_score;
            }
        }
        
        // Calculate average scores
        $ntAvg = count($ntScores) > 0 ? round(array_sum($ntScores) / count($ntScores), 2) : 0;
        $rtAvg = count($rtScores) > 0 ? round(array_sum($rtScores) / count($rtScores), 2) : 0;
        $onetAvg = count($onetScores) > 0 ? round(array_sum($onetScores) / count($onetScores), 2) : 0;
        
        // Comparison by education area
        $educationAreaComparison = [];
        $educationAreaLabels = School::pluck('education_area')
            ->map(fn($value) => $this->normalizeEducationArea($value))
            ->unique()
            ->sort()
            ->values();

        if ($educationAreaLabels->isEmpty()) {
            $educationAreaLabels = collect(['ไม่ระบุ']);
        }

        foreach ($educationAreaLabels as $areaLabel) {
            $areaResults = AcademicResult::with('school')
                ->where('academic_year', $year)
                ->whereNotNull('submitted_at')
                ->whereHas('school', function($q) use ($areaLabel) {
                    if ($areaLabel === 'ไม่ระบุ') {
                        $q->whereNull('education_area')
                            ->orWhereRaw("TRIM(education_area) = ''");
                    } else {
                        $q->whereRaw('TRIM(education_area) = ?', [$areaLabel]);
                    }
                })->get();

            $areaNTMath = $areaNTThai = [];
            $areaRTReading = $areaRTComp = [];
            $areaONETMath = $areaONETThai = $areaONETEnglish = $areaONETScience = [];

            foreach ($areaResults as $result) {
                if ($result->has_nt_test && $result->hasNtScores()) {
                    if ($result->nt_math_score) $areaNTMath[] = (float) $result->nt_math_score;
                    if ($result->nt_thai_score) $areaNTThai[] = (float) $result->nt_thai_score;
                }
                if ($result->has_rt_test && $result->hasRtScores()) {
                    if ($result->rt_reading_score) $areaRTReading[] = (float) $result->rt_reading_score;
                    if ($result->rt_comprehension_score) $areaRTComp[] = (float) $result->rt_comprehension_score;
                }
                if ($result->has_onet_test && $result->hasOnetScores()) {
                    if ($result->onet_math_score) $areaONETMath[] = (float) $result->onet_math_score;
                    if ($result->onet_thai_score) $areaONETThai[] = (float) $result->onet_thai_score;
                    if ($result->onet_english_score) $areaONETEnglish[] = (float) $result->onet_english_score;
                    if ($result->onet_science_score) $areaONETScience[] = (float) $result->onet_science_score;
                }
            }

            $displayArea = $this->shortenEducationArea($areaLabel);
            $educationAreaComparison[$displayArea] = [
                'nt_count' => count($areaNTMath),
                'nt_math' => array_sum($areaNTMath),
                'nt_thai' => array_sum($areaNTThai),
                'rt_count' => count($areaRTReading),
                'rt_reading' => array_sum($areaRTReading),
                'rt_comprehension' => array_sum($areaRTComp),
                'onet_count' => count($areaONETMath),
                'onet_math' => array_sum($areaONETMath),
                'onet_thai' => array_sum($areaONETThai),
                'onet_english' => array_sum($areaONETEnglish),
                'onet_science' => array_sum($areaONETScience),
            ];
        }
        
        // Comparison by district (อำเภอ)
        $areaComparison = [];
        $districts = School::distinct()->whereNotNull('district')->pluck('district');
        
        foreach ($districts as $district) {
            $districtResults = AcademicResult::with('school')
                ->where('academic_year', $year)
                ->whereNotNull('submitted_at')
                ->whereHas('school', function($q) use ($district) {
                    $q->where('district', $district);
                })->get();
            
            $districtNTMath = $districtNTThai = [];
            $districtRTReading = $districtRTComp = [];
            $districtONETMath = $districtONETThai = $districtONETEnglish = $districtONETScience = [];
            
            foreach ($districtResults as $result) {
                if ($result->has_nt_test && $result->hasNtScores()) {
                    if ($result->nt_math_score) $districtNTMath[] = (float) $result->nt_math_score;
                    if ($result->nt_thai_score) $districtNTThai[] = (float) $result->nt_thai_score;
                }
                if ($result->has_rt_test && $result->hasRtScores()) {
                    if ($result->rt_reading_score) $districtRTReading[] = (float) $result->rt_reading_score;
                    if ($result->rt_comprehension_score) $districtRTComp[] = (float) $result->rt_comprehension_score;
                }
                if ($result->has_onet_test && $result->hasOnetScores()) {
                    if ($result->onet_math_score) $districtONETMath[] = (float) $result->onet_math_score;
                    if ($result->onet_thai_score) $districtONETThai[] = (float) $result->onet_thai_score;
                    if ($result->onet_english_score) $districtONETEnglish[] = (float) $result->onet_english_score;
                    if ($result->onet_science_score) $districtONETScience[] = (float) $result->onet_science_score;
                }
            }
            
            $areaComparison[$district] = [
                'nt_count' => count($districtNTMath),
                'nt_math' => array_sum($districtNTMath),
                'nt_thai' => array_sum($districtNTThai),
                'rt_count' => count($districtRTReading),
                'rt_reading' => array_sum($districtRTReading),
                'rt_comprehension' => array_sum($districtRTComp),
                'onet_count' => count($districtONETMath),
                'onet_math' => array_sum($districtONETMath),
                'onet_thai' => array_sum($districtONETThai),
                'onet_english' => array_sum($districtONETEnglish),
                'onet_science' => array_sum($districtONETScience),
            ];
        }
        
        // Comparison by district (ตำบล)
        $districtComparison = [];
        $districts = School::distinct()->whereNotNull('district')->pluck('district');
        
        foreach ($districts as $district) {
            $districtResults = AcademicResult::with('school')
                ->where('academic_year', $year)
                ->whereNotNull('submitted_at')
                ->whereHas('school', function($q) use ($district) {
                    $q->where('district', $district);
                })->get();
            
            $distNTMath = $distNTThai = [];
            $distRTReading = $distRTComp = [];
            $distONETMath = $distONETThai = $distONETEnglish = $distONETScience = [];
            
            foreach ($districtResults as $result) {
                if ($result->has_nt_test && $result->hasNtScores()) {
                    if ($result->nt_math_score) $distNTMath[] = (float) $result->nt_math_score;
                    if ($result->nt_thai_score) $distNTThai[] = (float) $result->nt_thai_score;
                }
                if ($result->has_rt_test && $result->hasRtScores()) {
                    if ($result->rt_reading_score) $distRTReading[] = (float) $result->rt_reading_score;
                    if ($result->rt_comprehension_score) $distRTComp[] = (float) $result->rt_comprehension_score;
                }
                if ($result->has_onet_test && $result->hasOnetScores()) {
                    if ($result->onet_math_score) $distONETMath[] = (float) $result->onet_math_score;
                    if ($result->onet_thai_score) $distONETThai[] = (float) $result->onet_thai_score;
                    if ($result->onet_english_score) $distONETEnglish[] = (float) $result->onet_english_score;
                    if ($result->onet_science_score) $distONETScience[] = (float) $result->onet_science_score;
                }
            }
            
            $districtComparison[$district] = [
                'nt_count' => count($distNTMath),
                'nt_math' => array_sum($distNTMath),
                'nt_thai' => array_sum($distNTThai),
                'rt_count' => count($distRTReading),
                'rt_reading' => array_sum($distRTReading),
                'rt_comprehension' => array_sum($distRTComp),
                'onet_count' => count($distONETMath),
                'onet_math' => array_sum($distONETMath),
                'onet_thai' => array_sum($distONETThai),
                'onet_english' => array_sum($distONETEnglish),
                'onet_science' => array_sum($distONETScience),
            ];
        }
        
        // Score distribution (for bar chart) - REMOVED
        $scoreRanges = [
            '0-20' => 0,
            '21-40' => 0,
            '41-60' => 0,
            '61-80' => 0,
            '81-100' => 0
        ];
        
        $allScores = array_merge($ntScores, $rtScores, $onetScores);
        foreach ($allScores as $score) {
            if ($score <= 20) $scoreRanges['0-20']++;
            elseif ($score <= 40) $scoreRanges['21-40']++;
            elseif ($score <= 60) $scoreRanges['41-60']++;
            elseif ($score <= 80) $scoreRanges['61-80']++;
            else $scoreRanges['81-100']++;
        }
        
        // Subject-wise average for each test type
        $subjectAverages = [
            'NT' => [
                'math' => 0,
                'thai' => 0
            ],
            'RT' => [
                'reading' => 0,
                'comprehension' => 0
            ],
            'O-NET' => [
                'math' => 0,
                'thai' => 0,
                'english' => 0,
                'science' => 0
            ]
        ];
        
        $ntMath = $ntThai = [];
        $rtReading = $rtComprehension = [];
        $onetMath = $onetThai = $onetEnglish = $onetScience = [];
        
        foreach ($results as $result) {
            if ($result->has_nt_test) {
                if ($result->nt_math_score) $ntMath[] = (float) $result->nt_math_score;
                if ($result->nt_thai_score) $ntThai[] = (float) $result->nt_thai_score;
            }
            if ($result->has_rt_test) {
                if ($result->rt_reading_score) $rtReading[] = (float) $result->rt_reading_score;
                if ($result->rt_comprehension_score) $rtComprehension[] = (float) $result->rt_comprehension_score;
            }
            if ($result->has_onet_test) {
                if ($result->onet_math_score) $onetMath[] = (float) $result->onet_math_score;
                if ($result->onet_thai_score) $onetThai[] = (float) $result->onet_thai_score;
                if ($result->onet_english_score) $onetEnglish[] = (float) $result->onet_english_score;
                if ($result->onet_science_score) $onetScience[] = (float) $result->onet_science_score;
            }
        }
        
        $subjectAverages['NT']['math'] = count($ntMath) > 0 ? round(array_sum($ntMath) / count($ntMath), 2) : 0;
        $subjectAverages['NT']['thai'] = count($ntThai) > 0 ? round(array_sum($ntThai) / count($ntThai), 2) : 0;
        $subjectAverages['RT']['reading'] = count($rtReading) > 0 ? round(array_sum($rtReading) / count($rtReading), 2) : 0;
        $subjectAverages['RT']['comprehension'] = count($rtComprehension) > 0 ? round(array_sum($rtComprehension) / count($rtComprehension), 2) : 0;
        $subjectAverages['O-NET']['math'] = count($onetMath) > 0 ? round(array_sum($onetMath) / count($onetMath), 2) : 0;
        $subjectAverages['O-NET']['thai'] = count($onetThai) > 0 ? round(array_sum($onetThai) / count($onetThai), 2) : 0;
        $subjectAverages['O-NET']['english'] = count($onetEnglish) > 0 ? round(array_sum($onetEnglish) / count($onetEnglish), 2) : 0;
        $subjectAverages['O-NET']['science'] = count($onetScience) > 0 ? round(array_sum($onetScience) / count($onetScience), 2) : 0;
        
        return response()->json([
            'success' => true,
            'data' => [
                'averages' => [
                    'nt' => $ntAvg,
                    'rt' => $rtAvg,
                    'onet' => $onetAvg
                ],
                'subjects' => $subjectAverages,
                'comparisons' => [
                    'education_areas' => $educationAreaComparison,
                    'areas' => $areaComparison,
                    'districts' => $districtComparison
                ],
                'statistics' => [
                    'total_schools' => $results->unique('school_id')->count(),
                    'total_scores' => count($allScores),
                    'nt_count' => count($ntScores),
                    'nt_total' => array_sum($ntScores),
                    'rt_count' => count($rtScores),
                    'rt_total' => array_sum($rtScores),
                    'onet_count' => count($onetScores),
                    'onet_total' => array_sum($onetScores)
                ]
            ]
        ]);
    }

    /**
     * Export academic results to CSV
     * 
     * @param Request $request
     * @param ExportService $exportService
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportCSV(Request $request, ExportService $exportService)
    {
        // Get year filter
        $year = $request->get('year', date('Y') + 543);
        
        // Build query with filters
        $query = AcademicResult::with('school')
            ->where('academic_year', $year);
        
        // Apply school filters
        $educationArea = $request->get('education_area', $request->get('department'));
        if ($educationArea) {
            $query->whereHas('school', function($q) use ($educationArea) {
                if ($educationArea === 'ไม่ระบุ') {
                    $q->whereNull('education_area')
                        ->orWhereRaw("TRIM(education_area) = ''");
                } else {
                    $q->whereRaw('TRIM(education_area) = ?', [$educationArea]);
                }
            });
        }
        if ($ministry = $request->get('ministry')) {
            $query->whereHas('school', fn($q) => $q->where('ministry_affiliation', $ministry));
        }
        if ($bureau = $request->get('bureau')) {
            $query->whereHas('school', fn($q) => $q->where('bureau_affiliation', $bureau));
        }
        if ($district = $request->get('district')) {
            $query->whereHas('school', fn($q) => $q->where('district', $district));
        }
        if ($subdistrict = $request->get('subdistrict')) {
            $query->whereHas('school', fn($q) => $q->where('subdistrict', $subdistrict));
        }
        if ($filter = $request->get('filter')) {
            if ($filter === 'submitted') {
                $query->whereNotNull('submitted_at');
            } elseif ($filter === 'not_submitted') {
                $query->whereNull('submitted_at');
            }
        }
        
        $results = $query->get();
        
        // Prepare data for export
        $exportData = $exportService->prepareAcademicResultsData($results);
        
        // Generate filename with timestamp
        $filename = 'academic_results_' . $year . '_' . date('Y-m-d_His') . '.csv';
        
        return $exportService->exportToCSV(
            $exportData['data'],
            $exportData['headers'],
            $filename
        );
    }

    /**
     * Export academic results to Excel
     * 
     * @param Request $request
     * @param ExportService $exportService
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportExcel(Request $request, ExportService $exportService)
    {
        // Get year filter
        $year = $request->get('year', date('Y') + 543);
        
        // Build query with filters
        $query = AcademicResult::with('school')
            ->where('academic_year', $year);
        
        // Apply school filters
        $educationArea = $request->get('education_area', $request->get('department'));
        if ($educationArea) {
            $query->whereHas('school', function($q) use ($educationArea) {
                if ($educationArea === 'ไม่ระบุ') {
                    $q->whereNull('education_area')
                        ->orWhereRaw("TRIM(education_area) = ''");
                } else {
                    $q->whereRaw('TRIM(education_area) = ?', [$educationArea]);
                }
            });
        }
        if ($ministry = $request->get('ministry')) {
            $query->whereHas('school', fn($q) => $q->where('ministry_affiliation', $ministry));
        }
        if ($bureau = $request->get('bureau')) {
            $query->whereHas('school', fn($q) => $q->where('bureau_affiliation', $bureau));
        }
        if ($district = $request->get('district')) {
            $query->whereHas('school', fn($q) => $q->where('district', $district));
        }
        if ($subdistrict = $request->get('subdistrict')) {
            $query->whereHas('school', fn($q) => $q->where('subdistrict', $subdistrict));
        }
        if ($filter = $request->get('filter')) {
            if ($filter === 'submitted') {
                $query->whereNotNull('submitted_at');
            } elseif ($filter === 'not_submitted') {
                $query->whereNull('submitted_at');
            }
        }
        
        $results = $query->get();
        
        // Prepare data for export
        $exportData = $exportService->prepareAcademicResultsData($results);
        
        // Generate filename with timestamp
        $filename = 'academic_results_' . $year . '_' . date('Y-m-d_His') . '.xlsx';
        
        return $exportService->exportToExcel(
            $exportData['data'],
            $exportData['headers'],
            $filename,
            'ผลสัมฤทธิ์ปี ' . $year
        );
    }
}

