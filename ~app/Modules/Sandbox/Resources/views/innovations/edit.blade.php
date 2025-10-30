@extends('sandbox::layouts.master')

@section('title', 'แก้ไขนวัตกรรม - ' . $school->name)

@section('stylesheet-content')
<link rel="stylesheet" href="{{ asset('assets/common/css/school-innovation-form.css') }}">
@endsection

@section('content')
<div class="innovation-form-container">
    <div class="container">
        <!-- Header -->
        <div class="page-header">
            <div class="header-content">
                <h1 class="page-title">
                    <i class="fas fa-edit"></i>
                    แก้ไขนวัตกรรม
                </h1>
                <p class="page-subtitle">{{ $innovation->title }}</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('sandbox.schools.innovations', $school->id) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> กลับ
                </a>
                <a href="{{ route('sandbox.schools.show', $school->id) }}" class="btn btn-outline">
                    <i class="fas fa-school"></i> โรงเรียน
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="form-card">
            @if ($errors->any())
            <div class="alert alert-danger">
                <h4><i class="fas fa-exclamation-triangle"></i> พบข้อผิดพลาด</h4>
                <ul class="error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('sandbox.schools.innovations.update', [$school->id, $innovation->id]) }}" method="POST" enctype="multipart/form-data" class="innovation-form">
                @csrf
                @method('PUT')

                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-info-circle"></i>
                        ข้อมูลพื้นฐาน
                    </h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="title" class="form-label required">
                                <i class="fas fa-tag"></i>
                                ชื่อนวัตกรรม
                            </label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $innovation->title) }}" 
                                   placeholder="ระบุชื่อนวัตกรรม"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="category" class="form-label">
                                <i class="fas fa-folder"></i>
                                หมวดหมู่
                            </label>
                            <select class="form-control @error('category') is-invalid @enderror" 
                                    id="category" 
                                    name="category">
                                <option value="">เลือกหมวดหมู่...</option>
                                <option value="ด้านการบริหารจัดการ" {{ old('category', $innovation->category) == 'ด้านการบริหารจัดการ' ? 'selected' : '' }}>ด้านการบริหารจัดการ</option>
                                <option value="ด้านหลักสูตรสถานศึกษา" {{ old('category', $innovation->category) == 'ด้านหลักสูตรสถานศึกษา' ? 'selected' : '' }}>ด้านหลักสูตรสถานศึกษา</option>
                                <option value="ด้านการจัดการเรียนรู้" {{ old('category', $innovation->category) == 'ด้านการจัดการเรียนรู้' ? 'selected' : '' }}>ด้านการจัดการเรียนรู้</option>
                                <option value="ด้านการนิเทศการศึกษา" {{ old('category', $innovation->category) == 'ด้านการนิเทศการศึกษา' ? 'selected' : '' }}>ด้านการนิเทศการศึกษา</option>
                                <option value="ด้านสื่อเทคโนโลยี" {{ old('category', $innovation->category) == 'ด้านสื่อเทคโนโลยี' ? 'selected' : '' }}>ด้านสื่อเทคโนโลยี</option>
                                <option value="ด้านการประกันคุณภาพการศึกษา" {{ old('category', $innovation->category) == 'ด้านการประกันคุณภาพการศึกษา' ? 'selected' : '' }}>ด้านการประกันคุณภาพการศึกษา</option>
                                <option value="ด้านการวัดและประเมินผล" {{ old('category', $innovation->category) == 'ด้านการวัดและประเมินผล' ? 'selected' : '' }}>ด้านการวัดและประเมินผล</option>
                                <option value="ด้านอื่น ๆ" {{ old('category', $innovation->category) == 'ด้านอื่น ๆ' ? 'selected' : '' }}>ด้านอื่น ๆ</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">
                            <i class="fas fa-align-left"></i>
                            รายละเอียด
                        </label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="5" 
                                  placeholder="อธิบายรายละเอียดของนวัตกรรม...">{{ old('description', $innovation->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text">สูงสุด 2000 ตัวอักษร</small>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="year" class="form-label">
                                <i class="fas fa-calendar"></i>
                                ปีที่เริ่มใช้งาน (พ.ศ.)
                            </label>
                            <input type="number" 
                                   class="form-control @error('year') is-invalid @enderror" 
                                   id="year" 
                                   name="year" 
                                   value="{{ old('year', $innovation->year) }}" 
                                   placeholder="เช่น 2567"
                                   min="2543"
                                   max="2573">
                            @error('year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text">ระบุปี พ.ศ. ที่เริ่มใช้นวัตกรรมนี้</small>
                        </div>

                        <div class="form-group">
                            <label for="is_active" class="form-label">
                                <i class="fas fa-toggle-on"></i>
                                สถานะ
                            </label>
                            <select class="form-control @error('is_active') is-invalid @enderror" 
                                    id="is_active" 
                                    name="is_active">
                                <option value="1" {{ old('is_active', $innovation->is_active) == 1 ? 'selected' : '' }}>ใช้งานอยู่</option>
                                <option value="0" {{ old('is_active', $innovation->is_active) == 0 ? 'selected' : '' }}>ไม่ใช้งาน</option>
                            </select>
                            @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-file-upload"></i>
                        ไฟล์แนบ
                    </h3>
                    
                    @if($innovation->image_paths && count($innovation->image_paths) > 0)
                    <div class="current-files mb-3">
                        <label class="form-label">ไฟล์ปัจจุบัน:</label>
                        <div class="current-files-grid">
                            @foreach($innovation->image_paths as $index => $filePath)
                                @php
                                    $fileName = basename($filePath);
                                    $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                                    $isPDF = $extension === 'pdf';
                                    $imageUrl = route('sandbox.innovation.image', [$school->id, $fileName]);
                                @endphp
                                <div class="current-file-item">
                                    @if($isPDF)
                                        <div class="file-preview pdf-preview">
                                            <i class="fas fa-file-pdf pdf-icon"></i>
                                            <div class="file-info">
                                                <span class="file-name">{{ $fileName }}</span>
                                                <span class="file-type-badge pdf">PDF</span>
                                                <a href="{{ $imageUrl }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                                                    <i class="fas fa-external-link-alt"></i> เปิดดู
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="file-preview image-preview">
                                            <img src="{{ $imageUrl }}" 
                                                 alt="{{ $innovation->title }}" 
                                                 class="preview-image"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                            <div class="error-placeholder" style="display: none;">
                                                <i class="fas fa-image"></i>
                                                <small>ไม่สามารถแสดงรูปได้</small>
                                            </div>
                                            <div class="file-info">
                                                <span class="file-name">{{ $fileName }}</span>
                                                <span class="file-type-badge image">รูปภาพ</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-info-circle"></i> อัปโหลดไฟล์ใหม่เพื่อเปลี่ยนไฟล์แนบ (ไฟล์เก่าจะถูกเปลี่ยนแปลง)
                        </small>
                    </div>
                    @endif

                    <div class="form-group">
                        <label for="files" class="form-label">
                            <i class="fas fa-paperclip"></i>
                            {{ ($innovation->image_paths && count($innovation->image_paths) > 0) ? 'เปลี่ยนไฟล์แนบ' : 'อัปโหลดไฟล์แนบ' }}
                        </label>
                        <div class="file-upload-wrapper">
                            <input type="file" 
                                   class="form-control-file @error('files') is-invalid @enderror" 
                                   id="files" 
                                   name="files[]" 
                                   multiple 
                                   accept="image/*,.pdf,.webp">
                            <div class="file-upload-info">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span>เลือกไฟล์ (JPG, PNG, GIF, WEBP, PDF ขนาดไม่เกิน 5MB ต่อไฟล์ สูงสุด 5 ไฟล์)</span>
                            </div>
                        </div>
                        @error('files')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @error('files.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="file-preview-container" id="filePreviewContainer">
                            <!-- File previews will be shown here -->
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i>
                        บันทึกการแก้ไข
                    </button>
                    <a href="{{ route('sandbox.schools.innovations', $school->id) }}" class="btn btn-secondary btn-lg">
                        <i class="fas fa-times"></i>
                        ยกเลิก
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script-content')
<script src="{{ asset('assets/common/js/school-innovation-form.js') }}"></script>
<script>
// Image preview functionality
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImage').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        document.getElementById('imagePreview').style.display = 'none';
    }
});

// Form validation
document.querySelector('.innovation-form').addEventListener('submit', function(e) {
    const title = document.getElementById('title').value.trim();
    
    if (!title) {
        e.preventDefault();
        alert('กรุณากรอกชื่อนวัตกรรม');
        document.getElementById('title').focus();
        return false;
    }
});
</script>
@endsection
