@extends('sandbox::layouts.master')

@section('title', 'Dashboard - สถิติโรงเรียน')

@section('stylesheet-content')
    <link rel="stylesheet" href="{{ asset('assets/common/css/school-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/common/css/innovation-shared.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/common/css/innovation-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/common/css/academic-dashboard-charts.css') }}">
    <style>
        /* Innovation Category Stats - Column Layout */
        .category-stats-container {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-top: 1rem;
        }

        .category-stat-row {
            background: white;
            border-radius: 8px;
            padding: 1rem 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
            border-left: 4px solid #3498db;
        }

        .category-stat-row:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
            border-left-color: #2980b9;
        }

        .category-stat-name {
            font-size: 1rem;
            font-weight: 600;
            color: #2c3e50;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .category-stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: #3498db;
            min-width: 60px;
            text-align: right;
        }

        .category-icon {
            font-size: 1.25rem;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ecf0f1;
            border-radius: 8px;
        }

        /* Innovations Grid */
        .innovations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-top: 1rem;
        }

        /* Loading Indicator */
        .loading-indicator {
            text-align: center;
            padding: 2rem;
            display: none;
        }

        .loading-indicator.active {
            display: block;
        }

        .loading-indicator .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* No More Data */
        .no-more-data {
            text-align: center;
            padding: 2rem;
            color: #7f8c8d;
            display: none;
        }

        .no-more-data.active {
            display: block;
        }

        /* Vision Videos Section Styles */
        .vision-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 4rem 0;
            margin-top: 3rem;
        }

        .section-header-vision {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 2.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
            position: relative;
        }

        .vision-carousel-wrapper {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-top: 1rem;
            width: 100%;
            overflow: hidden;
        }

        .vision-scroll-btn {
            background: rgba(255, 255, 255, 0.25);
            border: 2px solid rgba(255, 255, 255, 0.4);
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1.3rem;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .vision-scroll-btn:hover:not(:disabled) {
            background: rgba(255, 255, 255, 0.4);
            border-color: rgba(255, 255, 255, 0.6);
            transform: scale(1.1);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
        }

        .vision-scroll-btn:active:not(:disabled) {
            transform: scale(0.95);
        }

        .vision-scroll-btn:disabled {
            opacity: 0.3;
            cursor: not-allowed;
            transform: scale(1);
        }

        .section-header-vision .header-content h2 {
            font-size: 2rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .section-header-vision .header-content h2 i {
            font-size: 1.75rem;
        }

        .section-header-vision .header-content p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.1rem;
            margin: 0;
        }

        .view-all-btn {
            background: white;
            color: #667eea;
            padding: 0.75rem 1.5rem;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .view-all-btn:hover {
            background: #f8f9fa;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        }

        .vision-videos-grid {
            display: flex;
            gap: 2rem;
            overflow-x: auto;
            overflow-y: hidden;
            scroll-behavior: smooth;
            padding-bottom: 1rem;
            -webkit-overflow-scrolling: touch;
            flex: 1;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.3) rgba(255, 255, 255, 0.1);
            min-width: 0;
            /* Important for flex container */
            cursor: grab;
            user-select: none;
        }

        .vision-videos-grid:active {
            cursor: grabbing;
            scroll-behavior: auto;
        }

        .vision-videos-grid.is-dragging {
            cursor: grabbing;
            scroll-behavior: auto;
        }

        .vision-videos-grid.is-dragging .vision-video-card {
            pointer-events: none;
        }

        .vision-videos-grid::-webkit-scrollbar {
            height: 8px;
        }

        .vision-videos-grid::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        .vision-videos-grid::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
        }

        .vision-videos-grid::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        .vision-video-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            min-width: 350px;
            max-width: 350px;
            flex-shrink: 0;
        }

        .vision-video-card:hover,
        .vision-video-card:focus-visible {
            transform: translateY(-8px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.18);
        }

        .vision-video-card:focus-visible {
            outline: 3px solid rgba(102, 126, 234, 0.35);
            outline-offset: 4px;
        }

        .video-wrapper {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            background: #000;
        }

        .video-wrapper iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .video-info {
            padding: 1.5rem;
        }

        .video-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 0.75rem;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .video-school {
            font-size: 0.9rem;
            color: #667eea;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .video-description {
            font-size: 0.875rem;
            color: #6c757d;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .video-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.5rem;
            font-size: 0.8rem;
        }

        .video-type-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .badge-youtube {
            background: #ff0000;
            color: white;
        }

        .badge-facebook {
            background: #1877f2;
            color: white;
        }

        .badge-tiktok {
            background: #000000;
            color: white;
        }

        .badge-google_drive {
            background: #0f9d58;
            color: white;
        }

        .badge-other {
            background: #6c757d;
            color: white;
        }

        .video-date {
            color: #95a5a6;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .vision-modal-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            align-items: center;
        }

        .vision-modal-meta .badge {
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0.5rem 0.75rem;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #f1f3f5;
            color: #2c3e50;
        }

        .vision-modal-description {
            margin-top: 1rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
            color: #495057;
            line-height: 1.6;
        }

        /* Publications Dashboard Section - Side by Side Layout */
        .dashboard-with-publications {
            display: flex;
            gap: 2rem;
            align-items: flex-start;
        }

        .dashboard-main-content {
            flex: 1;
            min-width: 0;
        }

        .publications-sidebar {
            width: 400px;
            flex-shrink: 0;
            margin-right: 2rem;
        }

        .publications-sidebar-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.1);
            overflow: hidden;
            position: sticky;
            top: 20px;
        }

        /* Stats Cards - Center alignment */
        .stats-row {
            margin-bottom: 2rem;
        }

        .publications-sidebar-header {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            padding: 1.5rem;
            color: white;
        }

        .publications-sidebar-header h3 {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0 0 0.5rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .publications-sidebar-header p {
            margin: 0;
            font-size: 0.9rem;
            opacity: 0.95;
        }

        .publications-sidebar-body {
            max-height: calc(100vh - 250px);
            overflow-y: auto;
            padding: 1rem;
        }

        .publications-sidebar-body::-webkit-scrollbar {
            width: 6px;
        }

        .publications-sidebar-body::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .publications-sidebar-body::-webkit-scrollbar-thumb {
            background: #dc3545;
            border-radius: 3px;
        }

        .publication-row-item {
            display: flex;
            gap: 1rem;
            padding: 1rem;
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            margin-bottom: 0.75rem;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
        }

        .publication-row-item:hover {
            border-color: #dc3545;
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.1);
            transform: translateX(5px);
        }

        .publication-row-icon {
            width: 50px;
            height: 50px;
            flex-shrink: 0;
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .publication-row-content {
            flex: 1;
            min-width: 0;
        }

        .publication-row-title {
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
            line-height: 1.3;
            color: #2c3e50;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .publication-row-item:hover .publication-row-title {
            color: #dc3545;
        }

        .publication-row-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.75rem;
            color: #6c757d;
            gap: 0.5rem;
        }

        .publication-row-date {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            white-space: nowrap;
        }

        .publication-row-action {
            flex-shrink: 0;
        }

        .publication-row-action .btn {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
        }

        .publications-sidebar-footer {
            padding: 1rem;
            border-top: 1px solid #e9ecef;
            text-align: center;
        }

        .publications-sidebar-footer .btn {
            width: 100%;
        }

        .vision-modal-description {
            margin-top: 1rem;
            font-size: 0.95rem;
            line-height: 1.6;
            color: #2c3e50;
        }

        @media (max-width: 768px) {
            .vision-section {
                padding: 2rem 0;
                margin-top: 2rem;
            }

            .section-header-vision {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .vision-carousel-wrapper {
                gap: 1rem;
                overflow: visible;
            }

            .vision-videos-grid {
                gap: 1.5rem;
                margin: 0 -1rem;
                /* Extend to screen edges on mobile */
                padding: 0 1rem;
            }

            .vision-video-card {
                min-width: calc(100vw - 3rem);
                /* Full width minus padding */
                max-width: calc(100vw - 3rem);
            }

            .vision-scroll-btn {
                display: none;
            }

            .section-header-vision .header-content h2 {
                font-size: 1.5rem;
            }

            .innovations-grid {
                grid-template-columns: 1fr;
            }

            .category-stat-row {
                padding: 0.75rem 1rem;
            }

            .category-stat-name {
                font-size: 0.9rem;
            }

            .category-stat-number {
                font-size: 1.25rem;
            }

            /* Publications Sidebar - Stack on Mobile */
            .dashboard-with-publications {
                flex-direction: column-reverse;
            }

            .publications-sidebar {
                width: 100%;
                margin-right: 0;
            }

            .publications-sidebar-card {
                position: relative;
            }

            .publications-sidebar-body {
                max-height: 500px;
            }

            /* Academic Results Charts - Responsive */
            .container[style*="max-width: 1400px"] {
                padding: 0 15px !important;
            }

            .academic-filters-row {
                padding: 12px !important;
            }

            .academic-filters-row > div {
                flex-direction: column !important;
            }

            .academic-filters-row [style*="flex: 1"] {
                width: 100% !important;
            }

            .academic-filters-row [style*="align-self: flex-end"] {
                width: 100% !important;
            }

            .academic-filters-row button {
                width: 100% !important;
            }

            /* Stats cards - single column on mobile */
            [style*="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr))"] {
                grid-template-columns: 1fr !important;
            }

            /* Stats cards font size adjustment */
            [style*="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr))"] [style*="font-size: 32px"] {
                font-size: 28px !important;
            }

            [style*="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr))"] [style*="font-size: 24px"] {
                font-size: 20px !important;
            }

            /* Charts - single column on mobile */
            [style*="grid-template-columns: repeat(auto-fit, minmax(450px, 1fr))"] {
                grid-template-columns: 1fr !important;
                gap: 15px !important;
            }

            [style*="grid-template-columns: repeat(auto-fit, minmax(450px, 1fr))"] > div {
                padding: 15px !important;
            }

            /* Chart container responsive height */
            [style*="grid-template-columns: repeat(auto-fit, minmax(450px, 1fr))"] [style*="position: relative"] {
                height: 280px !important;
                min-height: 280px !important;
            }

            /* Canvas responsive */
            [style*="grid-template-columns: repeat(auto-fit, minmax(450px, 1fr))"] canvas {
                max-width: 100% !important;
                height: auto !important;
                max-height: 280px !important;
            }
        }

        @media (min-width: 769px) and (max-width: 1024px) {
            .vision-video-card {
                min-width: 320px;
                max-width: 320px;
            }

            .vision-scroll-btn {
                width: 45px;
                height: 45px;
                font-size: 1.1rem;
            }

            /* Academic Results Charts - Tablet */
            .container[style*="max-width: 1400px"] {
                padding: 0 20px !important;
            }

            /* Stats cards - 2 columns on tablet */
            [style*="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr))"] {
                grid-template-columns: repeat(2, 1fr) !important;
            }

            /* Charts - 1 column on tablet for better readability */
            [style*="grid-template-columns: repeat(auto-fit, minmax(450px, 1fr))"] {
                grid-template-columns: 1fr !important;
                gap: 20px !important;
            }

            /* Chart container height for tablet */
            [style*="grid-template-columns: repeat(auto-fit, minmax(450px, 1fr))"] [style*="position: relative"] {
                height: 320px !important;
                min-height: 320px !important;
            }

            /* Canvas responsive for tablet */
            [style*="grid-template-columns: repeat(auto-fit, minmax(450px, 1fr))"] canvas {
                max-width: 100% !important;
                height: auto !important;
                max-height: 320px !important;
            }
        }

        @media (min-width: 1025px) {
            /* Desktop - ensure proper margins */
            .container[style*="max-width: 1400px"] {
                padding: 0 30px !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="dashboard-container">
        <!-- Header Section -->
        <div class="dashboard-hero">
            <div class="col-12 text-center mb-4">
                <div class="logo-container">
                    <img src="{{ asset('assets/images/education-sandbox/edu-sandbox_logo.png') }}"
                        alt="Education Sandbox Logo" class="hero-logo">
                </div>
            </div>
            <div class="container">
                <h1 class="hero-title">ระบบจัดการข้อมูลโรงเรียนนวัตกรรม</h1>
                <p class="hero-subtitle">Dashboard แสดงสถิติและข้อมูลโรงเรียนในโครงการพื้นที่นวัตกรรม</p>
            </div>
        </div>

        <!-- Stats Cards (Full Width Center) -->
        <div class="container">
            <div class="stats-row">
                <div class="stat-card orange">
                    <div class="stat-icon">🏫</div>
                    <div class="stat-content">
                        <div class="stat-number">{{ number_format($stats['total_schools']) }}</div>
                        <div class="stat-label">โรงเรียนทั้งหมด</div>
                    </div>
                </div>

                <div class="stat-card green">
                    <div class="stat-icon">👥</div>
                    <div class="stat-content">
                        <div class="stat-number">{{ number_format($stats['total_students']) }}</div>
                        <div class="stat-label">นักเรียนทั้งหมด</div>
                    </div>
                </div>

                <div class="stat-card blue">
                    <div class="stat-icon">👨‍🏫</div>
                    <div class="stat-content">
                        <div class="stat-number">{{ number_format($stats['total_teachers']) }}</div>
                        <div class="stat-label">ครูทั้งหมด</div>
                    </div>
                </div>

                <div class="stat-card purple">
                    <div class="stat-icon">💡</div>
                    <div class="stat-content">
                        <div class="stat-number">{{ number_format($stats['total_innovations']) }}</div>
                        <div class="stat-label">นวัตกรรมทั้งหมด</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content with Publications Sidebar -->
        <div class="dashboard-with-publications">
            <!-- Main Dashboard Content -->
            <div class="dashboard-main-content">
                <div class="container">
                    <!-- Quick Actions -->
                    @auth
                        <div class="section-card">
                            <div class="section-header">
                                <h2>จัดการข้อมูล</h2>
                            </div>
                            <div class="action-buttons">
                                <a href="{{ route('sandbox.schools.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> เพิ่มโรงเรียนใหม่
                                </a>
                                <a href="{{ route('sandbox.schools.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-list"></i> จัดการข้อมูลโรงเรียน
                                </a>
                                <a href="{{ route('sandbox.academic-results.index') }}" class="btn btn-success">
                                    <i class="fas fa-chart-line"></i> ผลสัมฤทธิ์ทางการศึกษา
                                </a>
                                <a href="{{ route('sandbox.experiments.index') }}" class="btn btn-info">
                                    <i class="fas fa-flask"></i> What-If Analysis
                                </a>
                                <a href="{{ route('sandbox.publications.create') }}" class="btn btn-danger">
                                    <i class="fas fa-file-upload"></i> อัปโหลดเอกสารเผยแพร่
                                </a>
                                <a href="{{ route('sandbox.publications.index') }}" class="btn btn-outline-danger">
                                    <i class="fas fa-file-pdf"></i> จัดการเอกสาร
                                </a>
                            </div>
                        </div>
                    @endauth

                    <!-- Education Area Stats -->
                    <div class="section-card">
                        <div class="section-header">
                            <h2>สถิติตามเขตพื้นที่การศึกษา</h2>
                            <a href="{{ route('sandbox.schools.index') }}" class="view-all-link">ดูโรงเรียนทั้งหมด</a>
                        </div>
                        <div class="department-grid">
                            @foreach ($stats['by_education_area'] as $area => $data)
                                @php
                                    $displayLabel = $data['label'] ?? $area;
                                @endphp
                                <div class="department-card">
                                    <h3 class="department-name">{{ $displayLabel }}</h3>
                                    <div class="department-stats">
                                        <div class="dept-stat">
                                            <span class="label">โรงเรียน</span>
                                            <span class="value">{{ $data['count'] }}</span>
                                        </div>
                                        <div class="dept-stat">
                                            <span class="label">นักเรียน</span>
                                            <span class="value">{{ number_format($data['students']) }}</span>
                                        </div>
                                        <div class="dept-stat">
                                            <span class="label">ครู</span>
                                            <span class="value">{{ number_format($data['teachers']) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Publications Sidebar -->
            @if ($publications->count() > 0)
                <aside class="publications-sidebar">
                    <div class="publications-sidebar-card">
                        <div class="publications-sidebar-header">
                            <h3>
                                <i class="fas fa-file-pdf"></i>
                                เอกสารเผยแพร่
                            </h3>
                            <p>เอกสารล่าสุดสำหรับเผยแพร่</p>
                        </div>
                        <div class="publications-sidebar-body">
                            @foreach ($publications as $publication)
                                <a href="{{ route('sandbox.publications.show', $publication->id) }}"
                                    class="publication-row-item" title="{{ $publication->title }}">
                                    <div class="publication-row-icon">
                                        <i class="fas fa-file-pdf"></i>
                                    </div>
                                    <div class="publication-row-content">
                                        <div class="publication-row-title">
                                            {{ $publication->title }}
                                        </div>
                                        <div class="publication-row-meta">
                                            <span class="publication-row-date">
                                                <i class="far fa-calendar"></i>
                                                {{ $publication->created_at->format('d/m/Y') }}
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                        <div class="publications-sidebar-footer">
                            <a href="{{ route('sandbox.publications.index') }}" class="btn btn-danger">
                                <i class="fas fa-th-list"></i> ดูทั้งหมด
                            </a>
                        </div>
                    </div>
                </aside>
            @endif
        </div>

        <!-- Academic Results Charts Section -->
        <div class="container" style="max-width: 1400px; margin: 0 auto; padding: 0 20px;">
            <div class="section-card">
                <div class="section-header">
                    <h2><i class="fas fa-chart-bar"></i> ผลสัมฤทธิ์ทางการศึกษา</h2>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        @auth
                            <div class="dropdown">
                                <button class="btn btn-success dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-download"></i> Export ข้อมูล
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                                    <li>
                                        <a class="dropdown-item" href="#" id="exportCSV">
                                            <i class="fas fa-file-csv"></i> Export CSV
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" id="exportExcel">
                                            <i class="fas fa-file-excel"></i> Export Excel
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        @endauth
                        <a href="{{ route('sandbox.academic-results.index') }}" class="view-all-link">ดูรายละเอียดทั้งหมด</a>
                    </div>
                </div>

                <!-- Filters -->
                <div class="academic-filters-row"
                    style="margin-bottom: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                    <div style="display: flex; gap: 15px; flex-wrap: wrap; align-items: center;">
                        <div style="flex: 1; min-width: 150px;">
                            <label
                                style="display: block; margin-bottom: 5px; font-weight: 600; color: #4a5568; font-size: 14px;">
                                ปีการศึกษา (พ.ศ.)
                            </label>
                            <select id="yearFilter" class="form-control" style="padding: 8px 12px; border-radius: 6px;">
                                <option value="2025" selected>2568</option>
                            </select>
                        </div>
                        {{-- <div style="flex: 1; min-width: 150px;">
                            <label
                                style="display: block; margin-bottom: 5px; font-weight: 600; color: #4a5568; font-size: 14px;">
                                สังกัด
                            </label>
                            <select id="educationAreaFilter" class="form-control"
                                style="padding: 8px 12px; border-radius: 6px;">
                                <option value="all" selected>ทั้งหมด</option>
                                @foreach ($stats['by_education_area'] as $area => $data)
                                    @php
                                        $displayLabel = $data['label'] ?? $area;
                                    @endphp
                                    <option value="{{ $displayLabel }}">{{ $displayLabel }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div style="align-self: flex-end;">
                            <button id="refreshCharts" class="btn btn-primary" style="padding: 8px 20px;">
                                <i class="fas fa-sync-alt"></i> รีเฟรช
                            </button>
                        </div> --}}
                    </div>
                </div>

                <!-- Statistics Summary Cards -->
                <div
                    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 25px;">
                    <div
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <i class="fas fa-school" style="font-size: 32px; opacity: 0.9;"></i>
                            <div>
                                <div style="font-size: 24px; font-weight: 700;" id="totalSchools">0</div>
                                <div style="font-size: 13px; opacity: 0.9;">โรงเรียนที่ส่งข้อมูล</div>
                            </div>
                        </div>
                    </div>
                    <div
                        style="background: linear-gradient(135deg, #36a2eb 0%, #1e7bb5 100%); color: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <i class="fas fa-file-alt" style="font-size: 32px; opacity: 0.9;"></i>
                            <div>
                                <div style="font-size: 24px; font-weight: 700;" id="ntAvg">0</div>
                                <div style="font-size: 13px; opacity: 0.9;">คะแนนเฉลี่ย NT</div>
                            </div>
                        </div>
                    </div>
                    <div
                        style="background: linear-gradient(135deg, #ffce56 0%, #f39c12 100%); color: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <i class="fas fa-book-reader" style="font-size: 32px; opacity: 0.9;"></i>
                            <div>
                                <div style="font-size: 24px; font-weight: 700;" id="rtAvg">0</div>
                                <div style="font-size: 13px; opacity: 0.9;">คะแนนเฉลี่ย RT</div>
                            </div>
                        </div>
                    </div>
                    <div
                        style="background: linear-gradient(135deg, #4bc0c0 0%, #2d9a9a 100%); color: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <i class="fas fa-graduation-cap" style="font-size: 32px; opacity: 0.9;"></i>
                            <div>
                                <div style="font-size: 24px; font-weight: 700;" id="onetAvg">0</div>
                                <div style="font-size: 13px; opacity: 0.9;">คะแนนเฉลี่ย O-NET</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Grid -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(450px, 1fr)); gap: 20px;">
                    <!-- NT Section -->
                    <div
                        style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); grid-column: 1 / -1;">
                        <h4 style="margin: 0 0 20px 0; font-size: 18px; font-weight: 600; color: #2d3748; border-bottom: 3px solid #36a2eb; padding-bottom: 12px;">
                            <i class="fas fa-chart-line" style="color: #36a2eb;"></i> NT (ป.3) - National Test
                        </h4>
                        
                        <!-- NT Stats Cards -->
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px;">
                            <div style="background: linear-gradient(135deg, #36a2eb 0%, #1e7bb5 100%); color: white; padding: 20px; border-radius: 10px;">
                                <div style="font-size: 13px; opacity: 0.9; margin-bottom: 5px;">คะแนนเฉลี่ยวิชาคณิตศาสตร์</div>
                                <div style="font-size: 32px; font-weight: 700;" id="ntMathAvg">0</div>
                            </div>
                            <div style="background: linear-gradient(135deg, #63b3ed 0%, #3b9dd9 100%); color: white; padding: 20px; border-radius: 10px;">
                                <div style="font-size: 13px; opacity: 0.9; margin-bottom: 5px;">คะแนนเฉลี่ยวิชาภาษาไทย</div>
                                <div style="font-size: 32px; font-weight: 700;" id="ntThaiAvg">0</div>
                            </div>
                        </div>
                        
                        <!-- NT Charts -->
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px;">
                            <div>
                                <h5 style="margin: 0 0 10px 0; font-size: 14px; color: #4a5568;">เปรียบเทียบตามอำเภอ</h5>
                                <div style="position: relative; height: 300px;">
                                    <canvas id="ntAreaChart"></canvas>
                                </div>
                            </div>
                            <div>
                                <h5 style="margin: 0 0 10px 0; font-size: 14px; color: #4a5568;">เปรียบเทียบตามสังกัด</h5>
                                <div style="position: relative; height: 300px;">
                                    <canvas id="ntEducationAreaChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- RT Section -->
                    <div
                        style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); grid-column: 1 / -1;">
                        <h4 style="margin: 0 0 20px 0; font-size: 18px; font-weight: 600; color: #2d3748; border-bottom: 3px solid #ffce56; padding-bottom: 12px;">
                            <i class="fas fa-book-reader" style="color: #ffce56;"></i> RT (ป.1) - Reading Test
                        </h4>
                        
                        <!-- RT Stats Cards -->
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px;">
                            <div style="background: linear-gradient(135deg, #ffce56 0%, #f39c12 100%); color: white; padding: 20px; border-radius: 10px;">
                                <div style="font-size: 13px; opacity: 0.9; margin-bottom: 5px;">คะแนนเฉลี่ยการอ่านออกเสียง</div>
                                <div style="font-size: 32px; font-weight: 700;" id="rtReadingAvg">0</div>
                            </div>
                            <div style="background: linear-gradient(135deg, #ffdf80 0%, #f5b041 100%); color: white; padding: 20px; border-radius: 10px;">
                                <div style="font-size: 13px; opacity: 0.9; margin-bottom: 5px;">คะแนนเฉลี่ยการอ่านรู้เรื่อง</div>
                                <div style="font-size: 32px; font-weight: 700;" id="rtCompAvg">0</div>
                            </div>
                        </div>
                        
                        <!-- RT Charts -->
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px;">
                            <div>
                                <h5 style="margin: 0 0 10px 0; font-size: 14px; color: #4a5568;">เปรียบเทียบตามอำเภอ</h5>
                                <div style="position: relative; height: 300px;">
                                    <canvas id="rtAreaChart"></canvas>
                                </div>
                            </div>
                            <div>
                                <h5 style="margin: 0 0 10px 0; font-size: 14px; color: #4a5568;">เปรียบเทียบตามสังกัด</h5>
                                <div style="position: relative; height: 300px;">
                                    <canvas id="rtEducationAreaChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- O-NET Section -->
                    <div
                        style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); grid-column: 1 / -1;">
                        <h4 style="margin: 0 0 20px 0; font-size: 18px; font-weight: 600; color: #2d3748; border-bottom: 3px solid #4bc0c0; padding-bottom: 12px;">
                            <i class="fas fa-graduation-cap" style="color: #4bc0c0;"></i> O-NET (ป.6/ม.3) - Ordinary National Educational Test
                        </h4>
                        
                        <!-- O-NET Chart -->
                        <div style="margin-bottom: 20px;">
                            <h5 style="margin: 0 0 10px 0; font-size: 14px; color: #4a5568;">คะแนนเฉลี่ยทั้ง 4 วิชา แยกตามอำเภอ</h5>
                            <div style="position: relative; height: 350px;">
                                <canvas id="onetAreaChart"></canvas>
                            </div>
                        </div>
                        
                        <!-- O-NET Tables -->
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px;">
                            <div>
                                <h5 style="margin: 0 0 10px 0; font-size: 14px; color: #4a5568;">อำเภอที่ได้คะแนนเฉลี่ยแต่ละวิชา</h5>
                                <div id="onetAreaTable" style="max-height: 300px; overflow-y: auto;"></div>
                            </div>
                            <div>
                                <h5 style="margin: 0 0 10px 0; font-size: 14px; color: #4a5568;">เขตพื้นที่การศึกษาที่ได้คะแนนเฉลี่ยแต่ละวิชา</h5>
                                <div id="onetEducationAreaTable" style="max-height: 300px; overflow-y: auto;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Schools -->
        {{-- <div class="section-card">
                <div class="section-header">
                    <h2>โรงเรียนล่าสุด</h2>
                    <a href="{{ route('sandbox.schools.index') }}" class="view-all-link">ดูทั้งหมด</a>
                </div>
                <div class="schools-preview">
                    @foreach ($schools->take(6) as $school)
                        <div class="school-preview-card">
                            <div class="school-header">
                                <h4>{{ $school->name }}</h4>
                                <span class="department-badge">{{ $school->education_area ?? 'ไม่ระบุ' }}</span>
                            </div>
                            <div class="school-stats">
                                <div class="quick-stat">
                                    <span class="icon">👥</span>
                                    <span>{{ $school->total_students }} คน</span>
                                </div>
                                <div class="quick-stat">
                                    <span class="icon">👨‍🏫</span>
                                    <span>{{ $school->total_teachers }} คน</span>
                                </div>
                                <div class="quick-stat">
                                    <span class="icon">💡</span>
                                    <span>{{ $school->active_innovations_count }} นวัตกรรม</span>
                                </div>
                            </div>
                            <div class="school-actions">
                                <a href="{{ route('sandbox.schools.show', $school->id) }}" class="btn-sm btn-view">ดู</a>
                                @auth
                                    <a href="{{ route('sandbox.schools.edit', $school->id) }}"
                                        class="btn-sm btn-edit">แก้ไข</a>
                                @endauth
                            </div>
                        </div>
                    @endforeach
                </div>
            </div> --}}
    </div>

    <!-- Vision Videos Section -->
    @if ($visionVideos->count() > 0)
        <section class="vision-section" id="vision-section">
            <div class="container">
                <div class="section-header-vision">
                    <div class="header-content">
                        <h2>
                            <i class="fas fa-video"></i>
                            วิสัยทัศน์การศึกษา
                        </h2>
                        <p>แบ่งปันวิสัยทัศน์และแนวทางการพัฒนาการศึกษาจากโรงเรียนต่าง ๆ</p>
                    </div>
                    <a href="{{ route('sandbox.vision-videos.all') }}" class="view-all-btn">
                        <span>ดูทั้งหมด</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <div class="vision-carousel-wrapper">
                    <button type="button" class="vision-scroll-btn" id="visionScrollLeft" aria-label="เลื่อนซ้าย">
                        <i class="fas fa-chevron-left"></i>
                    </button>

                    <div class="vision-videos-grid">
                        @foreach ($visionVideos as $video)
                            @php
                                $videoDescription = $video->description ? strip_tags($video->description) : '';
                                $videoDateDisplay = $video->created_at->format('d/m/Y H:i น.');
                            @endphp
                            <div class="vision-video-card" data-video-type="{{ $video->video_type }}"
                                data-video-title="{{ $video->title }}" data-video-school="{{ $video->school->name }}"
                                data-video-description="{{ $videoDescription }}"
                                data-video-date="{{ $videoDateDisplay }}" data-video-embed="{{ $video->embed_url }}"
                                tabindex="0" role="button" aria-label="เปิดวิดีโอ {{ $video->title }}">
                                <div class="video-wrapper">
                                    <iframe src="{{ $video->embed_url }}" frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen loading="lazy">
                                    </iframe>
                                </div>
                                <div class="video-info">
                                    <h3 class="video-title">{{ $video->title }}</h3>
                                    <p class="video-school">
                                        <i class="fas fa-school"></i>
                                        {{ $video->school->name }}
                                    </p>
                                    @if ($video->description)
                                        <p class="video-description">{{ Str::limit($video->description, 100) }}</p>
                                    @endif
                                    <div class="video-meta">
                                        <span class="video-type-badge badge-{{ $video->video_type }}">
                                            @if ($video->video_type == 'youtube')
                                                <i class="fab fa-youtube"></i> YouTube
                                            @elseif($video->video_type == 'facebook')
                                                <i class="fab fa-facebook"></i> Facebook
                                            @elseif($video->video_type == 'tiktok')
                                                <i class="fab fa-tiktok"></i> TikTok
                                            @elseif($video->video_type == 'google_drive')
                                                <i class="fab fa-google-drive"></i> Google Drive
                                            @else
                                                <i class="fas fa-video"></i> Video
                                            @endif
                                        </span>
                                        <span class="video-date">
                                            <i class="far fa-clock"></i>
                                            {{ $video->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" class="vision-scroll-btn" id="visionScrollRight" aria-label="เลื่อนขวา">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>

            <div class="modal fade vision-video-modal" id="visionVideoModal" tabindex="-1"
                aria-labelledby="visionVideoModalTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="visionVideoModalTitle">ดูวิสัยทัศน์การศึกษา</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="ratio ratio-16x9">
                                <iframe id="visionVideoModalIframe" src="" frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>
                            </div>
                            <div class="vision-modal-meta mt-3">
                                <span class="badge" id="visionVideoModalSchool">
                                    <i class="fas fa-school"></i>
                                    <span class="label">โรงเรียน</span>
                                </span>
                                <span class="video-type-badge badge-other" id="visionVideoModalType"></span>
                                <span class="badge" id="visionVideoModalDate">
                                    <i class="far fa-clock"></i>
                                    <span class="label">-</span>
                                </span>
                            </div>
                            <p class="vision-modal-description" id="visionVideoModalDescription" style="display: none;">
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Innovation Experience Zone (Full Width) -->
    <section class="innovation-zone" id="innovation-zone">
        <div class="container">
            <div class="zone-header">
                <div class="zone-title">
                    <h2>คลังนวัตกรรมเพื่อการเรียนรู้</h2>
                    <p>
                        สำรวจนวัตกรรมล่าสุดจากโรงเรียนในพื้นที่นวัตกรรมทางการศึกษา ค้นหาแนวทางใหม่ ๆ
                        ผ่านการกรองตามคีย์เวิร์ดและหมวดหมู่ที่หลากหลาย
                    </p>
                </div>
                <div class="zone-summary">
                    <div class="summary-card">
                        <span>นวัตกรรมทั้งหมด</span>
                        <strong>{{ number_format($stats['total_innovations']) }}</strong>
                    </div>
                    {{-- <div class="summary-card">
                        <span>พร้อมใช้งาน</span>
                        <strong>{{ number_format($stats['active_innovations']) }}</strong>
                    </div>
                    <div class="summary-card">
                        <span>อยู่ระหว่างพัฒนา</span>
                        <strong>{{ number_format($stats['inactive_innovations']) }}</strong>
                    </div> --}}
                </div>
            </div>

            <div class="filters-panel">
                <form id="innovationFilters" autocomplete="off">
                    <div class="filter-search">
                        <i class="fas fa-search"></i>
                        <input type="search" id="innovationSearch" placeholder="ค้นหาชื่อโรงเรียนหรือนวัตกรรม...">
                    </div>
                    <div class="filter-meta">
                        <span class="results-count"><strong
                                id="innovationResultsCount">{{ number_format($stats['total_innovations']) }}</strong>
                            รายการ</span>
                    </div>
                </form>
                <div class="categories-scroll" id="innovationCategories">
                    <button type="button" class="category-chip is-active" data-category="">
                        ทั้งหมด
                        <span class="count">{{ number_format($stats['total_innovations']) }}</span>
                    </button>
                    @foreach ($innovationsByCategory as $categoryStat)
                        <button type="button" class="category-chip" data-category="{{ $categoryStat->category }}">
                            {{ $categoryStat->category }}
                            <span class="count">{{ number_format($categoryStat->count) }}</span>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="container">
            @if ($innovations->isEmpty())
                <div class="empty-results" id="innovationEmpty">
                    <h3>ยังไม่มีนวัตกรรมในระบบ</h3>
                    <p>เมื่อมีการบันทึกนวัตกรรม
                        ระบบจะแสดงข้อมูลในส่วนนี้พร้อมการค้นหาและตัวกรองให้โดยอัตโนมัติ</p>
                </div>
            @else
                <div class="innovation-grid" id="innovationGrid">
                    @foreach ($innovations as $innovation)
                        @php
                            $firstImage = $innovation->first_image_path;
                            $fileName = $firstImage ? basename($firstImage) : null;
                            $extension = $fileName ? strtolower(pathinfo($fileName, PATHINFO_EXTENSION)) : null;
                            $isPdf = $extension === 'pdf';
                            $imageUrl = $fileName
                                ? route('sandbox.innovation.image', [$innovation->school_id, $fileName])
                                : null;
                            $categoryName = $innovation->category ?: 'ไม่ระบุหมวดหมู่';
                            $schoolName = optional($innovation->school)->name ?: 'ไม่พบข้อมูลโรงเรียน';
                            $detailUrl =
                                route('sandbox.schools.innovations', $innovation->school_id) .
                                '?highlight=' .
                                $innovation->id;
                            $assetPlaceholder = asset('assets/images/education-sandbox/innovation-placeholder.svg');
                        @endphp
                        <a href="{{ $detailUrl }}" class="innovation-card"
                            data-title="{{ Str::lower(strip_tags($innovation->title)) }}"
                            data-category="{{ Str::lower($categoryName) }}"
                            data-school="{{ Str::lower(strip_tags($schoolName)) }}"
                            data-description="{{ Str::lower(strip_tags($innovation->description ?? '')) }}"
                            data-active="{{ $innovation->is_active ? '1' : '0' }}">
                            <div class="card-figure">
                                @if ($imageUrl && !$isPdf)
                                    <img src="{{ $imageUrl }}" alt="{{ $innovation->title }}" loading="lazy">
                                @else
                                    <img src="{{ $assetPlaceholder }}" alt="Innovation placeholder" loading="lazy">
                                @endif
                                <span class="figure-badge">{{ $categoryName }}</span>
                                {{-- <span class="status-pill {{ $innovation->is_active ? '' : 'is-inactive' }}">
                                {{ $innovation->is_active ? 'พร้อมใช้งาน' : 'อยู่ระหว่างพัฒนา' }}
                            </span> --}}
                                <div class="figure-overlay"></div>
                            </div>
                            <div class="card-body">
                                <div class="card-heading">
                                    <span class="school-name">{{ $schoolName }}</span>
                                    <h3 class="innovation-title">{{ $innovation->title }}</h3>
                                </div>
                                @if (!empty($innovation->description))
                                    <p class="innovation-description">
                                        {{ Str::limit(strip_tags($innovation->description), 120) }}</p>
                                @endif
                                <div class="card-footer">
                                    @if ($innovation->year)
                                        <span class="meta-pill"><i class="far fa-calendar"></i> พ.ศ.
                                            {{ $innovation->year }}</span>
                                    @endif
                                    <span class="meta-pill"><i class="far fa-clock"></i>
                                        {{ $innovation->created_at->format('d/m/Y') }}</span>
                                    <span class="cta">ดูรายละเอียด <i class="fas fa-arrow-right"></i></span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="empty-results" id="innovationEmpty" style="display: none;">
                    <h3>ไม่พบนวัตกรรมที่ตรงกับการค้นหา</h3>
                    <p>ลองปรับคำค้นหาหรือเลือกหมวดหมู่อื่น เพื่อค้นพบแนวทางนวัตกรรมใหม่ ๆ</p>
                </div>
            @endif
        </div>
    </section>
@endsection

@section('script-content')
    <script src="{{ asset('assets/common/js/school-dashboard.js') }}"></script>
    <script src="{{ asset('assets/common/js/innovation-dashboard.js') }}"></script>

    <!-- Chart.js for Academic Results -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.1/dist/chart.umd.min.js"></script>
    <script src="{{ asset('assets/common/js/academic-dashboard-charts.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const videoCards = document.querySelectorAll('.vision-video-card');
            const modalEl = document.getElementById('visionVideoModal');

            if (!modalEl || videoCards.length === 0) {
                return;
            }

            const bootstrapModal = new bootstrap.Modal(modalEl);
            const iframeEl = modalEl.querySelector('#visionVideoModalIframe');
            const titleEl = modalEl.querySelector('#visionVideoModalTitle');
            const schoolBadge = modalEl.querySelector('#visionVideoModalSchool');
            const typeBadge = modalEl.querySelector('#visionVideoModalType');
            const dateBadge = modalEl.querySelector('#visionVideoModalDate');
            const descriptionEl = modalEl.querySelector('#visionVideoModalDescription');

            const typeMeta = {
                youtube: {
                    label: 'YouTube',
                    className: 'badge-youtube',
                    iconClass: 'fab fa-youtube'
                },
                facebook: {
                    label: 'Facebook',
                    className: 'badge-facebook',
                    iconClass: 'fab fa-facebook'
                },
                tiktok: {
                    label: 'TikTok',
                    className: 'badge-tiktok',
                    iconClass: 'fab fa-tiktok'
                    },
                    google_drive: {
                        label: 'Google Drive',
                        className: 'badge-google_drive',
                        iconClass: 'fab fa-google-drive'
                },
                other: {
                    label: 'Video',
                    className: 'badge-other',
                    iconClass: 'fas fa-video'
                }
            };

            videoCards.forEach((card) => {
                const openModal = () => {
                    const embedUrl = card.dataset.videoEmbed || '';
                    const title = card.dataset.videoTitle || '';
                    const school = card.dataset.videoSchool || '';
                    const description = card.dataset.videoDescription || '';
                    const date = card.dataset.videoDate || '';
                    const type = card.dataset.videoType || 'other';
                    const meta = typeMeta[type] || typeMeta.other;

                    if (titleEl) {
                        titleEl.textContent = title;
                    }

                    if (schoolBadge) {
                        const labelEl = schoolBadge.querySelector('.label');
                        if (labelEl) {
                            labelEl.textContent = school || '-';
                        }
                    }

                    if (typeBadge) {
                        typeBadge.className = 'video-type-badge';
                        typeBadge.classList.add(meta.className);
                        typeBadge.innerHTML = '';

                        const iconElement = document.createElement('i');
                        iconElement.className = meta.iconClass;
                        typeBadge.appendChild(iconElement);
                        typeBadge.appendChild(document.createTextNode(` ${meta.label}`));
                    }

                    if (dateBadge) {
                        const labelEl = dateBadge.querySelector('.label');
                        if (labelEl) {
                            labelEl.textContent = date || '-';
                        }
                    }

                    if (descriptionEl) {
                        const text = description.trim();
                        descriptionEl.textContent = text;
                        descriptionEl.style.display = text ? 'block' : 'none';
                    }

                    if (iframeEl) {
                        iframeEl.src = embedUrl;
                    }

                    bootstrapModal.show();
                };

                card.addEventListener('click', openModal);
                card.addEventListener('keydown', (event) => {
                    if (event.key === 'Enter' || event.key === ' ') {
                        event.preventDefault();
                        openModal();
                    }
                });
            });

            modalEl.addEventListener('hidden.bs.modal', function() {
                if (iframeEl) {
                    iframeEl.src = '';
                }
            });
        });

        // Vision Videos Carousel Scroll Controls
        const scrollContainer = document.querySelector('.vision-videos-grid');
        const scrollLeftBtn = document.getElementById('visionScrollLeft');
        const scrollRightBtn = document.getElementById('visionScrollRight');

        if (scrollContainer && scrollLeftBtn && scrollRightBtn) {
            const scrollAmount = 380; // card width (350px) + gap (30px)

            scrollLeftBtn.addEventListener('click', () => {
                scrollContainer.scrollBy({
                    left: -scrollAmount,
                    behavior: 'smooth'
                });
            });

            scrollRightBtn.addEventListener('click', () => {
                scrollContainer.scrollBy({
                    left: scrollAmount,
                    behavior: 'smooth'
                });
            });

            // Update button states based on scroll position
            const updateButtonStates = () => {
                const isAtStart = scrollContainer.scrollLeft <= 0;
                const isAtEnd = scrollContainer.scrollLeft + scrollContainer.clientWidth >= scrollContainer
                    .scrollWidth - 5;

                scrollLeftBtn.style.opacity = isAtStart ? '0.3' : '1';
                scrollLeftBtn.style.cursor = isAtStart ? 'not-allowed' : 'pointer';
                scrollRightBtn.style.opacity = isAtEnd ? '0.3' : '1';
                scrollRightBtn.style.cursor = isAtEnd ? 'not-allowed' : 'pointer';
            };

            scrollContainer.addEventListener('scroll', updateButtonStates);
            updateButtonStates(); // Initial state

            // Drag to Scroll functionality
            let isDown = false;
            let startX;
            let scrollLeft;
            let hasMoved = false;
            const dragThreshold = 5;

            scrollContainer.addEventListener('mousedown', (e) => {
                isDown = true;
                hasMoved = false;
                scrollContainer.classList.add('active');
                startX = e.pageX - scrollContainer.offsetLeft;
                scrollLeft = scrollContainer.scrollLeft;
            });

            scrollContainer.addEventListener('mouseleave', () => {
                isDown = false;
                hasMoved = false;
                scrollContainer.classList.remove('is-dragging');
                scrollContainer.classList.remove('active');
            });

            scrollContainer.addEventListener('mouseup', () => {
                isDown = false;
                scrollContainer.classList.remove('active');

                setTimeout(() => {
                    hasMoved = false;
                    scrollContainer.classList.remove('is-dragging');
                }, 100);
            });

            scrollContainer.addEventListener('mousemove', (e) => {
                if (!isDown) return;

                const x = e.pageX - scrollContainer.offsetLeft;
                const distance = Math.abs(x - startX);

                if (distance > dragThreshold) {
                    e.preventDefault();
                    hasMoved = true;
                    scrollContainer.classList.add('is-dragging');
                    const walk = (x - startX) * 2;
                    scrollContainer.scrollLeft = scrollLeft - walk;
                }
            });

            // Prevent video card click when dragging
            const videoCards = scrollContainer.querySelectorAll('.vision-video-card');
            videoCards.forEach(card => {
                card.addEventListener('click', (e) => {
                    if (hasMoved) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                }, true); // Use capture phase
            });
        }

        // Export functionality
        document.getElementById('exportCSV')?.addEventListener('click', function(e) {
            e.preventDefault();
            const year = document.getElementById('yearFilter').value;
            window.location.href = `/sandbox/academic-results/export/csv?year=${year}`;
        });

        document.getElementById('exportExcel')?.addEventListener('click', function(e) {
            e.preventDefault();
            const year = document.getElementById('yearFilter').value;
            window.location.href = `/sandbox/academic-results/export/excel?year=${year}`;
        });
    </script>
@endsection
