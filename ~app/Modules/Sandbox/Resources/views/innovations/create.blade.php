@extends('sandbox::layouts.master')

@section('title', 'เพิ่มนวัตกรรมใหม่ - ' . $school->name)

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
                        <i class="fas fa-lightbulb"></i>
                        เพิ่มนวัตกรรมใหม่
                    </h1>
                    <p class="page-subtitle">สำหรับ {{ $school->name }}</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('sandbox.schools.show', $school->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> กลับ
                    </a>
                    <a href="{{ route('sandbox.schools.innovations', $school->id) }}" class="btn btn-outline">
                        <i class="fas fa-list"></i> จัดการทั้งหมด
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

                <form action="{{ route('sandbox.schools.innovations.store', $school->id) }}" method="POST"
                    enctype="multipart/form-data" class="innovation-form">
                    @csrf

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
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title') }}" placeholder="ระบุชื่อนวัตกรรม"
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
                                <select class="form-control @error('category') is-invalid @enderror" id="category"
                                    name="category">
                                    <option value="">เลือกหมวดหมู่...</option>
                                    <option value="ด้านการบริหารจัดการ"
                                        {{ old('category') == 'ด้านการบริหารจัดการ' ? 'selected' : '' }}>ด้านการบริหารจัดการ
                                    </option>
                                    <option value="ด้านหลักสูตรสถานศึกษา"
                                        {{ old('category') == 'ด้านหลักสูตรสถานศึกษา' ? 'selected' : '' }}>
                                        ด้านหลักสูตรสถานศึกษา</option>
                                    <option value="ด้านการจัดการเรียนรู้"
                                        {{ old('category') == 'ด้านการจัดการเรียนรู้' ? 'selected' : '' }}>
                                        ด้านการจัดการเรียนรู้</option>
                                    <option value="ด้านการนิเทศการศึกษา"
                                        {{ old('category') == 'ด้านการนิเทศการศึกษา' ? 'selected' : '' }}>
                                        ด้านการนิเทศการศึกษา</option>
                                    <option value="ด้านสื่อเทคโนโลยี"
                                        {{ old('category') == 'ด้านสื่อเทคโนโลยี' ? 'selected' : '' }}>ด้านสื่อเทคโนโลยี
                                    </option>
                                    <option value="ด้านการประกันคุณภาพการศึกษา"
                                        {{ old('category') == 'ด้านการประกันคุณภาพการศึกษา' ? 'selected' : '' }}>
                                        ด้านการประกันคุณภาพการศึกษา</option>
                                    <option value="ด้านการวัดและประเมินผล"
                                        {{ old('category') == 'ด้านการวัดและประเมินผล' ? 'selected' : '' }}>
                                        ด้านการวัดและประเมินผล</option>
                                    <option value="ด้านอื่น ๆ" {{ old('category') == 'ด้านอื่น ๆ' ? 'selected' : '' }}>
                                        ด้านอื่น ๆ</option>
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
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                rows="4" placeholder="อธิบายรายละเอียดของนวัตกรรม วัตถุประสงค์ วิธีการใช้งาน ผลลัพธ์ที่ได้">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-calendar-alt"></i>
                            ข้อมูลเพิ่มเติม
                        </h3>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="year" class="form-label">
                                    <i class="fas fa-calendar"></i>
                                    ปีที่เริ่มใช้งาน (พ.ศ.)
                                </label>
                                <select class="form-control @error('year') is-invalid @enderror" id="year"
                                    name="year">
                                    <option value="">เลือกปี (พ.ศ.)...</option>
                                    @php
                                        $currentYear = date('Y') + 543; // Convert to Buddhist year
                                        $maxYear = 2573; // พ.ศ. 2573 = ค.ศ. 2030
                                        $startYear = $currentYear - 15; // ย้อนหลัง 15 ปี
                                        $endYear = min($currentYear + 5, $maxYear); // ไปข้างหน้า 5 ปีหรือไม่เกิน 2573
                                    @endphp
                                    @for ($year = $endYear; $year >= $startYear; $year--)
                                        <option value="{{ $year }}" {{ old('year') == $year ? 'selected' : '' }}>
                                            พ.ศ. {{ $year }}
                                        </option>
                                    @endfor
                                </select>
                                @error('year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="status" class="form-label">
                                    <i class="fas fa-toggle-on"></i>
                                    สถานะ
                                </label>
                                <select class="form-control @error('is_active') is-invalid @enderror" id="status"
                                    name="is_active">
                                    <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>ใช้งานอยู่
                                    </option>
                                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>ไม่ใช้งาน
                                    </option>
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

                        <div class="form-group">
                            <label for="files" class="form-label">
                                <i class="fas fa-paperclip"></i>
                                อัปโหลดไฟล์ (รูปภาพ หรือ PDF)
                            </label>
                            <div class="file-upload-wrapper">
                                <input type="file" class="form-control-file @error('files') is-invalid @enderror"
                                    id="files" name="files[]" multiple accept="image/*,.pdf,.webp">
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

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button type="reset" class="btn btn-light">
                            <i class="fas fa-redo"></i>
                            ล้างข้อมูล
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i>
                            บันทึกนวัตกรรม
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script-content')
    <script src="{{ asset('assets/common/js/school-innovation-form.js') }}"></script>
@endsection
