<?php

namespace Modules\Sandbox\Http\Controllers;

use App\CustomClass\ItopCyberUpload;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Modules\Sandbox\Entities\School;
use Modules\Sandbox\Entities\SchoolInnovation;
use Modules\Sandbox\Entities\SchoolVisionVideo;
use Modules\Sandbox\Entities\Publication;
use Modules\Sandbox\Services\ExportService;

class SandboxController extends Controller
{
    /**
     * Display a listing of schools.
     * @return Renderable|JsonResponse
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = School::with('innovations');
            
            // Filter by department
            if ($request->has('department') && $request->department != '') {
                $query->where('department', $request->department);
            }
            
            // Filter by search term
            if ($request->has('search') && $request->search != '') {
                $query->where('name', 'like', '%' . $request->search . '%');
            }
            
            $page = $request->get('page', 1);
            $perPage = 12;
            
            // Get total count before applying pagination
            $totalCount = $query->count();
            
            $schools = $query->orderBy('name')
                ->skip(($page - 1) * $perPage)
                ->take($perPage)
                ->get();
            
            $hasMore = $totalCount > ($page * $perPage);
            
            return response()->json([
                'schools' => $schools,
                'hasMore' => $hasMore
            ]);
        }
        
        // Initial page load - get all departments for filter
        $departments = School::select('department')
            ->distinct()
            ->orderBy('department')
            ->pluck('department');
        
        return view('sandbox::schools.index', compact('departments'));
    }

    /**
     * Show school statistics dashboard
     * @return Renderable|JsonResponse
     */
    public function dashboard(Request $request)
    {
        // Handle AJAX requests for infinite scroll
        if ($request->ajax()) {
            $query = SchoolInnovation::with('school');
            
            // Filter by search term
            if ($request->has('search') && $request->search != '') {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('title', 'like', '%' . $searchTerm . '%')
                      ->orWhere('description', 'like', '%' . $searchTerm . '%')
                      ->orWhereHas('school', function($sq) use ($searchTerm) {
                          $sq->where('name', 'like', '%' . $searchTerm . '%');
                      });
                });
            }
            
            // Filter by category
            if ($request->has('category') && $request->category != '') {
                $query->where('category', $request->category);
            }
            
            $page = $request->get('page', 1);
            $perPage = $request->get('perPage', 16); // Default to 16 for large screens
            
            // Get total count before applying pagination
            $totalCount = $query->count();
            
            $innovations = $query->orderBy('created_at', 'desc')
                ->skip(($page - 1) * $perPage)
                ->take($perPage)
                ->get()
                ->map(function($innovation) {
                    $innovation->append('image_paths');
                    return $innovation;
                });
            
            $hasMore = $totalCount > ($page * $perPage);
            
            return response()->json([
                'innovations' => $innovations,
                'hasMore' => $hasMore,
                'total' => $totalCount
            ]);
        }
        
        // Initial page load
        $schools = School::with('innovations')->get();
        
        $innovations = SchoolInnovation::with('school')
            ->orderBy('created_at', 'asc')
            ->take(16)
            ->get()
            ->map(function($innovation) {
                $innovation->append('image_paths');
                return $innovation;
            });
        
        // Define all innovation categories
        $allCategories = [
            'ด้านการบริหารจัดการ',
            'ด้านหลักสูตรสถานศึกษา',
            'ด้านการจัดการเรียนรู้',
            'ด้านการนิเทศการศึกษา',
            'ด้านสื่อเทคโนโลยี',
            'ด้านการประกันคุณภาพการศึกษา',
            'ด้านการวัดและประเมินผล',
            'ด้านอื่น ๆ'
        ];
        
        // Get innovation stats by category
        $categoryCounts = SchoolInnovation::select('category', DB::raw('count(*) as count'))
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->groupBy('category')
            ->pluck('count', 'category');
        
        // Build category stats with all categories (show 0 if no data)
        $innovationsByCategory = collect($allCategories)->map(function($category) use ($categoryCounts) {
            return (object)[
                'category' => $category,
                'count' => $categoryCounts->get($category, 0)
            ];
        });
        
        $stats = [
            'total_schools' => $schools->count(),
            'total_students' => $schools->sum(fn($school) => $school->total_students),
            'total_teachers' => $schools->sum(fn($school) => $school->total_teachers),
            'total_innovations' => SchoolInnovation::count(),
            'active_innovations' => SchoolInnovation::where('is_active', true)->count(),
            'inactive_innovations' => SchoolInnovation::where('is_active', false)->count(),
            'by_department' => $schools->groupBy('department')->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'students' => $group->sum(fn($school) => $school->total_students),
                    'teachers' => $group->sum(fn($school) => $school->total_teachers),
                ];
            })
        ];
        
        // Get latest 5 active vision videos for dashboard
        $visionVideos = SchoolVisionVideo::with('school')
            ->active()
            ->ordered()
            ->take(5)
            ->get();
        
        // Get latest 5 publications for dashboard
        $publications = Publication::ordered()
            ->take(5)
            ->get();
        
        return view('sandbox::dashboard', compact('schools', 'stats', 'innovations', 'innovationsByCategory', 'visionVideos', 'publications'));
    }

    /**
     * Show infographic view (replacing hard-coded version)
     * @return Renderable
     */
    public function infographic()
    {
        $schools = School::with(['innovations' => function($query) {
            $query->where('is_active', true);
        }])->get();
        
        $stats = [
            'total_schools' => $schools->count(),
            'total_students' => $schools->sum(fn($school) => $school->total_students),
            'total_teachers' => $schools->sum(fn($school) => $school->total_teachers),
            'total_innovations' => SchoolInnovation::where('is_active', true)->count(),
            'by_department' => $schools->groupBy('department')->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'students' => $group->sum(fn($school) => $school->total_students),
                    'teachers' => $group->sum(fn($school) => $school->total_teachers),
                ];
            })
        ];
        
        return view('sandbox::infographic', compact('schools', 'stats'));
    }

    /**
     * Show the form for creating a new school.
     * @return Renderable
     */
    public function create()
    {
        return view('sandbox::schools.create');
    }

    /**
     * Store a newly created school in storage.
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_code' => 'required|string|max:50|unique:schools,school_code',
            'name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'ministry_affiliation' => 'nulable|string|max:255',
            'bureau_affiliation' => 'nullable|string|max:255',
            'education_area' => 'nullable|string|max:255',
            'school_type' => 'nullable|string|max:255',
            'male_students' => 'required|integer|min:0',
            'female_students' => 'required|integer|min:0',
            'male_teachers' => 'required|integer|min:0',
            'female_teachers' => 'required|integer|min:0',
            'address' => 'nullable|string',
            'subdistrict' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        School::create($validated);

        return redirect()->route('sandbox.schools.index')
            ->with('success', 'โรงเรียนถูกเพิ่มเรียบร้อยแล้ว');
    }

    /**
     * Display the specified school.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $school = School::with(['innovations', 'visionVideos', 'galleries'])->findOrFail($id);
        return view('sandbox::schools.show', compact('school'));
    }

    /**
     * Show the form for editing the specified school.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $school = School::findOrFail($id);
        return view('sandbox::schools.edit', compact('school'));
    }

    /**
     * Update the specified school in storage.
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $school = School::findOrFail($id);

        $validated = $request->validate([
            'school_code' => 'required|string|max:50|unique:schools,school_code,' . $id,
            'name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'ministry_affiliation' => 'nullable|string|max:255',
            'bureau_affiliation' => 'nullable|string|max:255',
            'education_area' => 'nullable|string|max:255',
            'school_type' => 'nullable|string|max:255',
            'male_students' => 'required|integer|min:0',
            'female_students' => 'required|integer|min:0',
            'male_teachers' => 'required|integer|min:0',
            'female_teachers' => 'required|integer|min:0',
            'address' => 'nullable|string',
            'subdistrict' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $school->update($validated);

        return redirect()->route('sandbox.schools.index')
            ->with('success', 'ข้อมูลโรงเรียนถูกอัปเดตเรียบร้อยแล้ว');
    }

    /**
     * Remove the specified school from storage.
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        $school = School::findOrFail($id);
        $school->delete();

        return redirect()->route('sandbox.schools.index')
            ->with('success', 'โรงเรียนถูกลบเรียบร้อยแล้ว');
    }

    // Innovation Management Methods

    /**
     * Show innovations for a school
     * @param int $schoolId
     * @return Renderable
     */
    public function showInnovations($schoolId)
    {
        $school = School::with('innovations')->findOrFail($schoolId);
        return view('sandbox::innovations.index', compact('school'));
    }

    /**
     * Show form for creating new innovation
     * @param int $schoolId
     * @return Renderable
     */
    public function createInnovation($schoolId)
    {
        $school = School::findOrFail($schoolId);
        return view('sandbox::innovations.create', compact('school'));
    }

    /**
     * Show form for editing innovation
     * @param int $schoolId
     * @param int $innovationId
     * @return Renderable
     */
    public function editInnovation($schoolId, $innovationId)
    {
        $school = School::findOrFail($schoolId);
        $innovation = SchoolInnovation::where('school_id', $schoolId)->findOrFail($innovationId);
        return view('sandbox::innovations.edit', compact('school', 'innovation'));
    }

    /**
     * Store a new innovation
     * @param Request $request
     * @param int $schoolId
     * @return RedirectResponse
     */
    public function storeInnovation(Request $request, $schoolId)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'category' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:2543|max:2600', // พ.ศ. 2543-2600
            'is_active' => 'boolean',
            'files' => 'nullable|array|max:5',
            'files.*' => 'file|mimes:jpeg,png,jpg,gif,webp,pdf|max:5120', // 5MB per file
        ], [
            'title.required' => 'กรุณากรอกชื่อนวัตกรรม',
            'title.max' => 'ชื่อนวัตกรรมต้องไม่เกิน 255 ตัวอักษร',
            'description.max' => 'รายละเอียดต้องไม่เกิน 2000 ตัวอักษร',
            'year.min' => 'ปีต้องไม่น้อยกว่า พ.ศ. 2543',
            'year.max' => 'ปีต้องไม่เกิน พ.ศ. 2573',
            'year.integer' => 'กรุณากรอกปีให้ถูกต้อง',
            'files.max' => 'สามารถอัปโหลดได้สูงสุด 5 ไฟล์',
            'files.*.file' => 'กรุณาเลือกไฟล์ที่ถูกต้อง',
            'files.*.mimes' => 'ไฟล์ต้องเป็นประเภท JPEG, PNG, JPG, GIF, WEBP หรือ PDF เท่านั้น',
            'files.*.max' => 'ขนาดไฟล์ต้องไม่เกิน 5MB',
        ]);

        $validated['school_id'] = $schoolId;
        
        // Handle multiple file uploads
        if ($request->hasFile('files')) {
            $filePaths = [];
            $folder = 'innovations/' . gen_folder($schoolId);
            $storagePath = storage_path('app/public/' . $folder);
            
            // ตรวจสอบโฟลเดอร์ถ้ายังไม่มีให้สร้าง
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0777, true);
            }
            
            foreach ($request->file('files') as $index => $file) {
                if ($file && $file->isValid()) {
                    // Convert file to array format for ItopCyberUpload
                    $fileArray = [
                        'name' => $file->getClientOriginalName(),
                        'type' => $file->getMimeType(),
                        'tmp_name' => $file->getPathname(),
                        'size' => $file->getSize(),
                        'error' => $file->getError()
                    ];
                    
                    $fileName = ItopCyberUpload::upload($storagePath, $fileArray, []);
                    if ($fileName) {
                        $filePaths[] = $folder . '/' . $fileName;
                    }
                }
            }
            
            // Store as JSON array (for multiple files) or single string (for backward compatibility)
            if (count($filePaths) > 1) {
                $validated['image_path'] = json_encode($filePaths);
            } elseif (count($filePaths) == 1) {
                $validated['image_path'] = $filePaths[0];
            }
        }

        SchoolInnovation::create($validated);

        return redirect()->route('sandbox.schools.innovations', $schoolId)
            ->with('success', 'นวัตกรรมถูกเพิ่มเรียบร้อยแล้ว');
    }

    /**
     * Update innovation
     * @param Request $request
     * @param int $schoolId
     * @param int $innovationId
     * @return RedirectResponse
     */
    public function updateInnovation(Request $request, $schoolId, $innovationId)
    {
        $innovation = SchoolInnovation::where('school_id', $schoolId)->findOrFail($innovationId);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'category' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:2543|max:2600', // พ.ศ. 2543-2600
            'is_active' => 'boolean',
            'files' => 'nullable|array|max:5',
            'files.*' => 'file|mimes:jpeg,png,jpg,gif,webp,pdf|max:5120', // 5MB per file
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // Legacy support
        ], [
            'title.required' => 'กรุณากรอกชื่อนวัตกรรม',
            'title.max' => 'ชื่อนวัตกรรมต้องไม่เกิน 255 ตัวอักษร',
            'description.max' => 'รายละเอียดต้องไม่เกิน 2000 ตัวอักษร',
            'year.min' => 'ปีต้องไม่น้อยกว่า พ.ศ. 2543',
            'year.max' => 'ปีต้องไม่เกิน พ.ศ. 2600',
            'year.integer' => 'กรุณากรอกปีให้ถูกต้อง',
            'files.max' => 'สามารถอัปโหลดได้สูงสุด 5 ไฟล์',
            'files.*.file' => 'กรุณาเลือกไฟล์ที่ถูกต้อง',
            'files.*.mimes' => 'ไฟล์ต้องเป็นประเภท JPEG, PNG, JPG, GIF, WEBP หรือ PDF เท่านั้น',
            'files.*.max' => 'ขนาดไฟล์ต้องไม่เกิน 5MB',
            'image.image' => 'กรุณาเลือกไฟล์รูปภาพเท่านั้น',
            'image.mimes' => 'รูปภาพต้องเป็นไฟล์ประเภท JPEG, PNG, JPG หรือ GIF เท่านั้น',
            'image.max' => 'ขนาดรูปภาพต้องไม่เกิน 5MB',
        ]);

        // Handle multiple file uploads - only if new files are uploaded
        if ($request->hasFile('files') || $request->hasFile('image')) {
            // Delete old files if any
            if ($innovation->image_path) {
                $oldPaths = $innovation->image_paths;
                foreach ($oldPaths as $oldPath) {
                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }
            }
            
            $filePaths = [];
            $folder = 'innovations/' . gen_folder($schoolId);
            $storagePath = storage_path('app/public/' . $folder);
            
            // ตรวจสอบโฟลเดอร์ถ้ายังไม่มีให้สร้าง
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0777, true);
            }
            
            // Handle multiple files upload  
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $index => $file) {
                    if ($file && $file->isValid()) {
                        $fileArray = [
                            'name' => $file->getClientOriginalName(),
                            'type' => $file->getMimeType(),
                            'tmp_name' => $file->getPathname(),
                            'size' => $file->getSize(),
                            'error' => $file->getError()
                        ];
                        
                        $fileName = ItopCyberUpload::upload($storagePath, $fileArray, []);
                        if ($fileName) {
                            $filePaths[] = $folder . '/' . $fileName;
                        }
                    }
                }
            }
            
            // Handle legacy single image upload
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                if ($file && $file->isValid()) {
                    $fileArray = [
                        'name' => $file->getClientOriginalName(),
                        'type' => $file->getMimeType(),
                        'tmp_name' => $file->getPathname(),
                        'size' => $file->getSize(),
                        'error' => $file->getError()
                    ];
                    
                    $fileName = ItopCyberUpload::upload($storagePath, $fileArray, []);
                    if ($fileName) {
                        $filePaths[] = $folder . '/' . $fileName;
                    }
                }
            }
            
            // Store as JSON array (for multiple files) or single string (for backward compatibility)
            if (count($filePaths) > 1) {
                $validated['image_path'] = json_encode($filePaths);
            } elseif (count($filePaths) == 1) {
                $validated['image_path'] = $filePaths[0];
            }
        }

        $innovation->update($validated);

        return redirect()->route('sandbox.schools.innovations', $schoolId)
            ->with('success', 'นวัตกรรมถูกอัปเดตเรียบร้อยแล้ว');
    }

    /**
     * Get innovation details (API endpoint)
     * @param int $schoolId
     * @param int $innovationId
     * @return JsonResponse
     */
    public function getInnovation($schoolId, $innovationId)
    {
        $innovation = SchoolInnovation::where('school_id', $schoolId)->findOrFail($innovationId);
        return response()->json($innovation);
    }

    /**
     * Delete innovation
     * @param int $schoolId
     * @param int $innovationId
     * @return RedirectResponse
     */
    public function destroyInnovation($schoolId, $innovationId)
    {
        $innovation = SchoolInnovation::where('school_id', $schoolId)->findOrFail($innovationId);
        
        // ลบรูปภาพถ้ามี
        if ($innovation->image_path) {
            Storage::disk('public')->delete($innovation->image_path);
        }
        
        $innovation->delete();

        return redirect()->route('sandbox.schools.innovations', $schoolId)
            ->with('success', 'นวัตกรรมถูกลบเรียบร้อยแล้ว');
    }

    // ========================================
    // Vision Video Management Methods
    // ========================================

    /**
     * Show form to create new vision video for a school
     */
    public function createVisionVideo($schoolId): Renderable
    {
        $school = School::findOrFail($schoolId);
        
        return view('sandbox::vision-videos.create', compact('school'));
    }

    /**
     * Store new vision video
     */
    public function storeVisionVideo(Request $request, $schoolId): RedirectResponse
    {
        $school = School::findOrFail($schoolId);
        
        $validated = $request->validate([
            'video_url' => 'required|url|max:500',
            'video_type' => 'required|in:youtube,facebook,tiktok,other',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0'
        ]);

        // Set defaults for checkbox and order
        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        $school->visionVideos()->create($validated);

        return redirect()->route('sandbox.schools.show', $schoolId)
            ->with('success', 'เพิ่มวิดีโอวิสัยทัศน์เรียบร้อยแล้ว');
    }

    /**
     * Show form to edit vision video
     */
    public function editVisionVideo($schoolId, $videoId): Renderable
    {
        $school = School::findOrFail($schoolId);
        $video = SchoolVisionVideo::where('school_id', $schoolId)->findOrFail($videoId);
        
        return view('sandbox::vision-videos.edit', compact('school', 'video'));
    }

    /**
     * Update vision video
     */
    public function updateVisionVideo(Request $request, $schoolId, $videoId): RedirectResponse
    {
        $video = SchoolVisionVideo::where('school_id', $schoolId)->findOrFail($videoId);
        
        $validated = $request->validate([
            'video_url' => 'required|url|max:500',
            'video_type' => 'required|in:youtube,facebook,tiktok,other',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0'
        ]);

        // Set checkbox value (true if checked, false if not)
        $validated['is_active'] = $request->has('is_active');

        $video->update($validated);

        return redirect()->route('sandbox.schools.show', $schoolId)
            ->with('success', 'แก้ไขวิดีโอวิสัยทัศน์เรียบร้อยแล้ว');
    }

    /**
     * Delete vision video
     */
    public function destroyVisionVideo($schoolId, $videoId): RedirectResponse
    {
        $video = SchoolVisionVideo::where('school_id', $schoolId)->findOrFail($videoId);
        $video->delete();

        return redirect()->route('sandbox.schools.show', $schoolId)
            ->with('success', 'ลบวิดีโอวิสัยทัศน์เรียบร้อยแล้ว');
    }

    /**
     * Show all vision videos from all schools
     */
    public function allVisionVideos(): Renderable
    {
        $videos = SchoolVisionVideo::with('school')
            ->active()
            ->ordered()
            ->paginate(12);
        
        return view('sandbox::vision-videos.all', compact('videos'));
    }

    /**
     * Export schools data to CSV
     * 
     * @param Request $request
     * @param ExportService $exportService
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportSchoolsCSV(Request $request, ExportService $exportService)
    {
        // Get schools with filters
        $query = School::query();
        
        // Apply filters if provided
        if ($request->has('department') && $request->department != '') {
            $query->where('department', $request->department);
        }
        
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        $schools = $query->orderBy('name')->get();
        
        // Prepare data for export
        $exportData = $exportService->prepareSchoolsData($schools);
        
        // Generate filename with timestamp
        $filename = 'schools_export_' . date('Y-m-d_His') . '.csv';
        
        return $exportService->exportToCSV(
            $exportData['data'],
            $exportData['headers'],
            $filename
        );
    }

    /**
     * Export schools data to Excel
     * 
     * @param Request $request
     * @param ExportService $exportService
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportSchoolsExcel(Request $request, ExportService $exportService)
    {
        // Get schools with filters
        $query = School::query();
        
        // Apply filters if provided
        if ($request->has('department') && $request->department != '') {
            $query->where('department', $request->department);
        }
        
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        $schools = $query->orderBy('name')->get();
        
        // Prepare data for export
        $exportData = $exportService->prepareSchoolsData($schools);
        
        // Generate filename with timestamp
        $filename = 'schools_export_' . date('Y-m-d_His') . '.xlsx';
        
        return $exportService->exportToExcel(
            $exportData['data'],
            $exportData['headers'],
            $filename,
            'รายชื่อโรงเรียน'
        );
    }
}
