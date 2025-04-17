<?php

namespace Modules\ImageBoxSlider\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageBoxSliderModel extends Model
{
    protected $table = 'image_box_sliders'; 
    
    protected $fillable = [
        'title',
        'image',
        'description',
        'pdf_file',
        'is_active',
        'slug'
    ];
    
    protected $casts = [
        'pdf_file' => 'array',
        'is_active' => 'boolean',
    ];

    // Auto-generate slug from title
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($imageBoxSlider) {
            if (empty($imageBoxSlider->slug)) {
                $imageBoxSlider->slug = self::generateSlug($imageBoxSlider->title);
            }
        });

        static::updating(function ($imageBoxSlider) {
            if ($imageBoxSlider->isDirty('title')) {
                $imageBoxSlider->slug = self::generateSlug($imageBoxSlider->title);
            }
        });
    }

    /**
     * Generate a valid unique slug that preserves Thai language
     *
     * @param string $title
     * @return string
     */
    protected static function generateSlug($title)
    {
        // ใช้ชื่อเดิมทั้งหมดรวมภาษาไทยเป็น slug โดยตรง
        $slug = $title;
        
        // แทนช่องว่างด้วยเครื่องหมายขีด (-)
        $slug = str_replace(' ', '-', $slug);
        
        // แทนที่อักขระพิเศษที่ไม่สามารถใช้ใน URL ได้ แต่ยังคงรักษาภาษาไทยไว้
        $slug = preg_replace('/[\/\?\:\@\=\&"\'\<\>\#\%\{\}\|\\\^~\[\]`\+\$\;\,]/u', '', $slug);
        
        // ตรวจสอบว่า slug ซ้ำหรือไม่
        $originalSlug = $slug;
        $count = 1;
        
        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }
        
        return $slug;
    }

    // Get image URL with proper path checking
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            $path = 'storage/image_box_slider/' . $this->image;
            
            // ตรวจสอบว่าไฟล์มีอยู่จริงในระบบไฟล์
            if (file_exists(public_path($path))) {
                return asset($path);
            }
            
            // ถ้าไม่มีไฟล์อยู่จริง ลองเช็คในรูปแบบเต็ม
            return asset('storage/image_box_slider/' . $this->image);
        }
        // ถ้าไม่มีรูปภาพ ส่งคืนรูปภาพเริ่มต้น
        return asset('assets/images/no-image.png');
    }

    // Get PDF URL with proper storage path
    public function getPdfUrlAttribute()
    {
        if ($this->pdf_file && is_array($this->pdf_file) && !empty($this->pdf_file)) {
            foreach ($this->pdf_file as $file) {
                if (isset($file['name_uploaded'])) {
                    return asset('storage/image_box_slider/pdf/' . $file['name_uploaded']);
                }
            }
        }
        return null;
    }
    
    // Get PDF Name
    public function getPdfNameAttribute()
    {
        if ($this->pdf_file && is_array($this->pdf_file) && !empty($this->pdf_file)) {
            foreach ($this->pdf_file as $file) {
                if (isset($file['name'])) {
                    return $file['name'];
                }
            }
        }
        return null;
    }

    // ตรวจสอบว่ามีไฟล์ PDF หรือไม่
    public function hasPdf()
    {
        return $this->pdf_file && is_array($this->pdf_file) && !empty($this->pdf_file);
    }
}