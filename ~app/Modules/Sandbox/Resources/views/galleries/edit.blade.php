@extends('sandbox::layouts.master')

@section('title', 'แก้ไขคลังภาพกิจกรรม - ' . $gallery->title)

@section('stylesheet-content')
    <link rel="stylesheet" href="{{ asset('assets/common/css/school-gallery.css') }}">
@endsection

@section('content')
    <div class="container py-4">
        <!-- Header Section -->
        <div class="page-header mb-4">
            <h1 class="h2 mb-2">
                <i class="fas fa-edit text-primary"></i>
                แก้ไขคลังภาพกิจกรรม
            </h1>
            <p class="text-muted mb-0">{{ $school->name }}</p>
        </div>

        <!-- Form Card -->
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('sandbox.schools.galleries.update', [$school->id, $gallery->id]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="title" class="form-label">ชื่อคลังภาพ <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('title') is-invalid @enderror" 
                               id="title" 
                               name="title" 
                               value="{{ old('title', $gallery->title) }}" 
                               required
                               placeholder="เช่น กิจกรรมวันเด็ก 2568">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label">คำอธิบาย</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="4"
                                  placeholder="รายละเอียดเกี่ยวกับกิจกรรม...">{{ old('description', $gallery->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="drive_folder_url" class="form-label">
                            URL ของ Google Drive Folder <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('drive_folder_url') is-invalid @enderror" 
                               id="drive_folder_url" 
                               name="drive_folder_url" 
                               value="{{ old('drive_folder_url', $gallery->drive_folder_url) }}" 
                               required
                               placeholder="https://drive.google.com/drive/folders/1qGwpjmQIQO8rN1odas0njDSf72VRrTCa?usp=sharing">
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i>
                            Folder ID ปัจจุบัน: <code>{{ $gallery->drive_folder_id }}</code>
                        </small>
                        @error('drive_folder_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="display_order" class="form-label">ลำดับการแสดง</label>
                        <input type="number" 
                               class="form-control @error('display_order') is-invalid @enderror" 
                               id="display_order" 
                               name="display_order" 
                               value="{{ old('display_order', $gallery->display_order) }}" 
                               min="0"
                               placeholder="0">
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i>
                            ตัวเลขน้อยจะแสดงก่อน (0 = แสดงแรกสุด)
                        </small>
                        @error('display_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="1" 
                                   {{ old('is_active', $gallery->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                แสดงคลังภาพนี้
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> บันทึกการแก้ไข
                        </button>
                        <a href="{{ route('sandbox.schools.galleries.index', $school->id) }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> ยกเลิก
                        </a>
                        <a href="{{ route('sandbox.schools.galleries.show', [$school->id, $gallery->id]) }}" class="btn btn-outline-primary ms-auto">
                            <i class="fas fa-eye"></i> ดูคลังภาพ
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script-content')
    <script src="{{ asset('assets/common/js/school-gallery.js') }}"></script>
@endsection
