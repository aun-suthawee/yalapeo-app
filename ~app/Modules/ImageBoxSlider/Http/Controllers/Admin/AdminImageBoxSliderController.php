<?php

namespace Modules\ImageBoxSlider\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\ImageBoxSlider\Entities\ImageBoxSliderModel;

class AdminImageBoxSliderController extends Controller
{
    public function index()
    {
        $sliders = ImageBoxSliderModel::orderBy('created_at', 'desc')->paginate(15);
        
        // สร้างข้อมูล $body สำหรับ layout
        $body = [
            'title' => 'รายการรูปภาพสไลด์',
            'app_title' => [
                'h1' => '<i class="fas fa-images"></i> รายการรูปภาพสไลด์',
                'p' => 'จัดการรูปภาพสไลด์ทั้งหมด'
            ]
        ];
        
        return view('imageboxslider::admin.index', compact('sliders', 'body'));
    }

    public function create()
    {
        // สร้างข้อมูล $body สำหรับ layout
        $body = [
            'title' => 'เพิ่มรูปภาพสไลด์',
            'app_title' => [
                'h1' => '<i class="fas fa-images"></i> เพิ่มรูปภาพสไลด์',
                'p' => 'เพิ่มรูปภาพสไลด์ใหม่เข้าสู่ระบบ'
            ]
        ];
        
        return view('imageboxslider::admin.create', compact('body'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:20480',
            'pdf_files.*' => 'nullable|mimes:pdf|max:20480',
            'url' => 'nullable|string|max:255',
            'target' => 'nullable|string|in:_blank,_self,_parent,_top',
        ]);

        // บันทึกรูปภาพและแปลงเป็น WebP หากรองรับ
        $imageName = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            
            // สร้างไดเร็กทอรีถ้ายังไม่มี
            $imageDir = 'public/image_box_slider';
            Storage::makeDirectory($imageDir);
            
            // ตรวจสอบว่า GD extension พร้อมใช้งานและรองรับ WebP
            if (function_exists('imagewebp')) {
                // สร้างชื่อไฟล์ WebP
                $imageName = $originalName . '_' . time() . '.webp';
                $temp_file = tempnam(sys_get_temp_dir(), 'webp_');
                
                // โหลดรูปภาพตามประเภทไฟล์
                $source_image = null;
                $mime = $image->getMimeType();
                $source_path = $image->getRealPath();
                
                switch ($mime) {
                    case 'image/jpeg':
                    case 'image/jpg':
                        $source_image = imagecreatefromjpeg($source_path);
                        break;
                    case 'image/png':
                        $source_image = imagecreatefrompng($source_path);
                        // รักษาค่าความโปร่งใส
                        imagepalettetotruecolor($source_image);
                        imagealphablending($source_image, true);
                        imagesavealpha($source_image, true);
                        break;
                    case 'image/gif':
                        $source_image = imagecreatefromgif($source_path);
                        break;
                    default:
                        // กรณีไม่รองรับ ให้บันทึกเป็นไฟล์ปกติ
                        $extension = $image->getClientOriginalExtension();
                        $imageName = $originalName . '_' . time() . '.' . $extension;
                        $image->storeAs($imageDir, $imageName);
                        break;
                }
                
                if ($source_image) {
                    // แปลงและบันทึกเป็น WebP
                    imagewebp($source_image, $temp_file, 80); // quality 80%
                    imagedestroy($source_image);
                    
                    // อ่านไฟล์ WebP และบันทึกลง Storage
                    $webp_data = file_get_contents($temp_file);
                    Storage::put($imageDir . '/' . $imageName, $webp_data);
                    
                    // ลบไฟล์ชั่วคราว
                    @unlink($temp_file);
                }
            } else {
                // กรณีไม่มี WebP support ให้บันทึกเป็นไฟล์ปกติ
                $extension = $image->getClientOriginalExtension();
                $imageName = $originalName . '_' . time() . '.' . $extension;
                $image->storeAs($imageDir, $imageName);
            }
        }
        
        // บันทึกไฟล์ PDF (หลายไฟล์) ในรูปแบบ JSON array
        $pdfData = [];
        if ($request->hasFile('pdf_files')) {
            $pdfFiles = $request->file('pdf_files');
            
            // สร้างไดเร็กทอรีถ้ายังไม่มี
            $pdfDir = 'public/image_box_slider/pdf';
            Storage::makeDirectory($pdfDir);
            
            foreach ($pdfFiles as $pdf) {
                $originalName = $pdf->getClientOriginalName();
                $nameUploaded = pathinfo($originalName, PATHINFO_FILENAME) . '_' . uniqid() . '.' . $pdf->getClientOriginalExtension();
                
                // บันทึกไฟล์ PDF
                $pdf->storeAs($pdfDir, $nameUploaded);
                
                // เพิ่มข้อมูลไฟล์ลงในอาร์เรย์
                $pdfData[] = [
                    'name' => $originalName,
                    'size' => $pdf->getSize(),
                    'type' => $pdf->getMimeType(),
                    'error' => 0,
                    'tmp_name' => $pdf->getPathname(),
                    'name_uploaded' => $nameUploaded
                ];
            }
        }
        
        // บันทึกข้อมูล
        $slider = new ImageBoxSliderModel();
        $slider->title = $request->title;
        $slider->image = $imageName;
        $slider->description = $request->description;
        $slider->pdf_file = $pdfData;
        $slider->is_active = $request->is_active;
        $slider->url = $request->url;
        $slider->target = $request->target;
        $slider->save();
        
        return redirect()->route('admin.imageboxslider.index')
            ->with('success', 'รูปภาพสไลด์ถูกเพิ่มเรียบร้อยแล้ว');
    }

    public function edit($id)
    {
        $slider = ImageBoxSliderModel::findOrFail($id);
        
        // สร้างข้อมูล $body สำหรับ layout
        $body = [
            'title' => 'แก้ไขรูปภาพสไลด์',
            'app_title' => [
                'h1' => '<i class="fas fa-images"></i> แก้ไขรูปภาพสไลด์',
                'p' => 'แก้ไขข้อมูลรูปภาพสไลด์'
            ]
        ];
        
        return view('imageboxslider::admin.edit', compact('slider', 'body'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20480',
            'pdf_files.*' => 'nullable|mimes:pdf|max:20480',
            'url' => 'nullable|string|max:255',
            'target' => 'nullable|string|in:_blank,_self,_parent,_top',
        ]);
        
        $slider = ImageBoxSliderModel::findOrFail($id);
        
        // อัปเดต title และ description
        $slider->title = $request->title;
        $slider->description = $request->description;
        
        // อัปเดตสถานะการแสดงผล
        $slider->is_active = $request->is_active;
        
        // อัปเดต URL และ target
        $slider->url = $request->url;
        $slider->target = $request->target;
        
        // อัปเดตรูปภาพ (ถ้ามีการอัปโหลดใหม่)
        if ($request->hasFile('image')) {
            // ลบไฟล์เก่า (ถ้ามี)
            if ($slider->image && Storage::exists('public/image_box_slider/' . $slider->image)) {
                Storage::delete('public/image_box_slider/' . $slider->image);
            }
            
            $image = $request->file('image');
            $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $imageDir = 'public/image_box_slider';
            
            // ตรวจสอบว่า GD extension พร้อมใช้งานและรองรับ WebP
            if (function_exists('imagewebp')) {
                // สร้างชื่อไฟล์ WebP
                $imageName = $originalName . '_' . time() . '.webp';
                $temp_file = tempnam(sys_get_temp_dir(), 'webp_');
                
                // โหลดรูปภาพตามประเภทไฟล์
                $source_image = null;
                $mime = $image->getMimeType();
                $source_path = $image->getRealPath();
                
                switch ($mime) {
                    case 'image/jpeg':
                    case 'image/jpg':
                        $source_image = imagecreatefromjpeg($source_path);
                        break;
                    case 'image/png':
                        $source_image = imagecreatefrompng($source_path);
                        // รักษาค่าความโปร่งใส
                        imagepalettetotruecolor($source_image);
                        imagealphablending($source_image, true);
                        imagesavealpha($source_image, true);
                        break;
                    case 'image/gif':
                        $source_image = imagecreatefromgif($source_path);
                        break;
                    default:
                        // กรณีไม่รองรับ ให้บันทึกเป็นไฟล์ปกติ
                        $extension = $image->getClientOriginalExtension();
                        $imageName = $originalName . '_' . time() . '.' . $extension;
                        $image->storeAs($imageDir, $imageName);
                        break;
                }
                
                if ($source_image) {
                    // แปลงและบันทึกเป็น WebP
                    imagewebp($source_image, $temp_file, 80); // quality 80%
                    imagedestroy($source_image);
                    
                    // อ่านไฟล์ WebP และบันทึกลง Storage
                    $webp_data = file_get_contents($temp_file);
                    Storage::put($imageDir . '/' . $imageName, $webp_data);
                    
                    // ลบไฟล์ชั่วคราว
                    @unlink($temp_file);
                }
            } else {
                // กรณีไม่มี WebP support ให้บันทึกเป็นไฟล์ปกติ
                $extension = $image->getClientOriginalExtension();
                $imageName = $originalName . '_' . time() . '.' . $extension;
                $image->storeAs($imageDir, $imageName);
            }
            
            $slider->image = $imageName;
        }
        
        // อัปเดตไฟล์ PDF (ถ้ามีการอัปโหลดใหม่) ในรูปแบบ JSON array
        if ($request->hasFile('pdf_files')) {
            // เก็บข้อมูล PDF เดิมไว้
            $existingPdfData = $slider->pdf_file ? $slider->pdf_file : [];
            
            $pdfFiles = $request->file('pdf_files');
            
            // สร้างไดเร็กทอรีถ้ายังไม่มี
            $pdfDir = 'public/image_box_slider/pdf';
            Storage::makeDirectory($pdfDir);
            
            $newPdfData = [];
            foreach ($pdfFiles as $pdf) {
                $originalName = $pdf->getClientOriginalName();
                $nameUploaded = pathinfo($originalName, PATHINFO_FILENAME) . '_' . uniqid() . '.' . $pdf->getClientOriginalExtension();
                
                // บันทึกไฟล์ PDF
                $pdf->storeAs($pdfDir, $nameUploaded);
                
                // เพิ่มข้อมูลไฟล์ลงในอาร์เรย์
                $newPdfData[] = [
                    'name' => $originalName,
                    'size' => $pdf->getSize(),
                    'type' => $pdf->getMimeType(),
                    'error' => 0,
                    'tmp_name' => $pdf->getPathname(),
                    'name_uploaded' => $nameUploaded
                ];
            }
            
            // รวมข้อมูล PDF เก่าและใหม่เข้าด้วยกัน
            $slider->pdf_file = array_merge($existingPdfData, $newPdfData);
        }
        
        // บันทึกข้อมูล
        $slider->save();
        
        return redirect()->route('admin.imageboxslider.index')
            ->with('success', 'รูปภาพสไลด์ถูกอัปเดตเรียบร้อยแล้ว');
    }

    public function destroy($id)
    {
        $slider = ImageBoxSliderModel::findOrFail($id);
        
        // ลบไฟล์รูปภาพ (ถ้ามี)
        if ($slider->image && Storage::exists('public/image_box_slider/' . $slider->image)) {
            Storage::delete('public/image_box_slider/' . $slider->image);
        }
        
        // ลบไฟล์ PDF (ถ้ามี)
        if ($slider->pdf_file && is_array($slider->pdf_file)) {
            foreach ($slider->pdf_file as $file) {
                if (isset($file['name_uploaded']) && Storage::exists('public/image_box_slider/pdf/' . $file['name_uploaded'])) {
                    Storage::delete('public/image_box_slider/pdf/' . $file['name_uploaded']);
                }
            }
        }
        
        $slider->delete();
        
        return redirect()->route('admin.imageboxslider.index')
            ->with('success', 'รูปภาพสไลด์ถูกลบเรียบร้อยแล้ว');
    }
    
    /**
     * ลบไฟล์ PDF เฉพาะไฟล์ (AJAX)
     */
    public function deletePdfFile(Request $request, $id)
    {
        $key = $request->input('key');
        $keyParts = explode('-', $key);
        
        if (count($keyParts) == 2 && $keyParts[0] == 'pdf') {
            $index = (int)$keyParts[1];
            
            $slider = ImageBoxSliderModel::findOrFail($id);
            
            if ($slider->pdf_file && is_array($slider->pdf_file) && isset($slider->pdf_file[$index])) {
                $fileToDelete = $slider->pdf_file[$index];
                
                // ลบไฟล์จาก storage
                if (isset($fileToDelete['name_uploaded']) && Storage::exists('public/image_box_slider/pdf/' . $fileToDelete['name_uploaded'])) {
                    Storage::delete('public/image_box_slider/pdf/' . $fileToDelete['name_uploaded']);
                }
                
                // ลบข้อมูลไฟล์จาก array
                $pdfData = $slider->pdf_file;
                array_splice($pdfData, $index, 1);
                
                // บันทึกข้อมูลที่อัปเดตกลับไป
                $slider->pdf_file = $pdfData;
                $slider->save();
                
                return response()->json(['success' => true]);
            }
        }
        
        return response()->json(['success' => false], 400);
    }
}