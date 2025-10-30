@extends('sandbox::layouts.master')

@section('title', 'จัดการข้อมูลโรงเรียน')

@section('stylesheet-content')
<link rel="stylesheet" href="{{ asset('assets/common/css/school-management.css') }}">
<meta name="auth-check" content="{{ auth()->check() ? 'true' : 'false' }}">
@endsection

@section('content')
<div class="school-management-container">
    <div class="container">
        <!-- Header -->
        <div class="page-header">
            <h1 class="page-title">จัดการข้อมูลโรงเรียน</h1>
            <div class="page-actions">
                @auth
                    <div class="dropdown" style="display: inline-block; margin-right: 10px;">
                        <button class="btn btn-success dropdown-toggle" type="button" id="exportDropdownSchools" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-download"></i> Export
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="exportDropdownSchools">
                            <li>
                                <a class="dropdown-item" href="#" id="exportSchoolsCSV">
                                    <i class="fas fa-file-csv"></i> Export CSV
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" id="exportSchoolsExcel">
                                    <i class="fas fa-file-excel"></i> Export Excel
                                </a>
                            </li>
                        </ul>
                    </div>
                @endauth
                <a href="{{ route('sandbox.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-chart-bar"></i> Dashboard
                </a>
                @auth
                <a href="{{ route('sandbox.schools.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> เพิ่มโรงเรียนใหม่
                </a>
                @endauth
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
        @endif

        <!-- Search and Filter -->
        <div class="search-filter-section">
            <div class="search-box">
                <input type="text" id="schoolSearch" placeholder="ค้นหาชื่อโรงเรียน..." class="search-input">
                <i class="fas fa-search"></i>
            </div>
            <div class="filter-options">
                <select id="departmentFilter" class="filter-select">
                    <option value="">ทุกสังกัด</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept }}">{{ $dept }}</option>
                    @endforeach
                </select>
                <button id="resetFilter" class="btn btn-outline">รีเซ็ต</button>
            </div>
        </div>

        <!-- Schools Grid -->
        <div class="schools-grid" id="schoolsGrid">
            <!-- Schools will be loaded via AJAX -->
        </div>
        
        <!-- Loading indicator -->
        <div id="loadingIndicator" style="text-align: center; padding: 20px; display: none;">
            <i class="fas fa-spinner fa-spin" style="font-size: 24px;"></i>
            <p>กำลังโหลดข้อมูล...</p>
        </div>
        
        <!-- Empty state -->
        <div id="emptyState" class="empty-state" style="display: none;">
            <div class="empty-icon">🏫</div>
            <h3>ไม่พบข้อมูลโรงเรียน</h3>
            <p>ลองเปลี่ยนเงื่อนไขการค้นหาหรือกรองข้อมูล</p>
        </div>
    </div>
</div>
@endsection

@section('script-content')
<script src="{{ asset('assets/common/js/school-management.js') }}"></script>
<script>
    // Export functionality for schools
    document.getElementById('exportSchoolsCSV')?.addEventListener('click', function(e) {
        e.preventDefault();
        const search = document.getElementById('schoolSearch').value;
        const department = document.getElementById('departmentFilter').value;
        let url = '{{ route("sandbox.schools.export.csv") }}?';
        if (search) url += `search=${encodeURIComponent(search)}&`;
        if (department) url += `department=${encodeURIComponent(department)}`;
        window.location.href = url;
    });

    document.getElementById('exportSchoolsExcel')?.addEventListener('click', function(e) {
        e.preventDefault();
        const search = document.getElementById('schoolSearch').value;
        const department = document.getElementById('departmentFilter').value;
        let url = '{{ route("sandbox.schools.export.excel") }}?';
        if (search) url += `search=${encodeURIComponent(search)}&`;
        if (department) url += `department=${encodeURIComponent(department)}`;
        window.location.href = url;
    });
</script>
@endsection