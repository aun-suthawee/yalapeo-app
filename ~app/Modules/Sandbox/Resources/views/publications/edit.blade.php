@extends('sandbox::layouts.master')

@section('title', 'แก้ไขเอกสาร - ' . $publication->title)

@section('stylesheet-content')
    <link rel="stylesheet" href="{{ asset('assets/common/css/publications.css') }}">
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="container">
            <!-- Back Button -->
            <div class="mb-3">
                <a href="{{ route('sandbox.publications.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> กลับสู่รายการเอกสาร
                </a>
            </div>

            <!-- Header -->
            <div class="page-header mb-4">
                <h1 class="h2 mb-2">
                    <i class="fas fa-edit text-warning"></i>
                    แก้ไขเอกสารเผยแพร่
                </h1>
                <p class="text-muted mb-0">{{ $publication->title }}</p>
            </div>

            <!-- Error Messages -->
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>เกิดข้อผิดพลาด!</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Form -->
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-file-pdf"></i> ข้อมูลเอกสาร
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Current PDF Info -->
                    <div class="alert alert-info">
                        <i class="fas fa-file-pdf"></i>
                        <strong>ไฟล์ปัจจุบัน:</strong> {{ $publication->pdf_filename }}
                        <a href="{{ route('sandbox.publications.show', $publication->id) }}" 
                           target="_blank" 
                           class="btn btn-sm btn-primary ms-2">
                            <i class="fas fa-eye"></i> ดูเอกสาร
                        </a>
                    </div>

                    <form action="{{ route('sandbox.publications.update', $publication->id) }}" method="POST" enctype="multipart/form-data" id="publicationForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Title -->
                            <div class="col-md-12 mb-3">
                                <label for="title" class="form-label">ชื่อเอกสาร <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('title') is-invalid @enderror" 
                                       id="title" 
                                       name="title" 
                                       value="{{ old('title', $publication->title) }}" 
                                       required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">คำอธิบาย</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="3">{{ old('description', $publication->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">คำอธิบายเกี่ยวกับเอกสารนี้</small>
                            </div>

                            <!-- PDF File (Optional for update) -->
                            <div class="col-md-12 mb-3">
                                <label for="pdf_file" class="form-label">เปลี่ยนไฟล์ PDF (ถ้าต้องการ)</label>
                                <input type="file" 
                                       class="form-control @error('pdf_file') is-invalid @enderror" 
                                       id="pdf_file" 
                                       name="pdf_file" 
                                       accept=".pdf">
                                @error('pdf_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> 
                                    เว้นว่างไว้หากไม่ต้องการเปลี่ยนไฟล์ (รองรับไฟล์ PDF เท่านั้น, ขนาดไม่เกิน 10 MB)
                                </small>
                                <div id="file-preview" class="mt-2"></div>
                            </div>

                            <!-- Display Order -->
                            <div class="col-md-12 mb-3">
                                <label for="display_order" class="form-label">ลำดับการแสดง</label>
                                <input type="number" 
                                       class="form-control @error('display_order') is-invalid @enderror" 
                                       id="display_order" 
                                       name="display_order" 
                                       value="{{ old('display_order', $publication->display_order) }}"
                                       min="0">
                                @error('display_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">ตัวเลขน้อยจะแสดงก่อน (0 = แสดงท้ายสุด)</small>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-2 justify-content-end mt-4 pt-3 border-top">
                            <a href="{{ route('sandbox.publications.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> ยกเลิก
                            </a>
                            <button type="submit" class="btn btn-warning" id="submitBtn">
                                <i class="fas fa-save"></i> บันทึกการแก้ไข
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script-content')
    <script src="{{ asset('assets/common/js/publications.js') }}"></script>
@endsection
