<?php

namespace Modules\Sandbox\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Modules\Sandbox\Entities\Experiment;
use Modules\Sandbox\Entities\ExperimentScenario;
use Modules\Sandbox\Entities\ExperimentResult;
use Modules\Sandbox\Entities\School;
use Modules\Sandbox\Entities\AcademicResult;
use Modules\Sandbox\Services\AnalyticsService;
use Modules\Sandbox\Services\ExperimentService;
use Modules\Sandbox\Http\Requests\StoreExperimentRequest;

class ExperimentController extends Controller
{
    protected $analyticsService;
    protected $experimentService;

    public function __construct(AnalyticsService $analyticsService, ExperimentService $experimentService)
    {
        $this->analyticsService = $analyticsService;
        $this->experimentService = $experimentService;
        $this->middleware('auth')->except(['show', 'share']);
    }

    /**
     * Display a listing of experiments
     */
    public function index(Request $request)
    {
        $query = Experiment::with(['creator', 'scenarios', 'results'])
            ->orderBy('created_at', 'desc');

        // Filter by user's experiments or public
        if ($request->get('filter') === 'my') {
            $query->byUser(Auth::id());
        } elseif ($request->get('filter') === 'public') {
            $query->public();
        } else {
            // Show all: user's experiments + public experiments
            $query->where(function($q) {
                $q->byUser(Auth::id())
                  ->orWhere('is_public', true);
            });
        }

        // Filter by status
        if ($status = $request->get('status')) {
            $query->byStatus($status);
        }

        $experiments = $query->paginate(12);

        // Get statistics
        $stats = [
            'total' => Experiment::count(),
            'my_experiments' => Experiment::byUser(Auth::id())->count(),
            'public_experiments' => Experiment::public()->count(),
            'draft' => Experiment::byStatus('draft')->count(),
            'running' => Experiment::byStatus('running')->count(),
            'completed' => Experiment::byStatus('completed')->count(),
        ];

        return view('sandbox::experiments.index', compact('experiments', 'stats'));
    }

    /**
     * Show the form for creating a new experiment
     */
    public function create()
    {
        // Get available base years from academic results
        $availableYears = AcademicResult::distinct()
            ->pluck('academic_year')
            ->sort()
            ->values();

        // Get statistics for current data
        $currentYear = date('Y');
        $schoolsCount = School::count();
        $resultsCount = AcademicResult::where('academic_year', $currentYear)
            ->whereNotNull('submitted_at')
            ->count();

        // Get filter options
        $schools = School::orderBy('name')->get(['id', 'name', 'department', 'district', 'subdistrict']);
        $departments = School::distinct()->pluck('department')->filter()->sort()->values();
        $districts = School::distinct()->pluck('district')->filter()->sort()->values();
        $subDistricts = School::distinct()->pluck('subdistrict')->filter()->sort()->values();

        return view('sandbox::experiments.create', compact(
            'availableYears',
            'currentYear',
            'schoolsCount',
            'resultsCount',
            'schools',
            'departments',
            'districts',
            'subDistricts'
        ));
    }

    /**
     * Store a newly created experiment
     */
    public function store(StoreExperimentRequest $request)
    {
        DB::beginTransaction();
        try {
            // Create snapshot of base data with filters
            $baseYear = $request->input('base_year', date('Y'));
            
            $filters = [];
            if ($request->filled('baseline_filter_type')) {
                $filterType = $request->input('baseline_filter_type');
                $filterValue = $request->input('baseline_filter_value');
                
                if ($filterType && $filterValue) {
                    $filters[$filterType] = $filterValue;
                }
            }
            
            $baseSnapshot = $this->experimentService->createDataSnapshot($baseYear, $filters);

            // Create experiment
            $experiment = Experiment::create([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'type' => $request->input('type', 'what_if'),
                'created_by' => Auth::id(),
                'base_year' => $baseYear,
                'base_data_snapshot' => $baseSnapshot,
                'baseline_filters' => $filters,
                'settings' => $request->input('settings', []),
                'is_public' => $request->boolean('is_public', false),
                'status' => 'draft',
            ]);

            // Create default scenario (baseline) - NO PARAMETERS
            ExperimentScenario::create([
                'experiment_id' => $experiment->id,
                'name' => 'Baseline',
                'description' => 'ข้อมูลสถานการณ์ปัจจุบันตามที่เลือก (ไม่มีการปรับเปลี่ยน)',
                'parameters' => [],
                'order' => 1,
            ]);

            DB::commit();

            return redirect()
                ->route('sandbox.experiments.edit', $experiment)
                ->with('success', '✅ สร้าง Experiment สำเร็จ! เริ่มเพิ่ม Scenarios ได้เลย');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', '❌ เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified experiment
     */
    public function show(Experiment $experiment)
    {
        // Check access permission
        if (!$experiment->is_public && (!Auth::check() || Auth::id() !== $experiment->created_by)) {
            abort(403, 'คุณไม่มีสิทธิ์เข้าถึง Experiment นี้');
        }

        $experiment->load(['creator', 'scenarios.results', 'results']);

        // Get comparison data for charts
        $comparisonData = $this->experimentService->getComparisonData($experiment);

        return view('sandbox::experiments.show', compact('experiment', 'comparisonData'));
    }

    /**
     * Show the form for editing the specified experiment
     */
    public function edit(Experiment $experiment)
    {
        // Check permission
        if (Auth::id() !== $experiment->created_by) {
            abort(403, 'คุณไม่มีสิทธิ์แก้ไข Experiment นี้');
        }

        if (!$experiment->isEditable()) {
            return redirect()
                ->route('sandbox.experiments.show', $experiment)
                ->with('warning', '⚠️ Experiment นี้ไม่สามารถแก้ไขได้แล้ว');
        }

        $experiment->load(['scenarios.results']);

        // Get available parameters for scenarios
        $availableParameters = [
            'teacher_ratio' => [
                'label' => 'อัตราส่วนนักเรียน:ครู',
                'type' => 'number',
                'unit' => 'คน/ครู',
                'min' => 10,
                'max' => 50,
                'default' => 20,
                'impact' => 'medium',
            ],
            'budget_per_student' => [
                'label' => 'งบประมาณต่อนักเรียน',
                'type' => 'number',
                'unit' => 'บาท/คน/ปี',
                'min' => 1000,
                'max' => 50000,
                'default' => 10000,
                'impact' => 'low',
            ],
            'training_hours' => [
                'label' => 'ชั่วโมงอบรมครู',
                'type' => 'number',
                'unit' => 'ชม./ปี',
                'min' => 0,
                'max' => 200,
                'default' => 40,
                'impact' => 'high',
            ],
            'digital_readiness' => [
                'label' => 'ความพร้อมด้านเทคโนโลยี',
                'type' => 'percentage',
                'unit' => '%',
                'min' => 0,
                'max' => 100,
                'default' => 50,
                'impact' => 'medium',
            ],
            'infrastructure_score' => [
                'label' => 'คะแนนสิ่งอำนวยความสะดวก',
                'type' => 'percentage',
                'unit' => '%',
                'min' => 0,
                'max' => 100,
                'default' => 60,
                'impact' => 'low',
            ],
        ];

        return view('sandbox::experiments.edit', compact('experiment', 'availableParameters'));
    }

    /**
     * Update the specified experiment
     */
    public function update(Request $request, Experiment $experiment)
    {
        // Check permission
        if (Auth::id() !== $experiment->created_by) {
            abort(403);
        }

        if (!$experiment->isEditable()) {
            return back()->with('error', '❌ ไม่สามารถแก้ไข Experiment ที่เสร็จสิ้นแล้ว');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_public' => 'boolean',
            'settings' => 'nullable|array',
        ]);

        $experiment->update($validated);

        return back()->with('success', '✅ อัพเดท Experiment สำเร็จ');
    }

    /**
     * Remove the specified experiment
     */
    public function destroy(Experiment $experiment)
    {
        // Check permission
        if (Auth::id() !== $experiment->created_by) {
            abort(403);
        }

        $experiment->delete();

        return redirect()
            ->route('sandbox.experiments.index')
            ->with('success', '✅ ลบ Experiment สำเร็จ');
    }

    /**
     * Add a new scenario to the experiment
     */
    public function addScenario(Request $request, Experiment $experiment)
    {
        // Check permission
        if (Auth::id() !== $experiment->created_by) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (!$experiment->isEditable()) {
            return response()->json(['error' => 'Experiment is not editable'], 400);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parameters' => 'required|array',
        ]);

        $lastOrder = $experiment->scenarios()->max('order') ?? 0;

        $scenario = ExperimentScenario::create([
            'experiment_id' => $experiment->id,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'parameters' => $validated['parameters'],
            'order' => $lastOrder + 1,
        ]);

        return response()->json([
            'success' => true,
            'scenario' => $scenario->load('results'),
            'message' => '✅ เพิ่ม Scenario สำเร็จ',
        ]);
    }

    /**
     * Add or update a parameter for a scenario (used by drag-and-drop)
     */
    public function addParameterToScenario(Request $request, Experiment $experiment, ExperimentScenario $scenario)
    {
        // Check permission
        if (Auth::id() !== $experiment->created_by) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if ($scenario->experiment_id !== $experiment->id) {
            return response()->json(['success' => false, 'message' => 'Scenario mismatch'], 400);
        }

        $validated = $request->validate([
            'param_key' => 'required|string',
            'value' => 'nullable'
        ]);

        try {
            $parameters = $scenario->parameters ?? [];
            $parameters[$validated['param_key']] = $validated['value'] ?? ($parameters[$validated['param_key']] ?? null);
            $scenario->parameters = $parameters;
            $scenario->save();

            return response()->json([
                'success' => true,
                'message' => '✅ เพิ่มพารามิเตอร์สำเร็จ',
                'scenario' => $scenario->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove a parameter from a scenario (drag to delete)
     */
    public function removeParameterFromScenario(Experiment $experiment, ExperimentScenario $scenario, $paramKey)
    {
        // Check permission
        if (Auth::id() !== $experiment->created_by) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if ($scenario->experiment_id !== $experiment->id) {
            return response()->json(['success' => false, 'message' => 'Scenario mismatch'], 400);
        }

        try {
            $parameters = $scenario->parameters ?? [];
            
            if (!isset($parameters[$paramKey])) {
                return response()->json([
                    'success' => false,
                    'message' => 'ไม่พบพารามิเตอร์นี้'
                ], 404);
            }

            unset($parameters[$paramKey]);
            $scenario->parameters = $parameters;
            $scenario->save();

            return response()->json([
                'success' => true,
                'message' => '✅ ลบพารามิเตอร์สำเร็จ'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Run calculations for a scenario
     */
    public function runScenario(Request $request, Experiment $experiment, ExperimentScenario $scenario)
    {
        // Check permission
        if (Auth::id() !== $experiment->created_by) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($scenario->experiment_id !== $experiment->id) {
            return response()->json(['error' => 'Scenario does not belong to this experiment'], 400);
        }

        try {
            // Run analytics calculations
            $results = $this->analyticsService->runScenarioCalculations(
                $experiment->base_data_snapshot,
                $scenario->parameters
            );

            // Store or update results
            ExperimentResult::updateOrCreate(
                [
                    'experiment_id' => $experiment->id,
                    'scenario_id' => $scenario->id,
                ],
                [
                    'metrics' => $results,
                    'calculated_at' => now(),
                ]
            );

            // Update experiment status
            if ($experiment->status === 'draft') {
                $experiment->update(['status' => 'running']);
            }

            return response()->json([
                'success' => true,
                'results' => $results,
                'message' => '✅ คำนวณเสร็จสิ้น',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Run all scenarios in the experiment
     */
    public function runAllScenarios(Experiment $experiment)
    {
        // Check permission
        if (Auth::id() !== $experiment->created_by) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        DB::beginTransaction();
        try {
            $scenarios = $experiment->scenarios;
            $resultsCount = 0;

            foreach ($scenarios as $scenario) {
                $results = $this->analyticsService->runScenarioCalculations(
                    $experiment->base_data_snapshot,
                    $scenario->parameters
                );

                ExperimentResult::updateOrCreate(
                    [
                        'experiment_id' => $experiment->id,
                        'scenario_id' => $scenario->id,
                    ],
                    [
                        'metrics' => $results,
                        'calculated_at' => now(),
                    ]
                );

                $resultsCount++;
            }

            // Update experiment status
            $experiment->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "✅ คำนวณทั้งหมด {$resultsCount} scenarios สำเร็จ",
                'results_count' => $resultsCount,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update scenario order
     */
    public function updateScenarioOrder(Request $request, Experiment $experiment)
    {
        // Check permission
        if (Auth::id() !== $experiment->created_by) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:experiment_scenarios,id',
            'orders.*.order' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            foreach ($validated['orders'] as $item) {
                ExperimentScenario::where('id', $item['id'])
                    ->where('experiment_id', $experiment->id)
                    ->update(['order' => $item['order']]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => '✅ อัพเดทลำดับสำเร็จ',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get a scenario (for editing)
     */
    public function getScenario(Experiment $experiment, ExperimentScenario $scenario)
    {
        // Check permission
        if (!$experiment->is_public && (!Auth::check() || Auth::id() !== $experiment->created_by)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($scenario->experiment_id !== $experiment->id) {
            return response()->json(['error' => 'Scenario does not belong to this experiment'], 400);
        }

        return response()->json([
            'success' => true,
            'scenario' => $scenario,
        ]);
    }

    /**
     * Update a scenario
     */
    public function updateScenario(Request $request, Experiment $experiment, ExperimentScenario $scenario)
    {
        // Check permission
        if (Auth::id() !== $experiment->created_by) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (!$experiment->isEditable()) {
            return response()->json(['error' => 'Experiment is not editable'], 400);
        }

        if ($scenario->experiment_id !== $experiment->id) {
            return response()->json(['error' => 'Scenario does not belong to this experiment'], 400);
        }

        // Don't allow editing baseline scenario
        if ($scenario->order === 1) {
            return response()->json(['error' => 'Cannot edit baseline scenario'], 400);
        }

        // Validate input
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'parameters' => 'sometimes|array',
        ]);

        // Update scenario
        if (isset($validated['name'])) {
            $scenario->name = $validated['name'];
        }
        
        if (isset($validated['description'])) {
            $scenario->description = $validated['description'];
        }
        
        if (isset($validated['parameters'])) {
            $scenario->parameters = $validated['parameters'];
        }
        
        $scenario->save();

        return response()->json([
            'success' => true,
            'message' => '✅ แก้ไข Scenario สำเร็จ',
            'scenario' => $scenario,
        ]);
    }

    /**
     * Delete a scenario
     */
    public function deleteScenario(Experiment $experiment, ExperimentScenario $scenario)
    {
        // Check permission
        if (Auth::id() !== $experiment->created_by) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (!$experiment->isEditable()) {
            return response()->json(['error' => 'Experiment is not editable'], 400);
        }

        if ($scenario->experiment_id !== $experiment->id) {
            return response()->json(['error' => 'Scenario does not belong to this experiment'], 400);
        }

        // Don't allow deleting baseline scenario
        if ($scenario->order === 1) {
            return response()->json(['error' => 'Cannot delete baseline scenario'], 400);
        }

        $scenario->delete();

        return response()->json([
            'success' => true,
            'message' => '✅ ลบ Scenario สำเร็จ',
        ]);
    }

    /**
     * Get comparison data for charts
     */
    public function getComparisonData(Experiment $experiment): JsonResponse
    {
        // Check access permission
        if (!$experiment->is_public && (!Auth::check() || Auth::id() !== $experiment->created_by)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $comparisonData = $this->experimentService->getComparisonData($experiment);

        return response()->json($comparisonData);
    }

    /**
     * Share experiment via token
     */
    public function share(string $token)
    {
        $experiment = Experiment::where('share_token', $token)
            ->where('is_public', true)
            ->firstOrFail();

        $experiment->load(['creator', 'scenarios.results', 'results']);

        // Get comparison data
        $comparisonData = $this->experimentService->getComparisonData($experiment);

        return view('sandbox::experiments.share', compact('experiment', 'comparisonData'));
    }

    /**
     * Duplicate an experiment
     */
    public function duplicate(Experiment $experiment)
    {
        // Check access permission
        if (!$experiment->is_public && (!Auth::check() || Auth::id() !== $experiment->created_by)) {
            abort(403);
        }

        DB::beginTransaction();
        try {
            // Create new experiment
            $newExperiment = $experiment->replicate();
            $newExperiment->name = $experiment->name . ' (Copy)';
            $newExperiment->created_by = Auth::id();
            $newExperiment->status = 'draft';
            $newExperiment->completed_at = null;
            $newExperiment->is_public = false;
            $newExperiment->share_token = \Illuminate\Support\Str::random(32);
            $newExperiment->save();

            // Duplicate scenarios
            foreach ($experiment->scenarios as $scenario) {
                $newScenario = $scenario->replicate();
                $newScenario->experiment_id = $newExperiment->id;
                $newScenario->save();
            }

            DB::commit();

            return redirect()
                ->route('sandbox.experiments.edit', $newExperiment)
                ->with('success', '✅ คัดลอก Experiment สำเร็จ');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', '❌ เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    /**
     * Export experiment to PDF
     */
    public function exportPDF(Experiment $experiment)
    {
        // Check access permission
        if (!$experiment->is_public && Auth::id() !== $experiment->created_by) {
            abort(403, 'You do not have permission to export this experiment.');
        }

        // Load relationships
        $experiment->load(['creator', 'scenarios.results']);

        // Generate PDF using DomPDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('sandbox::experiments.pdf', [
            'experiment' => $experiment
        ]);

        // Set paper size and orientation
        $pdf->setPaper('a4', 'portrait');

        // Set options for Thai font support
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'DejaVu Sans'
        ]);

        // Generate filename
        $filename = 'experiment_' . $experiment->id . '_' . date('Ymd_His') . '.pdf';

        // Download PDF
        return $pdf->download($filename);
    }

    /**
     * Send experiment via email
     */
    public function sendEmail(Request $request, Experiment $experiment)
    {
        $request->validate([
            'email' => 'required|email',
            'message' => 'nullable|string|max:500'
        ]);

        // Check access permission
        if (!$experiment->is_public && Auth::id() !== $experiment->created_by) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to share this experiment.'
            ], 403);
        }

        try {
            // Generate PDF
            $experiment->load(['creator', 'scenarios.results']);
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('sandbox::experiments.pdf', [
                'experiment' => $experiment
            ]);

            // Send email with PDF attachment
            Mail::send('sandbox::emails.experiment_share', [
                'experiment' => $experiment,
                'sender' => Auth::user(),
                'custom_message' => $request->message
            ], function($message) use ($request, $experiment, $pdf) {
                $message->to($request->email)
                        ->subject('Shared Experiment: ' . $experiment->name)
                        ->attachData($pdf->output(), 'experiment_' . $experiment->id . '.pdf');
            });

            return response()->json([
                'success' => true,
                'message' => '✅ ส่งอีเมลสำเร็จ'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export experiment to Excel, optionally embedding chart images sent as base64
     */
    public function exportExcel(Request $request, Experiment $experiment)
    {
        // Check access permission
        if (!$experiment->is_public && Auth::id() !== $experiment->created_by) {
            abort(403, 'You do not have permission to export this experiment.');
        }

        $request->validate([
            'charts' => 'nullable|array',
            'charts.*.name' => 'nullable|string',
            'charts.*.dataUrl' => 'nullable|string',
            'include_table' => 'boolean'
        ]);

        $experiment->load(['creator', 'scenarios.results']);

        // Create spreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Experiment Report');

        // Header
        $sheet->setCellValue('A1', $experiment->name);
        $sheet->mergeCells('A1:D1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);

        // Meta
        $sheet->setCellValue('A3', 'Description');
        $sheet->setCellValue('B3', $experiment->description ?: '-');
        $sheet->setCellValue('A4', 'Creator');
        $sheet->setCellValue('B4', $experiment->creator->name ?? 'Unknown');
        $sheet->setCellValue('A5', 'Base Year');
        $sheet->setCellValue('B5', $experiment->base_year + 543);

        $row = 7;

        // Insert comparison table
        if ($request->boolean('include_table', true)) {
            $sheet->setCellValue("A{$row}", 'Scenario');
            $sheet->setCellValue("B{$row}", 'Avg Score');
            $sheet->setCellValue("C{$row}", 'Improvement %');
            $sheet->getStyle("A{$row}:C{$row}")->getFont()->setBold(true);
            $row++;

            foreach ($experiment->scenarios as $scenario) {
                $metrics = ($scenario->results && $scenario->results->first()) ? $scenario->results->first()->metrics : null;
                $sheet->setCellValue("A{$row}", $scenario->name);
                $sheet->setCellValue("B{$row}", $metrics['avg_score'] ?? null);
                $sheet->setCellValue("C{$row}", $metrics['improvement_percent'] ?? null);
                $row++;
            }

            $row += 2;
        }

        // Embed chart images if provided
        if ($charts = $request->input('charts', [])) {
            foreach ($charts as $chart) {
                if (empty($chart['dataUrl'])) continue;

                // data:image/png;base64,....
                if (preg_match('/^data:image\/(png|jpeg);base64,/', $chart['dataUrl'], $matches)) {
                    $type = $matches[1] === 'jpeg' ? 'jpeg' : 'png';
                    $data = preg_replace('#^data:image/[^;]+;base64,#', '', $chart['dataUrl']);
                    $imageData = base64_decode($data);

                    // Save to temporary file
                    $tmpFile = sys_get_temp_dir() . '/' . uniqid('chart_') . '.' . $type;
                    file_put_contents($tmpFile, $imageData);

                    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                    $drawing->setPath($tmpFile);
                    $drawing->setCoordinates("A{$row}");
                    $drawing->setOffsetX(10);
                    $drawing->setOffsetY(10);
                    $drawing->setHeight(300);
                    $drawing->setWorksheet($sheet);

                    // advance rows
                    $row += 18;
                }
            }
        }

        // Output
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        $filename = 'experiment_' . $experiment->id . '_' . date('Ymd_His') . '.xlsx';

        // Stream to browser
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    /**
     * Send experiment PDF to multiple recipients (batch)
     */
    public function sendBatchEmail(Request $request, Experiment $experiment)
    {
        $request->validate([
            'emails' => 'required|array|min:1',
            'emails.*' => 'required|email',
            'message' => 'nullable|string|max:1000'
        ]);

        if (!$experiment->is_public && Auth::id() !== $experiment->created_by) {
            return response()->json(['success' => false, 'message' => 'You do not have permission to share this experiment.'], 403);
        }

        try {
            $experiment->load(['creator', 'scenarios.results']);
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('sandbox::experiments.pdf', ['experiment' => $experiment]);

            foreach ($request->input('emails') as $email) {
                // Optionally dispatch to queue if available
                Mail::send('sandbox::emails.experiment_share', [
                    'experiment' => $experiment,
                    'sender' => Auth::user(),
                    'custom_message' => $request->input('message')
                ], function($message) use ($email, $experiment, $pdf) {
                    $message->to($email)
                            ->subject('Shared Experiment: ' . $experiment->name)
                            ->attachData($pdf->output(), 'experiment_' . $experiment->id . '.pdf');
                });
            }

            return response()->json(['success' => true, 'message' => '✅ ส่งอีเมลเรียบร้อยทั้งหมด ' . count($request->input('emails')) . ' รายการ']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => '❌ Error: ' . $e->getMessage()], 500);
        }
    }
}
