@extends('sandbox::layouts.master')

@section('stylesheet-content')
<link rel="stylesheet" href="{{ asset('assets/common/css/academic-results.css?v=' . time()) }}">
@endsection

@section('content')
<div class="academic-results-page academic-results-index">
<div class="container-fluid py-4" style="max-width: 1400px;">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 page-header fade-in">
        <div>
            <h2 class="mb-1">
                <i class="fas fa-chart-line me-2"></i>ผลสัมฤทธิ์ทางการศึกษา
            </h2>
            <p class="text-muted mb-0">NT (ป.3), RT (ป.1), O-NET (ป.6/ม.3) | ปีการศึกษา <span class="current-year" data-year="{{ $selectedYear }}">{{ $selectedYear + 543 }}</span></p>
        </div>
        <div class="d-flex gap-2">
            @auth
                <div class="dropdown">
                    <button class="btn btn-success dropdown-toggle" type="button" id="exportDropdownResults" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-download"></i> Export ข้อมูล
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="exportDropdownResults">
                        <li>
                            <a class="dropdown-item" href="{{ route('sandbox.academic-results.export.csv', ['year' => $selectedYear]) }}">
                                <i class="fas fa-file-csv"></i> Export CSV
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('sandbox.academic-results.export.excel', ['year' => $selectedYear]) }}">
                                <i class="fas fa-file-excel"></i> Export Excel
                            </a>
                        </li>
                    </ul>
                </div>
            @endauth
            <a href="{{ route('sandbox.dashboard') }}" class="btn btn-secondary-custom">
                <i class="fas fa-arrow-left me-2"></i>กลับหน้าหลัก
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4 fade-in">
        <div class="col-md-4">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="stats-icon-wrapper primary">
                        <i class="fas fa-school"></i>
                    </div>
                    <div class="stats-content ms-3">
                        <div class="stats-label">จำนวนโรงเรียนทั้งหมด</div>
                        <div class="stats-value">{{ number_format($totalSchools) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="stats-icon-wrapper success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stats-content ms-3">
                        <div class="stats-label">ส่งข้อมูลแล้ว</div>
                        <div class="stats-value">{{ number_format($submittedCount) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="stats-icon-wrapper warning">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="stats-content ms-3">
                        <div class="stats-label">ยังไม่ได้ส่งข้อมูล</div>
                        <div class="stats-value">{{ number_format($notSubmittedCount) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filter-card mb-4 fade-in">
        <form method="GET" action="{{ route('sandbox.academic-results.index') }}" id="filterForm" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">ปีการศึกษา</label>
                <select name="year" class="form-select">
                    @foreach($availableYears as $year)
                        <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                            {{ $year + 543 }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">สถานะการส่งข้อมูล</label>
                <select name="filter" class="form-select">
                    <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>ทั้งหมด</option>
                    <option value="submitted" {{ $filter == 'submitted' ? 'selected' : '' }}>ส่งข้อมูลแล้ว</option>
                    <option value="not_submitted" {{ $filter == 'not_submitted' ? 'selected' : '' }}>ยังไม่ได้ส่งข้อมูล</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">เขตพื้นที่การศึกษา</label>
                <select name="education_area" class="form-select">
                    <option value="">ทั้งหมด</option>
                    @foreach($educationAreas as $area)
                        <option value="{{ $area }}" {{ $educationArea == $area ? 'selected' : '' }}>{{ $area }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">สังกัดกระทรวง</label>
                <select name="ministry" class="form-select">
                    <option value="">ทั้งหมด</option>
                    @foreach($ministries as $min)
                        <option value="{{ $min }}" {{ $ministry == $min ? 'selected' : '' }}>{{ $min }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">สังกัดสำนัก/กอง</label>
                <select name="bureau" class="form-select">
                    <option value="">ทั้งหมด</option>
                    @foreach($bureaus as $bur)
                        <option value="{{ $bur }}" {{ $bureau == $bur ? 'selected' : '' }}>{{ $bur }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">อำเภอ</label>
                <select name="district" id="districtSelect" class="form-select">
                    <option value="">ทั้งหมด</option>
                    @foreach($districts as $dist)
                        <option value="{{ $dist }}" {{ $district == $dist ? 'selected' : '' }}>{{ $dist }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">ตำบล</label>
                <select name="subdistrict" id="subdistrictSelect" class="form-select">
                    <option value="">ทั้งหมด</option>
                    @foreach($subdistricts as $sub)
                        <option value="{{ $sub }}" {{ $subdistrict == $sub ? 'selected' : '' }}>{{ $sub }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary-custom me-2">
                    <i class="fas fa-filter me-2"></i>ค้นหา
                </button>
                <a href="{{ route('sandbox.academic-results.index') }}" class="btn btn-secondary-custom">
                    <i class="fas fa-redo me-2"></i>รีเซ็ต
                </a>
            </div>
        </form>
    </div>

    <!-- Education area summary -->
    @if($educationAreaSummaries->count())
        <div class="card mb-4 fade-in">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-layer-group me-2"></i>ภาพรวมตามเขตพื้นที่การศึกษา
                </h5>
                <small class="text-muted">จำนวนโรงเรียนที่ส่งข้อมูลในปี {{ $selectedYear + 543 }}</small>
            </div>
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>เขตพื้นที่</th>
                            <th class="text-end" width="140">โรงเรียนทั้งหมด</th>
                            <th class="text-end" width="140">ส่งข้อมูลแล้ว</th>
                            <th class="text-end" width="140">ยังไม่ส่ง</th>
                            <th class="text-end" width="120">อัตราส่ง (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($educationAreaSummaries as $summary)
                            @php
                                $completionRate = $summary['total'] > 0
                                    ? round(($summary['submitted'] / $summary['total']) * 100, 1)
                                    : 0;
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $summary['short_label'] }}</strong>
                                    @if($summary['label'] !== $summary['short_label'])
                                        <div class="text-muted small">{{ $summary['label'] }}</div>
                                    @endif
                                </td>
                                <td class="text-end">{{ number_format($summary['total']) }}</td>
                                <td class="text-end text-success">{{ number_format($summary['submitted']) }}</td>
                                <td class="text-end text-danger">{{ number_format($summary['pending']) }}</td>
                                <td class="text-end">{{ number_format($completionRate, 1) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Schools List -->
    <div class="schools-table-card fade-in">
        <div class="table-responsive">
            <table class="table schools-table align-middle">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th>ชื่อโรงเรียน</th>
                            <th class="text-center" width="120">NT (ป.3)</th>
                            <th class="text-center" width="120">RT (ป.1)</th>
                            <th class="text-center" width="120">O-NET</th>
                            <th class="text-center" width="150">สถานะ</th>
                            <th class="text-center" width="180">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schools as $index => $school)
                            @php
                                $result = $school->academicResults->first();
                                
                                // Check if has test data (considering availability flags)
                                $hasNt = $result && $result->hasNtScores();
                                $hasRt = $result && $result->hasRtScores();
                                $hasOnet = $result && $result->hasOnetScores();
                                
                                // Check completion status
                                $isComplete = $result && $result->isComplete();
                                $isSubmitted = $result && $result->submitted_at;
                                
                                // Check if test is applicable for this school
                                $ntApplicable = !$result || $result->has_nt_test;
                                $rtApplicable = !$result || $result->has_rt_test;
                                $onetApplicable = !$result || $result->has_onet_test;
                            @endphp
                            <tr>
                                <td>{{ $schools->firstItem() + $index }}</td>
                                <td>
                                    <div>
                                        <strong>{{ $school->name }}</strong>
                                        @if($school->school_code)
                                            <small class="text-muted d-block">รหัส: {{ $school->school_code }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if(!$ntApplicable)
                                        <span class="status-badge" style="background: #e2e8f0; color: #64748b;">
                                            <i class="fas fa-ban"></i> N/A
                                        </span>
                                    @elseif($hasNt)
                                        <span class="status-badge has-data">
                                            <i class="fas fa-check"></i> มีข้อมูล
                                        </span>
                                    @else
                                        <span class="status-badge no-data">
                                            <i class="fas fa-minus"></i> ไม่มี
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(!$rtApplicable)
                                        <span class="status-badge" style="background: #e2e8f0; color: #64748b;">
                                            <i class="fas fa-ban"></i> N/A
                                        </span>
                                    @elseif($hasRt)
                                        <span class="status-badge has-data">
                                            <i class="fas fa-check"></i> มีข้อมูล
                                        </span>
                                    @else
                                        <span class="status-badge no-data">
                                            <i class="fas fa-minus"></i> ไม่มี
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(!$onetApplicable)
                                        <span class="status-badge" style="background: #e2e8f0; color: #64748b;">
                                            <i class="fas fa-ban"></i> N/A
                                        </span>
                                    @elseif($hasOnet)
                                        <span class="status-badge has-data">
                                            <i class="fas fa-check"></i> มีข้อมูล
                                        </span>
                                    @else
                                        <span class="status-badge no-data">
                                            <i class="fas fa-minus"></i> ไม่มี
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($isSubmitted)
                                        <span class="status-badge submitted">
                                            <i class="fas fa-check-circle"></i> ส่งแล้ว
                                        </span>
                                        <small class="text-muted d-block mt-1">
                                            @php
                                                $thaiMonths = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
                                                $submittedDate = $result->submitted_at->setTimezone('Asia/Bangkok');
                                                $day = $submittedDate->format('d');
                                                $month = $thaiMonths[$submittedDate->format('n') - 1];
                                                $year = $submittedDate->format('Y') + 543;
                                                $time = $submittedDate->format('H:i');
                                            @endphp
                                            {{ $day }} {{ $month }} {{ $year }} {{ $time }} น.
                                        </small>
                                    @else
                                        <span class="status-badge pending">
                                            <i class="fas fa-clock"></i> รอส่งข้อมูล
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @auth
                                        <a href="{{ route('sandbox.academic-results.edit', ['school' => $school->id, 'year' => $selectedYear]) }}" 
                                           class="action-btn edit">
                                            <i class="fas fa-edit"></i> กรอก/แก้ไข
                                        </a>
                                        @if($result && $result->hasAnyScores())
                                            <form action="{{ route('sandbox.academic-results.destroy', $school->id) }}" 
                                                  method="POST" 
                                                  class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="year" value="{{ $selectedYear }}">
                                                <button type="submit" class="action-btn delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <a href="{{ route('sandbox.academic-results.edit', ['school' => $school->id, 'year' => $selectedYear]) }}" 
                                           class="action-btn view">
                                            <i class="fas fa-eye"></i> ดูข้อมูล
                                        </a>
                                    @endauth
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <p>ไม่พบข้อมูลโรงเรียน</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($schools->hasPages())
                <div class="d-flex justify-content-center p-4">
                    {{ $schools->appends(request()->query())->links() }}
                </div>
            @endif
    </div>
</div>
</div>
@endsection

@section('script-content')
<script src="{{ asset('assets/common/js/academic-results.js?v=' . time()) }}"></script>
<script>
    // Dynamic Subdistrict Filter based on District selection
    document.addEventListener('DOMContentLoaded', function() {
        const districtSelect = document.getElementById('districtSelect');
        const subdistrictSelect = document.getElementById('subdistrictSelect');
        const filterForm = document.getElementById('filterForm');
        
        // Store all subdistricts data from server (loaded on page load based on selected district)
        const allSubdistricts = {!! json_encode([
            'เมืองยะลา' => ['สะเตง', 'ยุโป', 'ลำใหม่', 'หน้าถ้ำ'],
            'เบตง' => ['เบตง', 'ยะรม', 'ตาเนาะปูเต๊ะ'],
            'บันนังสตา' => ['บันนังสตา', 'บาเจาะ'],
            'ธารโต' => ['ธารโต', 'บ้านแหร'],
            'ยะหา' => ['ยะหา', 'กาตอง'],
            'รามัน' => ['รามัน', 'กายูบอเกาะ'],
            'กาบัง' => ['กาบัง'],
            'กรงปินัง' => ['กรงปินัง', 'สะเอะ']
        ]) !!};
        
        // Update subdistrict options when district changes
        districtSelect.addEventListener('change', function() {
            const selectedDistrict = this.value;
            
            // Clear current subdistrict options
            subdistrictSelect.innerHTML = '<option value="">ทั้งหมด</option>';
            
            // Add subdistricts for selected district
            if (selectedDistrict && allSubdistricts[selectedDistrict]) {
                allSubdistricts[selectedDistrict].forEach(function(subdistrict) {
                    const option = document.createElement('option');
                    option.value = subdistrict;
                    option.textContent = subdistrict;
                    subdistrictSelect.appendChild(option);
                });
            }
        });
        
        // Handle export button clicks to include filter parameters
        const exportLinks = document.querySelectorAll('[href*="export"]');
        exportLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const baseUrl = this.getAttribute('href');
                const params = new URLSearchParams(new FormData(filterForm));
                window.location.href = baseUrl + (baseUrl.includes('?') ? '&' : '?') + params.toString();
            });
        });
    });
</script>
@endsection
