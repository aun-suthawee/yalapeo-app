<?php

namespace Modules\Sandbox\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Sandbox\Entities\Publication;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PublicationController extends Controller
{
    /**
     * Display a listing of the publications.
     */
    public function index()
    {
        $publications = Publication::ordered()->paginate(15);
        
        return view('sandbox::publications.index', compact('publications'));
    }

    /**
     * Show the form for creating a new publication.
     */
    public function create()
    {
        return view('sandbox::publications.create');
    }

    /**
     * Store a newly created publication in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'pdf_file' => 'required|file|mimes:pdf|max:10240', // Max 10MB
            'display_order' => 'nullable|integer',
        ]);

        try {
            // Check total storage usage before upload
            $this->checkStorageLimit();

            // Handle PDF upload
            if ($request->hasFile('pdf_file')) {
                $file = $request->file('pdf_file');
                $originalName = $file->getClientOriginalName();
                $fileSize = $file->getSize();
                
                // Validate file is actually a PDF
                if (!$this->isValidPdf($file)) {
                    return back()->withInput()
                                ->with('error', 'ไฟล์ที่อัปโหลดไม่ใช่ไฟล์ PDF ที่ถูกต้อง');
                }
                
                $filename = time() . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.pdf';
                
                // Store in storage/app/public/publications
                $file->storeAs('public/publications', $filename);
                
                $validated['pdf_path'] = $filename;
                $validated['original_filename'] = $originalName;
                $validated['file_size'] = $fileSize;
            }

            $validated['display_order'] = $validated['display_order'] ?? 0;

            Publication::create($validated);
            
            // Clean up old files if needed
            $this->cleanupOldFiles();

            return redirect()->route('sandbox.publications.index')
                           ->with('success', 'เพิ่มเอกสารเผยแพร่สำเร็จ');
        } catch (\Exception $e) {
            return back()->withInput()
                        ->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified publication (PDF viewer).
     */
    public function show($id)
    {
        $publication = Publication::findOrFail($id);
        
        return view('sandbox::publications.show', compact('publication'));
    }

    /**
     * Show the form for editing the specified publication.
     */
    public function edit($id)
    {
        $publication = Publication::findOrFail($id);
        
        return view('sandbox::publications.edit', compact('publication'));
    }

    /**
     * Update the specified publication in storage.
     */
    public function update(Request $request, $id)
    {
        $publication = Publication::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'pdf_file' => 'nullable|file|mimes:pdf|max:10240',
            'display_order' => 'nullable|integer',
        ]);

        try {
            // Handle PDF upload if new file is provided
            if ($request->hasFile('pdf_file')) {
                $file = $request->file('pdf_file');
                $originalName = $file->getClientOriginalName();
                $fileSize = $file->getSize();
                
                // Validate file is actually a PDF
                if (!$this->isValidPdf($file)) {
                    return back()->withInput()
                                ->with('error', 'ไฟล์ที่อัปโหลดไม่ใช่ไฟล์ PDF ที่ถูกต้อง');
                }
                
                // Check storage limit
                $this->checkStorageLimit();
                
                // Delete old file
                if ($publication->pdf_path && Storage::exists('public/publications/' . $publication->pdf_path)) {
                    Storage::delete('public/publications/' . $publication->pdf_path);
                }

                $filename = time() . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.pdf';
                
                $file->storeAs('public/publications', $filename);
                
                $validated['pdf_path'] = $filename;
                $validated['original_filename'] = $originalName;
                $validated['file_size'] = $fileSize;
            }

            $validated['display_order'] = $validated['display_order'] ?? $publication->display_order;

            $publication->update($validated);
            
            // Clean up old files if needed
            $this->cleanupOldFiles();

            return redirect()->route('sandbox.publications.index')
                           ->with('success', 'แก้ไขเอกสารเผยแพร่สำเร็จ');
        } catch (\Exception $e) {
            return back()->withInput()
                        ->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified publication from storage.
     */
    public function destroy($id)
    {
        try {
            $publication = Publication::findOrFail($id);

            // Delete PDF file
            if ($publication->pdf_path && Storage::exists('public/publications/' . $publication->pdf_path)) {
                Storage::delete('public/publications/' . $publication->pdf_path);
            }

            $publication->delete();

            return redirect()->route('sandbox.publications.index')
                           ->with('success', 'ลบเอกสารเผยแพร่สำเร็จ');
        } catch (\Exception $e) {
            return back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    /**
     * Display PDF file
     */
    public function viewPdf($filename)
    {
        $path = storage_path('app/public/publications/' . $filename);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    }

    /**
     * Check if uploaded file is a valid PDF
     */
    private function isValidPdf($file)
    {
        // Check MIME type
        if ($file->getMimeType() !== 'application/pdf') {
            return false;
        }

        // Check file signature (PDF starts with %PDF-)
        $handle = fopen($file->getRealPath(), 'r');
        $header = fread($handle, 5);
        fclose($handle);

        return $header === '%PDF-';
    }

    /**
     * Check storage limit before upload
     */
    private function checkStorageLimit()
    {
        $maxStorageSize = 100 * 1024 * 1024; // 100 MB total limit
        $currentSize = $this->getDirectorySize(storage_path('app/public/publications'));

        if ($currentSize >= $maxStorageSize) {
            throw new \Exception('พื้นที่จัดเก็บเต็ม กรุณาลบเอกสารเก่าออกก่อน');
        }
    }

    /**
     * Get directory size in bytes
     */
    private function getDirectorySize($path)
    {
        if (!is_dir($path)) {
            return 0;
        }

        $size = 0;
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path)) as $file) {
            if ($file->isFile()) {
                $size += $file->getSize();
            }
        }

        return $size;
    }

    /**
     * Clean up old unused files
     * Deletes files that are not referenced in database
     */
    private function cleanupOldFiles()
    {
        try {
            $directory = storage_path('app/public/publications');
            
            if (!is_dir($directory)) {
                return;
            }

            // Get all PDF files in directory
            $files = glob($directory . '/*.pdf');
            
            // Get all pdf_path from database
            $dbFiles = Publication::pluck('pdf_path')->toArray();
            
            $cleanedCount = 0;
            
            foreach ($files as $file) {
                $filename = basename($file);
                
                // If file not in database, delete it
                if (!in_array($filename, $dbFiles)) {
                    @unlink($file);
                    $cleanedCount++;
                }
            }
            
            Log::info("Publication cleanup: Removed {$cleanedCount} unused files");
        } catch (\Exception $e) {
            Log::error("Publication cleanup error: " . $e->getMessage());
        }
    }

    /**
     * Get storage statistics
     */
    public function getStorageStats()
    {
        $directory = storage_path('app/public/publications');
        $totalSize = $this->getDirectorySize($directory);
        $maxSize = 100 * 1024 * 1024; // 100 MB
        
        $fileCount = Publication::count();
        $totalDbSize = Publication::sum('file_size');

        return [
            'total_size' => $totalSize,
            'max_size' => $maxSize,
            'used_percentage' => ($totalSize / $maxSize) * 100,
            'file_count' => $fileCount,
            'db_size' => $totalDbSize,
        ];
    }
}
