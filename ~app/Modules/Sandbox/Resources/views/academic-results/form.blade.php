@extends('sandbox::layouts.master')

@section('stylesheet-content')
<link rel="stylesheet" href="{{ asset('assets/common/css/academic-results.css?v=' . time()) }}">
@endsection

@section('content')
<div class="academic-results-page academic-results-form">
<div class="container py-4" style="max-width: 1200px;">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 page-header fade-in">
        <div>
            <h2 class="mb-1">
                <i class="fas fa-edit me-2"></i>กรอกผลสัมฤทธิ์ทางการศึกษา
            </h2>
            <p class="text-muted mb-0">{{ $school->name }} | ปีการศึกษา <span class="current-year" data-year="{{ $year }}">{{ $year + 543 }}</span></p>
        </div>
        <div>
            <a href="{{ route('sandbox.academic-results.index', ['year' => $year]) }}" class="btn btn-secondary-custom">
                <i class="fas fa-arrow-left me-2"></i>กลับ
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success-custom alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger-custom alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger-custom alert-dismissible fade show" role="alert">
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
    <form action="{{ route('sandbox.academic-results.update', $school->id) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="academic_year" value="{{ $year }}">

        <div class="card border-0 shadow-sm fade-in">
            <div class="card-body">
                <!-- Tabs -->
                <ul class="nav form-tabs mb-4" id="resultTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="nt-tab" data-bs-toggle="tab" data-bs-target="#nt" type="button" role="tab">
                            <i class="fas fa-calculator me-2"></i>NT (ป.3)
                            @if($academicResult->hasNtScores())
                                <span class="badge bg-success ms-2">
                                    <i class="fas fa-check"></i>
                                </span>
                            @endif
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="rt-tab" data-bs-toggle="tab" data-bs-target="#rt" type="button" role="tab">
                            <i class="fas fa-book-reader me-2"></i>RT (ป.1)
                            @if($academicResult->hasRtScores())
                                <span class="badge bg-success ms-2">
                                    <i class="fas fa-check"></i>
                                </span>
                            @endif
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="onet-tab" data-bs-toggle="tab" data-bs-target="#onet" type="button" role="tab">
                            <i class="fas fa-graduation-cap me-2"></i>O-NET (ป.6/ม.3)
                            @if($academicResult->hasOnetScores())
                                <span class="badge bg-success ms-2">
                                    <i class="fas fa-check"></i>
                                </span>
                            @endif
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="notes-tab" data-bs-toggle="tab" data-bs-target="#notes" type="button" role="tab">
                            <i class="fas fa-sticky-note me-2"></i>หมายเหตุ
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="resultTabContent">
                    <!-- NT Tab -->
                    <div class="tab-pane fade show active" id="nt" role="tabpanel">
                        <div class="info-alert">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>NT (National Test)</strong> - แบบทดสอบทางการศึกษาระดับชาติ ประจำปีการศึกษา สำหรับนักเรียนชั้นประถมศึกษาปีที่ 3
                        </div>
                        
                        <!-- Checkbox for test availability -->
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="has_nt_test" id="has_nt_test" value="1" 
                                   {{ old('has_nt_test', $academicResult->has_nt_test ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="has_nt_test">
                                โรงเรียนนี้มีการทดสอบ NT
                            </label>
                            <small class="text-muted d-block ms-4">หากไม่มีการทดสอบ NT ให้ยกเลิกการเลือก ระบบจะไม่นับว่ายังไม่ส่งข้อมูล</small>
                        </div>
                        
                        <div class="row g-3" id="nt-score-section">
                            <div class="col-md-6 score-input-group">
                                <label class="form-label">คะแนนเฉลี่ยวิชาคณิตศาสตร์</label>
                                <div class="input-group">
                                    <input type="number" 
                                           class="form-control @error('nt_math_score') is-invalid @enderror" 
                                           name="nt_math_score" 
                                           value="{{ old('nt_math_score', $academicResult->nt_math_score) }}"
                                           min="0" 
                                           max="100" 
                                           step="0.01"
                                           placeholder="0.00">
                                    <span class="input-group-text">คะแนน</span>
                                </div>
                                @error('nt_math_score')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">คะแนนเฉลี่ยวิชาภาษาไทย</label>
                                <div class="input-group">
                                    <input type="number" 
                                           class="form-control @error('nt_thai_score') is-invalid @enderror" 
                                           name="nt_thai_score" 
                                           value="{{ old('nt_thai_score', $academicResult->nt_thai_score) }}"
                                           min="0" 
                                           max="100" 
                                           step="0.01"
                                           placeholder="0.00">
                                    <span class="input-group-text">คะแนน</span>
                                </div>
                                @error('nt_thai_score')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        @if($academicResult->nt_average)
                            <div class="alert alert-success mt-3 mb-0">
                                <strong>คะแนนเฉลี่ย NT:</strong> {{ number_format($academicResult->nt_average, 2) }} คะแนน
                            </div>
                        @endif
                    </div>

                    <!-- RT Tab -->
                    <div class="tab-pane fade" id="rt" role="tabpanel">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>RT (Reading Test)</strong> - แบบทดสอบความสามารถในการอ่านของนักเรียนชั้นประถมศึกษาปีที่ 1
                        </div>
                        
                        <!-- Checkbox for test availability -->
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="has_rt_test" id="has_rt_test" value="1"
                                   {{ old('has_rt_test', $academicResult->has_rt_test ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="has_rt_test">
                                โรงเรียนนี้มีการทดสอบ RT
                            </label>
                            <small class="text-muted d-block ms-4">หากไม่มีการทดสอบ RT ให้ยกเลิกการเลือก ระบบจะไม่นับว่ายังไม่ส่งข้อมูล</small>
                        </div>
                        
                        <div class="row g-3" id="rt-score-section">
                            <div class="col-md-6">
                                <label class="form-label">คะแนนเฉลี่ยวิชาการอ่านออกเสียง</label>
                                <div class="input-group">
                                    <input type="number" 
                                           class="form-control @error('rt_reading_score') is-invalid @enderror" 
                                           name="rt_reading_score" 
                                           value="{{ old('rt_reading_score', $academicResult->rt_reading_score) }}"
                                           min="0" 
                                           max="100" 
                                           step="0.01"
                                           placeholder="0.00">
                                    <span class="input-group-text">คะแนน</span>
                                </div>
                                @error('rt_reading_score')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">คะแนนเฉลี่ยวิชาการอ่านรู้เรื่อง</label>
                                <div class="input-group">
                                    <input type="number" 
                                           class="form-control @error('rt_comprehension_score') is-invalid @enderror" 
                                           name="rt_comprehension_score" 
                                           value="{{ old('rt_comprehension_score', $academicResult->rt_comprehension_score) }}"
                                           min="0" 
                                           max="100" 
                                           step="0.01"
                                           placeholder="0.00">
                                    <span class="input-group-text">คะแนน</span>
                                </div>
                                @error('rt_comprehension_score')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        @if($academicResult->rt_average)
                            <div class="alert alert-success mt-3 mb-0">
                                <strong>คะแนนเฉลี่ย RT:</strong> {{ number_format($academicResult->rt_average, 2) }} คะแนน
                            </div>
                        @endif
                    </div>

                    <!-- O-NET Tab -->
                    <div class="tab-pane fade" id="onet" role="tabpanel">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>O-NET (Ordinary National Educational Test)</strong> - การทดสอบทางการศึกษาระดับชาติขั้นพื้นฐาน สำหรับนักเรียนชั้นประถมศึกษาปีที่ 6 และชั้นมัธยมศึกษาปีที่ 3
                        </div>
                        
                        <!-- Checkbox for test availability -->
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="has_onet_test" id="has_onet_test" value="1"
                                   {{ old('has_onet_test', $academicResult->has_onet_test ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="has_onet_test">
                                โรงเรียนนี้มีการทดสอบ O-NET
                            </label>
                            <small class="text-muted d-block ms-4">หากไม่มีการทดสอบ O-NET (เช่น โรงเรียนมีเฉพาะระดับประถมต้น) ให้ยกเลิกการเลือก</small>
                        </div>
                        
                        <div class="row g-3" id="onet-score-section">
                            <div class="col-md-6">
                                <label class="form-label">คะแนนเฉลี่ยวิชาคณิตศาสตร์</label>
                                <div class="input-group">
                                    <input type="number" 
                                           class="form-control @error('onet_math_score') is-invalid @enderror" 
                                           name="onet_math_score" 
                                           value="{{ old('onet_math_score', $academicResult->onet_math_score) }}"
                                           min="0" 
                                           max="100" 
                                           step="0.01"
                                           placeholder="0.00">
                                    <span class="input-group-text">คะแนน</span>
                                </div>
                                @error('onet_math_score')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">คะแนนเฉลี่ยวิชาภาษาไทย</label>
                                <div class="input-group">
                                    <input type="number" 
                                           class="form-control @error('onet_thai_score') is-invalid @enderror" 
                                           name="onet_thai_score" 
                                           value="{{ old('onet_thai_score', $academicResult->onet_thai_score) }}"
                                           min="0" 
                                           max="100" 
                                           step="0.01"
                                           placeholder="0.00">
                                    <span class="input-group-text">คะแนน</span>
                                </div>
                                @error('onet_thai_score')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">คะแนนเฉลี่ยวิชาภาษาอังกฤษ</label>
                                <div class="input-group">
                                    <input type="number" 
                                           class="form-control @error('onet_english_score') is-invalid @enderror" 
                                           name="onet_english_score" 
                                           value="{{ old('onet_english_score', $academicResult->onet_english_score) }}"
                                           min="0" 
                                           max="100" 
                                           step="0.01"
                                           placeholder="0.00">
                                    <span class="input-group-text">คะแนน</span>
                                </div>
                                @error('onet_english_score')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">คะแนนเฉลี่ยวิชาวิทยาศาสตร์</label>
                                <div class="input-group">
                                    <input type="number" 
                                           class="form-control @error('onet_science_score') is-invalid @enderror" 
                                           name="onet_science_score" 
                                           value="{{ old('onet_science_score', $academicResult->onet_science_score) }}"
                                           min="0" 
                                           max="100" 
                                           step="0.01"
                                           placeholder="0.00">
                                    <span class="input-group-text">คะแนน</span>
                                </div>
                                @error('onet_science_score')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        @if($academicResult->onet_average)
                            <div class="alert alert-success mt-3 mb-0">
                                <strong>คะแนนเฉลี่ย O-NET:</strong> {{ number_format($academicResult->onet_average, 2) }} คะแนน
                            </div>
                        @endif
                    </div>

                    <!-- Notes Tab -->
                    <div class="tab-pane fade" id="notes" role="tabpanel">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            กรุณาระบุหมายเหตุหรือข้อมูลเพิ่มเติม (ถ้ามี)
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">หมายเหตุ</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      name="notes" 
                                      rows="5" 
                                      placeholder="ระบุหมายเหตุหรือข้อมูลเพิ่มเติม...">{{ old('notes', $academicResult->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submission Status -->
                @if($academicResult->submitted_at)
                    <div class="alert alert-success mt-4">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>ส่งข้อมูลแล้ว</strong>
                        <br>
                        <small>วันที่ส่ง: {{ $academicResult->submitted_at->format('d/m/Y H:i น.') }}</small>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('sandbox.academic-results.index', ['year' => $year]) }}" class="btn btn-secondary-custom">
                        <i class="fas fa-times me-2"></i>ยกเลิก
                    </a>
                    @auth
                        <button type="submit" class="btn btn-primary-custom">
                            <i class="fas fa-save me-2"></i>บันทึกข้อมูล
                        </button>
                    @endauth
                </div>
            </div>
        </div>
    </form>
</div>
</div>
@endsection

@section('script-content')
<script src="{{ asset('assets/common/js/academic-results.js?v=' . time()) }}"></script>
<script>
    // Additional form-specific scripts
    $(document).ready(function() {
        // Add score-input-group class to all score input containers
        $('input[name*="score"]').closest('.col-md-6').addClass('score-input-group');
        
        // Replace alert classes
        $('.alert-info').addClass('info-alert').removeClass('alert-info');
        $('.alert-success').not('.alert-dismissible').addClass('average-alert').removeClass('alert-success');
        
        // Handle test availability checkboxes
        function toggleTestSection(testType) {
            const checkbox = $(`#has_${testType}_test`);
            const section = $(`#${testType}-score-section`);
            const inputs = section.find('input[type="number"]');
            
            if (checkbox.is(':checked')) {
                // Enable inputs
                inputs.prop('disabled', false);
                section.removeClass('disabled-section');
            } else {
                // Disable inputs and clear values
                inputs.prop('disabled', true).val('');
                section.addClass('disabled-section');
            }
        }
        
        // Initialize on page load
        toggleTestSection('nt');
        toggleTestSection('rt');
        toggleTestSection('onet');
        
        // Handle checkbox change
        $('#has_nt_test').on('change', function() {
            toggleTestSection('nt');
        });
        
        $('#has_rt_test').on('change', function() {
            toggleTestSection('rt');
        });
        
        $('#has_onet_test').on('change', function() {
            toggleTestSection('onet');
        });
    });
</script>
<style>
    .disabled-section {
        opacity: 0.5;
        pointer-events: none;
    }
    
    .form-check-input:checked {
        background-color: #28a745;
        border-color: #28a745;
    }
    
    .form-check-label {
        cursor: pointer;
    }
</style>
@endsection
