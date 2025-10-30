<?php

namespace Modules\Sandbox\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Sandbox\Entities\School;
use Modules\Sandbox\Entities\SchoolGallery;

class GalleryController extends Controller
{
    /**
     * Display a listing of galleries for a school
     */
    public function index($schoolId)
    {
        $school = School::findOrFail($schoolId);
        $galleries = $school->galleries()->ordered()->paginate(12);

        return view('sandbox::galleries.index', compact('school', 'galleries'));
    }

    /**
     * Show the form for creating a new gallery
     */
    public function create($schoolId)
    {
        $school = School::findOrFail($schoolId);
        
        return view('sandbox::galleries.create', compact('school'));
    }

    /**
     * Store a newly created gallery in storage
     */
    public function store(Request $request, $schoolId)
    {
        $school = School::findOrFail($schoolId);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'drive_folder_url' => 'required|string',
            'is_active' => 'boolean',
            'display_order' => 'nullable|integer|min:0',
        ]);

        // Extract folder ID from URL
        $folderId = SchoolGallery::extractFolderId($validated['drive_folder_url']);
        
        if (!$folderId) {
            return back()->withErrors(['drive_folder_url' => 'ไม่สามารถดึง Folder ID จาก URL ได้ กรุณาตรวจสอบ URL อีกครั้ง'])->withInput();
        }

        $gallery = $school->galleries()->create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'drive_folder_id' => $folderId,
            'drive_folder_url' => $validated['drive_folder_url'],
            'is_active' => $validated['is_active'] ?? true,
            'display_order' => $validated['display_order'] ?? 0,
        ]);

        return redirect()->route('sandbox.schools.galleries.index', $schoolId)
            ->with('success', 'เพิ่มคลังภาพกิจกรรมสำเร็จ');
    }

    /**
     * Display the specified gallery
     */
    public function show($schoolId, $id)
    {
        $school = School::findOrFail($schoolId);
        $gallery = SchoolGallery::where('school_id', $schoolId)->findOrFail($id);

        return view('sandbox::galleries.show', compact('school', 'gallery'));
    }

    /**
     * Show the form for editing the specified gallery
     */
    public function edit($schoolId, $id)
    {
        $school = School::findOrFail($schoolId);
        $gallery = SchoolGallery::where('school_id', $schoolId)->findOrFail($id);

        return view('sandbox::galleries.edit', compact('school', 'gallery'));
    }

    /**
     * Update the specified gallery in storage
     */
    public function update(Request $request, $schoolId, $id)
    {
        $school = School::findOrFail($schoolId);
        $gallery = SchoolGallery::where('school_id', $schoolId)->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'drive_folder_url' => 'required|string',
            'is_active' => 'boolean',
            'display_order' => 'nullable|integer|min:0',
        ]);

        // Extract folder ID from URL
        $folderId = SchoolGallery::extractFolderId($validated['drive_folder_url']);
        
        if (!$folderId) {
            return back()->withErrors(['drive_folder_url' => 'ไม่สามารถดึง Folder ID จาก URL ได้ กรุณาตรวจสอบ URL อีกครั้ง'])->withInput();
        }

        $gallery->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'drive_folder_id' => $folderId,
            'drive_folder_url' => $validated['drive_folder_url'],
            'is_active' => $request->has('is_active') ? $validated['is_active'] : $gallery->is_active,
            'display_order' => $validated['display_order'] ?? $gallery->display_order,
        ]);

        return redirect()->route('sandbox.schools.galleries.index', $schoolId)
            ->with('success', 'แก้ไขคลังภาพกิจกรรมสำเร็จ');
    }

    /**
     * Remove the specified gallery from storage
     */
    public function destroy($schoolId, $id)
    {
        $school = School::findOrFail($schoolId);
        $gallery = SchoolGallery::where('school_id', $schoolId)->findOrFail($id);

        $gallery->delete();

        return redirect()->route('sandbox.schools.galleries.index', $schoolId)
            ->with('success', 'ลบคลังภาพกิจกรรมสำเร็จ');
    }
}
