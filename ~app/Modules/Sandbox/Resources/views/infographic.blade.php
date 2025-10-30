@extends('sandbox::layouts.master')

@section('title', 'โรงเรียนพื้นที่นวัตกรรม - สถิติ')

@section('stylesheet-content')
    <link rel="stylesheet" href="{{ asset('assets/common/css/school-infographic.css') }}">
@endsection

@section('content')
    <div class="infographic-container">
        <!-- Hero Section -->
        <div class="infographic-hero">
            <div class="container">
                <div class="hero-content">
                    <h1 class="hero-title">โรงเรียนพื้นที่นวัตกรรม {{ date('Y') + 543 }}</h1>
                    <div class="hero-stats">
                        <div class="hero-stat">
                            <span>{{ $stats['total_schools'] }} โรงเรียน</span>
                        </div>
                        <div class="hero-stat">
                            <span>{{ number_format($stats['total_innovations']) }} นวัตกรรม</span>
                        </div>
                        <div class="hero-stat">
                            <span>Infographic รายโรงเรียน</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <!-- Overall Statistics -->
            <div class="stats-overview">
                <div class="stat-card primary">
                    <div class="stat-number">{{ number_format($stats['total_schools']) }}</div>
                    <div class="stat-label">โรงเรียนทั้งหมด</div>
                </div>
                <div class="stat-card success">
                    <div class="stat-number">{{ number_format($stats['total_students']) }}</div>
                    <div class="stat-label">นักเรียนทั้งหมด</div>
                </div>
                <div class="stat-card info">
                    <div class="stat-number">{{ number_format($stats['total_teachers']) }}</div>
                    <div class="stat-label">ครูและบุคลากร</div>
                </div>
                <div class="stat-card warning">
                    <div class="stat-number">{{ number_format($stats['total_innovations']) }}</div>
                    <div class="stat-label">นวัตกรรมทั้งหมด</div>
                </div>
            </div>

            <!-- Search Section -->
            <div class="search-section">
                <div class="search-container">
                    <input type="text" class="search-input" id="schoolSearch" placeholder="ค้นหาโรงเรียน...">
                    <i class="fas fa-search search-icon"></i>
                </div>
            </div>

            <!-- Department Sections -->
            @foreach ($stats['by_department'] as $department => $data)
                <div class="department-section" data-department="{{ $department }}">
                    <div class="department-header {{ $loop->index % 2 == 1 ? 'green' : '' }}">
                        <h2 class="department-title">{{ $department }}</h2>
                        <div class="department-stats">
                            <span class="dept-count">{{ $data['count'] }}</span> โรงเรียน |
                            <span class="student-count">{{ number_format($data['students']) }}</span> นักเรียน |
                            <span class="teacher-count">{{ number_format($data['teachers']) }}</span> ครู
                        </div>
                    </div>

                    <div class="schools-grid active">
                        @foreach ($schools->where('department', $department) as $school)
                            <div class="school-item {{ $loop->parent->index % 2 == 1 ? 'green' : '' }}"
                                data-name="{{ strtolower($school->name) }}"
                                data-innovations="{{ $school->active_innovations_count }}"
                                onclick="showSchoolModal({{ $school->id }})">
                                <div class="school-content">
                                    <h5 class="school-name">{{ $school->name }}</h5>
                                    <div class="school-meta">
                                        <div class="school-stat">
                                            <span class="stat-icon">👥</span>
                                            <span class="stat-text">{{ number_format($school->total_students) }} คน</span>
                                        </div>
                                        <div class="school-stat">
                                            <span class="stat-icon">👨‍🏫</span>
                                            <span class="stat-text">{{ number_format($school->total_teachers) }} คน</span>
                                        </div>
                                        <div class="school-stat">
                                            <span class="stat-icon">💡</span>
                                            <span class="stat-text">{{ $school->active_innovations_count }} นวัตกรรม</span>
                                        </div>
                                    </div>
                                    @if ($school->active_innovations_count > 0)
                                        <div class="innovation-preview">
                                            <small>คลิกเพื่อดูนวัตกรรม</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <!-- Management Links -->
            <div class="management-section">
                <div class="management-card">
                    <h3>จัดการข้อมูล</h3>
                    <p>สำหรับผู้ดูแลระบบ - จัดการข้อมูลโรงเรียนและนวัตกรรม</p>
                    <div class="management-actions">
                        <a href="{{ route('sandbox.dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                        <a href="{{ route('sandbox.schools.index') }}" class="btn btn-secondary">
                            <i class="fas fa-school"></i> จัดการโรงเรียน
                        </a>
                        <a href="{{ route('sandbox.schools.create') }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> เพิ่มโรงเรียน
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- School Modal -->
    <div id="schoolModal" class="school-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalSchoolName" class="modal-title"></h3>
                <button class="close-modal" onclick="closeSchoolModal()">&times;</button>
            </div>
            <div class="modal-body" id="modalBody">
                <div class="loading">กำลังโหลด...</div>
            </div>
        </div>
    </div>

    <!-- Fullscreen Image Overlay -->
    <div class="fullscreen-overlay" onclick="closeFullscreen()">
        <span class="fullscreen-close">&times;</span>
        <img class="fullscreen-image" src="" alt="" />
    </div>
@endsection

@section('script-content')
    <script src="{{ asset('assets/common/js/school-infographic.js') }}"></script>
    <script>
        // School data for modal display
        const schoolsData = @json($schools->map(function ($school) {
                return [
                    'id' => $school->id,
                    'name' => $school->name,
                    'department' => $school->department,
                    'total_students' => $school->total_students,
                    'male_students' => $school->male_students,
                    'female_students' => $school->female_students,
                    'total_teachers' => $school->total_teachers,
                    'male_teachers' => $school->male_teachers,
                    'female_teachers' => $school->female_teachers,
                    'active_innovations_count' => $school->active_innovations_count,
                    'innovations' => $school->innovations->map(function ($innovation) {
                        return [
                            'title' => $innovation->title,
                            'description' => $innovation->description,
                            'image_path' => $innovation->image_path,
                            'category' => $innovation->category,
                            'year' => $innovation->year,
                            'is_active' => $innovation->is_active,
                        ];
                    }),
                ];
            }));
    </script>
@endsection
