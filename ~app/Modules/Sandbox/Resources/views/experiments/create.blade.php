@extends('sandbox::layouts.master')

@section('stylesheet-content')
<style>
.create-experiment-page {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 40px 0;
}

.create-card {
    background: white;
    border-radius: 16px;
    padding: 32px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    margin-bottom: 24px;
}

.form-section {
    margin-bottom: 32px;
}

.form-section-title {
    font-size: 18px;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 2px solid #e2e8f0;
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-label i {
    color: #667eea;
}

.form-control, .form-select {
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    padding: 12px 16px;
    font-size: 14px;
    transition: all 0.2s;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-text {
    color: #718096;
    font-size: 13px;
    margin-top: 6px;
}

.info-box {
    background: #f7fafc;
    border-left: 4px solid #667eea;
    padding: 16px;
    border-radius: 8px;
    margin-bottom: 24px;
}

.info-box h4 {
    font-size: 14px;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 8px;
}

.info-box p {
    font-size: 13px;
    color: #4a5568;
    margin: 0;
}

.type-selector {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.type-card {
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 20px;
    cursor: pointer;
    transition: all 0.2s;
    text-align: center;
}

.type-card:hover {
    border-color: #667eea;
    transform: translateY(-2px);
}

.type-card.active {
    border-color: #667eea;
    background: #f0f4ff;
}

.type-card input[type="radio"] {
    display: none;
}

.type-card i {
    font-size: 32px;
    color: #667eea;
    margin-bottom: 12px;
}

.type-card h4 {
    font-size: 16px;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 8px;
}

.type-card p {
    font-size: 13px;
    color: #718096;
    margin: 0;
}

.btn-create {
    background: #667eea;
    color: white;
    padding: 14px 32px;
    border-radius: 8px;
    font-weight: 600;
    border: none;
    transition: all 0.2s;
}

.btn-create:hover {
    background: #5568d3;
    transform: translateY(-2px);
}

.switch {
    position: relative;
    display: inline-block;
    width: 48px;
    height: 24px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #cbd5e0;
    transition: .4s;
    border-radius: 24px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

input:checked + .slider {
    background-color: #667eea;
}

input:checked + .slider:before {
    transform: translateX(24px);
}
</style>
@endsection

@section('content')
<div class="create-experiment-page">
    <div class="container" style="max-width: 900px;">
        <!-- Header -->
        <div class="text-center text-white mb-4">
            <h2>
                <i class="fas fa-flask me-2"></i>สร้าง Experiment ใหม่
            </h2>
            <p class="mb-0">กำหนดพารามิเตอร์และสถานการณ์สมมติเพื่อวิเคราะห์ผลลัพธ์</p>
        </div>

        <!-- Info Box -->
        <div class="info-box">
            <h4><i class="fas fa-info-circle me-2"></i>What-If Analysis คืออะไร?</h4>
            <p>
                เป็นเครื่องมือวิเคราะห์สถานการณ์สมมติ (Scenario Analysis) ที่ช่วยให้คุณทดลองเปลี่ยนแปลงตัวแปรต่างๆ 
                เช่น อัตราส่วนนักเรียน:ครู งบประมาณ ชั่วโมงอบรม แล้วดูว่าจะส่งผลต่อคะแนนสอบอย่างไร
            </p>
        </div>

        <form action="{{ route('sandbox.experiments.store') }}" method="POST">
            @csrf

            <!-- Basic Information -->
            <div class="create-card">
                <div class="form-section">
                    <h3 class="form-section-title">
                        <i class="fas fa-info-circle"></i> ข้อมูลพื้นฐาน
                    </h3>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-tag"></i>
                            ชื่อ Experiment <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" placeholder="เช่น: ผลกระทบของการเพิ่มครูต่อคะแนนสอบ" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-align-left"></i>
                            คำอธิบาย
                        </label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="4" placeholder="อธิบายจุดประสงค์และสิ่งที่ต้องการวิเคราะห์...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text">
                            <i class="fas fa-lightbulb"></i> แนะนำ: อธิบายว่าต้องการทดลองอะไร และคาดหวังผลลัพธ์อย่างไร
                        </small>
                    </div>
                </div>

                <!-- Experiment Type -->
                <div class="form-section">
                    <h3 class="form-section-title">
                        <i class="fas fa-sliders-h"></i> ประเภท Experiment
                    </h3>

                    <div class="type-selector">
                        <label class="type-card active">
                            <input type="radio" name="type" value="what_if" checked>
                            <i class="fas fa-question-circle"></i>
                            <h4>What-If Analysis</h4>
                            <p>วิเคราะห์ "ถ้า...จะเป็นอย่างไร"</p>
                        </label>

                        <label class="type-card">
                            <input type="radio" name="type" value="scenario_comparison">
                            <i class="fas fa-code-branch"></i>
                            <h4>Scenario Comparison</h4>
                            <p>เปรียบเทียบหลายสถานการณ์</p>
                        </label>

                        <label class="type-card">
                            <input type="radio" name="type" value="impact_analysis">
                            <i class="fas fa-chart-line"></i>
                            <h4>Impact Analysis</h4>
                            <p>วิเคราะห์ผลกระทบ</p>
                        </label>
                    </div>
                </div>

                <!-- Data Settings -->
                <div class="form-section">
                    <h3 class="form-section-title">
                        <i class="fas fa-database"></i> ข้อมูลฐาน (Baseline)
                    </h3>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-calendar"></i>
                                    ปีการศึกษาฐาน <span class="text-danger">*</span>
                                </label>
                                <select name="base_year" class="form-select @error('base_year') is-invalid @enderror" required>
                                    @foreach($availableYears as $year)
                                        <option value="{{ $year }}" {{ old('base_year', $currentYear) == $year ? 'selected' : '' }}>
                                            {{ $year + 543 }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('base_year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text">
                                    ข้อมูลจะถูกสำรองจากปีนี้เป็น baseline
                                </small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label d-flex justify-content-between align-items-center">
                                    <span>
                                        <i class="fas fa-globe"></i>
                                        สาธารณะ (ให้คนอื่นเห็นได้)
                                    </span>
                                    <label class="switch mb-0">
                                        <input type="checkbox" name="is_public" value="1" {{ old('is_public') ? 'checked' : '' }}>
                                        <span class="slider"></span>
                                    </label>
                                </label>
                                <small class="form-text">
                                    หากเปิดใช้ คนอื่นสามารถดูและคัดลอก experiment นี้ได้
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Baseline Filter -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <i class="fas fa-filter me-2"></i>กรองข้อมูล Baseline (ไม่บังคับ)
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">ประเภทการกรอง</label>
                                                <select id="baseline_filter_type" name="baseline_filter_type" class="form-select">
                                                    <option value="">-- ทั้งหมด --</option>
                                                    <option value="school_id">โรงเรียนเฉพาะ</option>
                                                    <option value="department">สังกัด</option>
                                                    <option value="district">อำเภอ</option>
                                                    <option value="subdistrict">ตำบล</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="form-label">เลือกค่า</label>
                                                <select id="baseline_filter_value" name="baseline_filter_value" class="form-select" disabled>
                                                    <option value="">-- กรุณาเลือกประเภทการกรองก่อน --</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <small class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        หากไม่เลือก จะใช้ข้อมูลทั้งหมด / เลือกเพื่อจำกัดขอบเขตข้อมูล Baseline
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Current Data Summary -->
                    <div class="alert alert-info mt-3">
                        <h6 class="mb-2"><i class="fas fa-chart-bar me-2"></i>ข้อมูลปัจจุบัน</h6>
                        <div class="row text-center">
                            <div class="col-md-6">
                                <div class="fw-bold text-primary" style="font-size: 24px;">{{ $schoolsCount }}</div>
                                <small>โรงเรียนทั้งหมด</small>
                            </div>
                            <div class="col-md-6">
                                <div class="fw-bold text-success" style="font-size: 24px;">{{ $resultsCount }}</div>
                                <small>มีข้อมูลคะแนนสอบ</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-between gap-3">
                <a href="{{ route('sandbox.experiments.index') }}" class="btn btn-outline-light">
                    <i class="fas fa-times me-2"></i>ยกเลิก
                </a>
                <button type="submit" class="btn btn-create">
                    <i class="fas fa-check me-2"></i>สร้าง Experiment
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script-content')
<script>
const filterData = {
    schools: @json($schools),
    departments: @json($departments),
    districts: @json($districts),
    subDistricts: @json($subDistricts)
};

document.addEventListener('DOMContentLoaded', function() {
    // Type selector
    const typeCards = document.querySelectorAll('.type-card');
    typeCards.forEach(card => {
        card.addEventListener('click', function() {
            typeCards.forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            this.querySelector('input[type="radio"]').checked = true;
        });
    });

    // Baseline filter
    const filterType = document.getElementById('baseline_filter_type');
    const filterValue = document.getElementById('baseline_filter_value');

    filterType.addEventListener('change', function() {
        const type = this.value;
        filterValue.innerHTML = '<option value="">-- เลือก --</option>';
        
        if (!type) {
            filterValue.disabled = true;
            return;
        }

        filterValue.disabled = false;

        if (type === 'school_id') {
            filterData.schools.forEach(school => {
                const option = document.createElement('option');
                option.value = school.id;
                option.textContent = school.name;
                filterValue.appendChild(option);
            });
        } else if (type === 'department') {
            filterData.departments.forEach(dept => {
                const option = document.createElement('option');
                option.value = dept;
                option.textContent = dept;
                filterValue.appendChild(option);
            });
        } else if (type === 'district') {
            filterData.districts.forEach(district => {
                const option = document.createElement('option');
                option.value = district;
                option.textContent = district;
                filterValue.appendChild(option);
            });
        } else if (type === 'subdistrict') {
            filterData.subDistricts.forEach(subDistrict => {
                const option = document.createElement('option');
                option.value = subDistrict;
                option.textContent = subDistrict;
                filterValue.appendChild(option);
            });
        }
    });
});
</script>
@endsection
