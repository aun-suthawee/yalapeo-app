@extends('sandbox::layouts.master')

@section('title', 'รายละเอียด ' . $school->name)

@section('stylesheet-content')
    <link rel="stylesheet" href="{{ asset('assets/common/css/school-detail.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/common/css/innovation-shared.css') }}">
    <style>
        /* Innovation Lightbox */
        .innovation-lightbox .modal-dialog {
            max-width: min(1100px, 95vw);
        }

        .innovation-lightbox .modal-content {
            background: #0b0d17;
            border: none;
            border-radius: 18px;
            padding: 1.5rem 1.5rem 1.75rem;
            position: relative;
            color: #f8f9ff;
        }

        .innovation-lightbox .btn-close {
            position: absolute;
            top: 1rem;
            left: 1rem;
            filter: invert(1);
            opacity: 0.7;
        }

        .innovation-lightbox .btn-close:hover,
        .innovation-lightbox .btn-close:focus-visible {
            opacity: 1;
        }

        .innovation-lightbox-share {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(255, 255, 255, 0.18);
            border: none;
            color: #ffffff;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            transition: background 0.3s ease;
        }

        .innovation-lightbox-share:hover,
        .innovation-lightbox-share:focus-visible {
            background: rgba(255, 255, 255, 0.3);
            outline: none;
        }

        .innovation-lightbox-main {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1.5rem;
            margin-top: 2.5rem;
            position: relative;
        }

        .innovation-lightbox-image-wrapper {
            position: relative;
            overflow: hidden;
            border-radius: 16px;
            background: #141a2b;
            display: flex;
            align-items: center;
            justify-content: center;
            max-height: 70vh;
            width: 100%;
            cursor: zoom-in;
            touch-action: none;
            user-select: none;
        }

        .innovation-lightbox-image-wrapper img {
            max-width: 100%;
            max-height: 70vh;
            width: auto;
            height: auto;
            user-select: none;
            -webkit-user-drag: none;
            will-change: transform;
            transition: transform 0.25s ease;
        }

        .innovation-lightbox-image-wrapper.is-zoomed {
            cursor: grab;
        }

        .innovation-lightbox-image-wrapper.is-zoomed img {
            cursor: grab;
        }

        .innovation-lightbox-image-wrapper.is-panning,
        .innovation-lightbox-image-wrapper.is-panning img {
            cursor: grabbing;
        }

        .innovation-lightbox-image-wrapper[data-zoom-level="1"] {
            cursor: zoom-in;
        }

        .innovation-lightbox-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.18);
            border: none;
            color: #ffffff;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .innovation-lightbox-nav:hover,
        .innovation-lightbox-nav:focus-visible {
            background: rgba(255, 255, 255, 0.32);
            outline: none;
        }

        .innovation-lightbox-prev {
            left: -0.5rem;
        }

        .innovation-lightbox-next {
            right: -0.5rem;
        }

        .innovation-lightbox-nav.is-hidden {
            display: none;
        }

        .innovation-lightbox-feedback {
            min-height: 1.25rem;
            margin-top: 1rem;
            color: #9fb3ff;
            font-size: 0.875rem;
        }

        .innovation-lightbox-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-top: 1.25rem;
            flex-wrap: wrap;
        }

        .innovation-lightbox-meta-text h3 {
            font-size: 1.35rem;
            margin-bottom: 0.25rem;
            color: #ffffff;
        }

        .innovation-lightbox-meta-text p {
            margin: 0;
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.7);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .innovation-lightbox-meta-text .bullet {
            opacity: 0.5;
        }

        @media (max-width: 768px) {
            .innovation-lightbox .modal-content {
                padding: 1.25rem 1.25rem 1.5rem;
            }

            .innovation-lightbox-main {
                gap: 0.75rem;
                margin-top: 2rem;
            }

            .innovation-lightbox-prev {
                left: -0.25rem;
            }

            .innovation-lightbox-next {
                right: -0.25rem;
            }

            .innovation-lightbox-nav {
                width: 42px;
                height: 42px;
            }
        }

        /* Vision Videos Styles */
        .vision-videos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        .vision-video-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .vision-video-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
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
            padding: 1.25rem;
        }

        .video-title {
            font-size: 1rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.75rem;
            line-height: 1.4;
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
            margin-bottom: 1rem;
        }

        .video-type-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.625rem;
            border-radius: 15px;
            font-weight: 600;
            font-size: 0.7rem;
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
            font-size: 0.75rem;
        }

        .video-actions {
            display: flex;
            gap: 0.5rem;
            padding-top: 0.75rem;
            border-top: 1px solid #e9ecef;
        }

        .empty-videos {
            text-align: center;
            padding: 3rem 2rem;
            background: #f8f9fa;
            border-radius: 12px;
            margin-top: 1.5rem;
        }

        .empty-videos .empty-icon {
            margin-bottom: 1rem;
        }

        .empty-videos h3 {
            font-size: 1.25rem;
            color: #495057;
            margin-bottom: 0.75rem;
        }

        .empty-videos p {
            color: #6c757d;
            margin-bottom: 1.5rem;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .section-header .section-title {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-actions {
            display: flex;
            gap: 0.5rem;
        }

        /* Gallery Section Styles */
        .galleries-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        .gallery-item-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .gallery-item-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .gallery-preview {
            position: relative;
            padding-bottom: 60%;
            background: #f8f9fa;
            overflow: hidden;
        }

        .gallery-preview-iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        .gallery-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .gallery-item-card:hover .gallery-overlay {
            opacity: 1;
        }

        .gallery-item-info {
            padding: 1.25rem;
        }

        .gallery-item-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .gallery-item-title a {
            color: #2c3e50;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .gallery-item-title a:hover {
            color: #3498db;
        }

        .gallery-item-description {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 0.75rem;
            line-height: 1.5;
        }

        .gallery-item-meta {
            display: flex;
            gap: 1rem;
            margin-bottom: 0.75rem;
            font-size: 0.85rem;
            color: #95a5a6;
        }

        .meta-date {
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .gallery-item-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            padding-top: 0.75rem;
            border-top: 1px solid #e9ecef;
        }

        @media (max-width: 768px) {
            .vision-videos-grid,
            .galleries-grid {
                grid-template-columns: 1fr;
            }

            .section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .section-actions {
                width: 100%;
            }

            .section-actions .btn {
                flex: 1;
            }

            .gallery-item-actions {
                flex-direction: column;
            }

            .gallery-item-actions .btn {
                width: 100%;
            }
        }
    </style>
@endsection

@section('content')
    <div class="school-detail-container">
        <div class="container">
            <!-- Back Button -->
            <div class="mb-3">
                <a href="{{ route('sandbox.schools.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> กลับสู่รายการโรงเรียน
                </a>
            </div>

            <!-- Header -->
            <div class="page-header">
                <div class="header-content">
                    <h1 class="school-name">{{ $school->name }}</h1>
                    <span class="department-badge">{{ $school->department }}</span>
                </div>
            </div>

            <!-- Action Section -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-tasks"></i> จัดการข้อมูลโรงเรียน
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- School Management -->
                        @auth
                        <div class="col-md-4">
                            <h6 class="text-muted mb-2">
                                <i class="fas fa-school"></i> จัดการโรงเรียน
                            </h6>
                            <div class="d-grid">
                                <a href="{{ route('sandbox.schools.edit', $school->id) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> แก้ไขข้อมูลโรงเรียน
                                </a>
                            </div>
                        </div>
                        @endauth

                        <!-- Content Management -->
                        <div class="col-md-{{ auth()->check() ? '4' : '6' }}">
                            <h6 class="text-muted mb-2">
                                <i class="fas fa-folder-open"></i> จัดการเนื้อหา
                            </h6>
                            <div class="d-grid gap-2">
                                <a href="{{ route('sandbox.schools.innovations', $school->id) }}" class="btn btn-outline-success">
                                    <i class="fas fa-lightbulb"></i> {{ auth()->check() ? 'จัดการนวัตกรรม' : 'ดูนวัตกรรม' }}
                                </a>
                                <a href="{{ route('sandbox.schools.galleries.index', $school->id) }}" class="btn btn-outline-info">
                                    <i class="fas fa-images"></i> {{ auth()->check() ? 'จัดการคลังภาพ' : 'ดูคลังภาพ' }}
                                </a>
                            </div>
                        </div>

                        <!-- Quick Add -->
                        @auth
                        <div class="col-md-4">
                            <h6 class="text-muted mb-2">
                                <i class="fas fa-plus-circle"></i> เพิ่มข้อมูลใหม่
                            </h6>
                            <div class="d-grid gap-2">
                                <a href="{{ route('sandbox.schools.vision-videos.create', $school->id) }}" class="btn btn-primary">
                                    <i class="fas fa-video"></i> เพิ่มวิดีโอวิสัยทัศน์
                                </a>
                                <a href="{{ route('sandbox.schools.innovations.create', $school->id) }}" class="btn btn-success">
                                    <i class="fas fa-lightbulb"></i> เพิ่มนวัตกรรม
                                </a>
                                <a href="{{ route('sandbox.schools.galleries.create', $school->id) }}" class="btn btn-info">
                                    <i class="fas fa-images"></i> เพิ่มคลังภาพ
                                </a>
                            </div>
                        </div>
                        @endauth
                    </div>
                </div>
            </div>

            <div class="school-detail-content">
                <!-- Statistics Overview -->
                <div class="stats-overview">
                    <div class="stat-card students">
                        <div class="stat-icon">👥</div>
                        <div class="stat-content">
                            <h3>นักเรียนทั้งหมด</h3>
                            <div class="stat-number">{{ number_format($school->total_students) }}</div>
                            <div class="stat-breakdown">
                                <span class="male">ชาย {{ number_format($school->male_students) }}</span>
                                <span class="female">หญิง {{ number_format($school->female_students) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card teachers">
                        <div class="stat-icon">👨‍🏫</div>
                        <div class="stat-content">
                            <h3>ครู/บุคลากร</h3>
                            <div class="stat-number">{{ number_format($school->total_teachers) }}</div>
                            <div class="stat-breakdown">
                                <span class="male">ชาย {{ number_format($school->male_teachers) }}</span>
                                <span class="female">หญิง {{ number_format($school->female_teachers) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card innovations">
                        <div class="stat-icon">💡</div>
                        <div class="stat-content">
                            <h3>นวัตกรรม</h3>
                            <div class="stat-number">{{ $school->active_innovations_count }}</div>
                            <div class="stat-breakdown">
                                <span>Active Innovations</span>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card innovations">
                        <div class="stat-icon">📊</div>
                        <div class="stat-content">
                            <h3>อัตราส่วนนักเรียน : ครู</h3>
                            @if ($school->total_teachers > 0)
                                <div class="stat-number">
                                    {{ number_format($school->total_students / $school->total_teachers, 2) }} : 1</div>
                                <div class="stat-breakdown">
                                    <span>นักเรียน {{ number_format($school->total_students) }} คน ต่อ ครู
                                        {{ number_format($school->total_teachers) }} คน</span>
                                </div>
                            @else
                                <div class="stat-number">-</div>
                                <div class="stat-breakdown">
                                    <span>ไม่มีข้อมูลครู</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                @if ($school->address || $school->phone || $school->email)
                    <div class="info-section">
                        <h2 class="section-title">ข้อมูลการติดต่อ</h2>
                        <div class="contact-grid">
                            @if ($school->address)
                                <div class="contact-item">
                                    <div class="contact-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div class="contact-content">
                                        <h4>ที่อยู่</h4>
                                        <p>{{ $school->address }}</p>
                                    </div>
                                </div>
                            @endif

                            @if ($school->phone)
                                <div class="contact-item">
                                    <div class="contact-icon">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <div class="contact-content">
                                        <h4>เบอร์โทรศัพท์</h4>
                                        <p><a href="tel:{{ $school->phone }}">{{ $school->phone }}</a></p>
                                    </div>
                                </div>
                            @endif

                            @if ($school->email)
                                <div class="contact-item">
                                    <div class="contact-icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="contact-content">
                                        <h4>อีเมล</h4>
                                        <p><a href="mailto:{{ $school->email }}">{{ $school->email }}</a></p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Vision Videos Section -->
                <div class="info-section">
                    <div class="section-header">
                        <h2 class="section-title">
                            <i class="fas fa-video"></i>
                            วิดีโอวิสัยทัศน์
                        </h2>
                        <div class="section-actions">
                            @auth
                                <a href="{{ route('sandbox.schools.vision-videos.create', $school->id) }}"
                                    class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> เพิ่มวิดีโอใหม่
                                </a>
                            @endauth
                        </div>
                    </div>

                    @if ($school->visionVideos->where('is_active', true)->count() > 0)
                        <div class="vision-videos-grid">
                            @foreach ($school->visionVideos->where('is_active', true)->sortBy('order') as $video)
                                <div class="vision-video-card">
                                    <div class="video-wrapper">
                                        <iframe src="{{ $video->embed_url }}" frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen loading="lazy">
                                        </iframe>
                                    </div>
                                    <div class="video-info">
                                        <h3 class="video-title">{{ $video->title }}</h3>
                                        @if ($video->description)
                                            <p class="video-description">{{ Str::limit($video->description, 150) }}</p>
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
                                        @auth
                                            <div class="video-actions">
                                                <a href="{{ route('sandbox.schools.vision-videos.edit', [$school->id, $video->id]) }}"
                                                    class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i> แก้ไข
                                                </a>
                                                <form
                                                    action="{{ route('sandbox.schools.vision-videos.destroy', [$school->id, $video->id]) }}"
                                                    method="POST" style="display: inline;"
                                                    onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบวิดีโอนี้?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i> ลบ
                                                    </button>
                                                </form>
                                            </div>
                                        @endauth
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-videos">
                            <div class="empty-icon">
                                <i class="fas fa-video" style="font-size: 3rem; color: #95a5a6;"></i>
                            </div>
                            <h3>ยังไม่มีวิดีโอวิสัยทัศน์</h3>
                            <p>
                                {{ auth()->check() ? 'เริ่มต้นด้วยการเพิ่มวิดีโอวิสัยทัศน์ของโรงเรียน' : 'โรงเรียนนี้ยังไม่มีวิดีโอวิสัยทัศน์ในระบบ' }}
                            </p>
                            @auth
                                <a href="{{ route('sandbox.schools.vision-videos.create', $school->id) }}"
                                    class="btn btn-primary">
                                    <i class="fas fa-plus"></i> เพิ่มวิดีโอใหม่
                                </a>
                            @endauth
                        </div>
                    @endif
                </div>

                <!-- Gallery Section -->
                <div class="info-section">
                    <div class="section-header">
                        <h2 class="section-title">
                            <i class="fas fa-images"></i>
                            คลังภาพกิจกรรม
                        </h2>
                        <div class="section-actions">
                            @auth
                                <a href="{{ route('sandbox.schools.galleries.create', $school->id) }}"
                                    class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> เพิ่มคลังภาพใหม่
                                </a>
                            @endauth
                            <a href="{{ route('sandbox.schools.galleries.index', $school->id) }}"
                                class="btn btn-outline btn-sm">
                                <i class="fas fa-images"></i> ดูทั้งหมด
                            </a>
                        </div>
                    </div>

                    @if ($school->galleries->where('is_active', true)->count() > 0)
                        <div class="galleries-grid">
                            @foreach ($school->galleries->where('is_active', true)->sortBy('display_order')->take(6) as $gallery)
                                <div class="gallery-item-card">
                                    <div class="gallery-preview">
                                        <iframe 
                                            src="{{ $gallery->embed_url }}" 
                                            class="gallery-preview-iframe"
                                            frameborder="0"
                                            scrolling="no"
                                            loading="lazy">
                                        </iframe>
                                        <div class="gallery-overlay">
                                            <a href="{{ route('sandbox.schools.galleries.show', [$school->id, $gallery->id]) }}" 
                                               class="btn btn-light btn-sm">
                                                <i class="fas fa-eye"></i> ดูภาพทั้งหมด
                                            </a>
                                        </div>
                                    </div>
                                    <div class="gallery-item-info">
                                        <h4 class="gallery-item-title">
                                            <a href="{{ route('sandbox.schools.galleries.show', [$school->id, $gallery->id]) }}">
                                                {{ $gallery->title }}
                                            </a>
                                        </h4>
                                        @if ($gallery->description)
                                            <p class="gallery-item-description">{{ Str::limit($gallery->description, 100) }}</p>
                                        @endif
                                        <div class="gallery-item-meta">
                                            <span class="meta-date">
                                                <i class="far fa-calendar"></i>
                                                {{ $gallery->created_at->format('d/m/Y') }}
                                            </span>
                                        </div>
                                        @auth
                                            <div class="gallery-item-actions">
                                                <a href="{{ route('sandbox.schools.galleries.show', [$school->id, $gallery->id]) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> ดูภาพ
                                                </a>
                                                <a href="{{ route('sandbox.schools.galleries.edit', [$school->id, $gallery->id]) }}"
                                                    class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-edit"></i> แก้ไข
                                                </a>
                                                <form
                                                    action="{{ route('sandbox.schools.galleries.destroy', [$school->id, $gallery->id]) }}"
                                                    method="POST" style="display: inline;"
                                                    onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบคลังภาพนี้?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-trash"></i> ลบ
                                                    </button>
                                                </form>
                                            </div>
                                        @endauth
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @if ($school->galleries->where('is_active', true)->count() > 6)
                            <div class="text-center mt-3">
                                <a href="{{ route('sandbox.schools.galleries.index', $school->id) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-images"></i> ดูคลังภาพทั้งหมด ({{ $school->galleries->where('is_active', true)->count() }} คลัง)
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="empty-videos">
                            <div class="empty-icon">
                                <i class="fas fa-images" style="font-size: 3rem; color: #95a5a6;"></i>
                            </div>
                            <h3>ยังไม่มีคลังภาพกิจกรรม</h3>
                            <p>
                                {{ auth()->check() ? 'เริ่มต้นด้วยการเพิ่มคลังภาพกิจกรรมจาก Google Drive' : 'โรงเรียนนี้ยังไม่มีคลังภาพกิจกรรมในระบบ' }}
                            </p>
                            @auth
                                <a href="{{ route('sandbox.schools.galleries.create', $school->id) }}"
                                    class="btn btn-primary">
                                    <i class="fas fa-plus"></i> เพิ่มคลังภาพใหม่
                                </a>
                            @endauth
                        </div>
                    @endif
                </div>

                <!-- Innovations Section -->
                <div class="info-section">
                    <div class="section-header">
                        <h2 class="section-title">นวัตกรรมของโรงเรียน</h2>
                        <div class="section-actions">
                            @auth
                                <a href="{{ route('sandbox.schools.innovations.create', $school->id) }}"
                                    class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> เพิ่มนวัตกรรมใหม่
                                </a>
                            @endauth
                            <a href="{{ route('sandbox.schools.innovations', $school->id) }}"
                                class="btn btn-outline btn-sm">
                                <i class="fas fa-list"></i> {{ auth()->check() ? 'จัดการทั้งหมด' : 'ดูทั้งหมด' }}
                            </a>
                        </div>
                    </div>

                    @if ($school->innovations->count() > 0)
                        <div class="innovations-grid">
                            @foreach ($school->innovations->sortByDesc('created_at') as $innovation)
                                @php
                                    $categoryLabel = $innovation->category ?: 'ไม่ระบุหมวดหมู่';
                                    $schoolName = strip_tags($school->name ?? 'ไม่พบข้อมูลโรงเรียน');
                                    $detailUrl = route('sandbox.schools.innovations', $school->id) . '?highlight=' . $innovation->id;
                                    $imagePaths = collect($innovation->image_paths ?? []);
                                    $firstPath = $imagePaths->first();
                                    $firstFile = $firstPath ? basename($firstPath) : null;
                                    $firstExtension = $firstFile ? strtolower(pathinfo($firstFile, PATHINFO_EXTENSION)) : null;
                                    $isFirstPdf = $firstExtension === 'pdf';
                                    $previewImageUrl = $firstFile && !$isFirstPdf
                                        ? route('sandbox.innovation.image', [$school->id, $firstFile])
                                        : null;
                                    $lightboxImages = $imagePaths
                                        ->map(function ($path) use ($school) {
                                            $file = $path ? basename($path) : null;
                                            if (!$file) {
                                                return null;
                                            }

                                            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                            if ($ext === 'pdf') {
                                                return null;
                                            }

                                            return [
                                                'url' => route('sandbox.innovation.image', [$school->id, $file]),
                                                'name' => $file,
                                            ];
                                        })
                                        ->filter()
                                        ->values();
                                    $lightboxImagesJson = $lightboxImages->toJson();
                                @endphp
                                <div class="innovation-card {{ $innovation->is_active ? 'active' : 'inactive' }}"
                                    role="button" tabindex="0"
                                    aria-label="ดูนวัตกรรม {{ strip_tags($innovation->title ?? '') }}"
                                    data-lightbox="1"
                                    data-category="{{ $innovation->category ?? '' }}"
                                    data-status="{{ $innovation->is_active ? '1' : '0' }}"
                                    data-innovation-id="{{ $innovation->id }}"
                                    data-title="{{ Str::lower(strip_tags($innovation->title ?? '')) }}"
                                    data-description="{{ Str::lower(strip_tags($innovation->description ?? '')) }}"
                                    data-title-display="{{ e(strip_tags($innovation->title ?? '')) }}"
                                    data-school-name="{{ e($schoolName) }}"
                                    data-category-label="{{ e($categoryLabel) }}"
                                    data-detail-url="{{ $detailUrl }}"
                                    data-images="{{ e($lightboxImagesJson) }}"
                                    style="cursor: pointer;">

                                    @if ($previewImageUrl || $isFirstPdf)
                                        <div class="innovation-image {{ $isFirstPdf ? 'pdf-container' : '' }}">
                                            @if ($isFirstPdf)
                                                <div class="pdf-preview-card">
                                                    <i class="fas fa-file-pdf pdf-icon"></i>
                                                </div>
                                            @else
                                                <img src="{{ $previewImageUrl }}" alt="{{ $innovation->title }}"
                                                    loading="lazy" style="width: 100%; height: 200px; object-fit: cover;"
                                                    onerror="this.onerror=null; this.parentElement.classList.add('placeholder'); this.style.display='none';">
                                                @if ($imagePaths->count() > 1)
                                                    <div class="file-count-badge">
                                                        <i class="fas fa-images"></i>
                                                        {{ $imagePaths->count() }}
                                                    </div>
                                                @endif
                                            @endif

                                            <div class="image-overlay">
                                                <div class="status-badge {{ $innovation->is_active ? 'active' : 'inactive' }}">
                                                    {{ $innovation->is_active ? 'ใช้งาน' : 'ไม่ใช้งาน' }}
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="innovation-image placeholder">
                                            <div class="placeholder-icon">
                                                <i class="fas fa-lightbulb"></i>
                                            </div>
                                            <div class="status-badge {{ $innovation->is_active ? 'active' : 'inactive' }}">
                                                {{ $innovation->is_active ? 'ใช้งาน' : 'ไม่ใช้งาน' }}
                                            </div>
                                        </div>
                                    @endif

                                    <div class="innovation-content">
                                        <h4 class="innovation-title">{{ $innovation->title }}</h4>

                                        @if ($innovation->category)
                                            <span class="innovation-category">{{ $innovation->category }}</span>
                                        @endif

                                        @if ($innovation->description)
                                            <p class="innovation-description">
                                                {{ Str::limit($innovation->description, 120) }}
                                            </p>
                                        @endif

                                        <div class="innovation-meta">
                                            @if ($innovation->year)
                                                <span class="innovation-year">
                                                    <i class="fas fa-calendar"></i>
                                                    พ.ศ. {{ $innovation->year }}
                                                </span>
                                            @endif
                                            <span class="innovation-date">
                                                <i class="fas fa-clock"></i>
                                                {{ $innovation->created_at->format('d/m/Y') }}
                                            </span>
                                        </div>

                                        <div class="innovation-actions">
                                            <a href="{{ $detailUrl }}" class="btn btn-sm btn-outline-primary"
                                                target="_blank" rel="noopener">
                                                <i class="fas fa-info-circle"></i> ดูรายละเอียด
                                            </a>
                                            @auth
                                                <a href="{{ route('sandbox.schools.innovations.edit', [$school->id, $innovation->id]) }}"
                                                    class="btn btn-sm btn-warning" onclick="event.stopPropagation();">
                                                    <i class="fas fa-edit"></i> แก้ไข
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    onclick="event.stopPropagation(); deleteInnovation({{ $innovation->id }}, '{{ addslashes($innovation->title) }}')">
                                                    <i class="fas fa-trash"></i> ลบ
                                                </button>
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-innovations">
                            <div class="empty-icon">💡</div>
                            <h3>ยังไม่มีนวัตกรรม</h3>
                            <p>{{ auth()->check() ? 'เริ่มต้นด้วยการเพิ่มนวัตกรรมแรกของโรงเรียน' : 'โรงเรียนนี้ยังไม่มีนวัตกรรมในระบบ' }}
                            </p>
                            @auth
                                <a href="{{ route('sandbox.schools.innovations.create', $school->id) }}"
                                    class="btn btn-primary">
                                    <i class="fas fa-plus"></i> เพิ่มนวัตกรรมใหม่
                                </a>
                            @endauth
                        </div>
                    @endif
                </div>

                <div class="modal fade innovation-lightbox" id="innovationLightboxModal" tabindex="-1"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xl">
                        <div class="modal-content">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
                            <button type="button" class="innovation-lightbox-share" id="innovationLightboxShare"
                                aria-label="คัดลอกลิงก์ภาพ">
                                <i class="fas fa-share-alt"></i>
                            </button>
                            <div class="innovation-lightbox-main">
                                <button type="button" class="innovation-lightbox-nav innovation-lightbox-prev"
                                    id="innovationLightboxPrev" aria-label="ภาพก่อนหน้า">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <div class="innovation-lightbox-image-wrapper" id="innovationLightboxImageWrapper">
                                    <img id="innovationLightboxImage" src="" alt="">
                                </div>
                                <button type="button" class="innovation-lightbox-nav innovation-lightbox-next"
                                    id="innovationLightboxNext" aria-label="ภาพถัดไป">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                            <div class="innovation-lightbox-feedback" id="innovationLightboxFeedback" role="status"
                                aria-live="polite"></div>
                            <div class="innovation-lightbox-meta">
                                <div class="innovation-lightbox-meta-text">
                                    <h3 id="innovationLightboxTitle"></h3>
                                    <p>
                                        <span id="innovationLightboxSchool"></span>
                                        <span class="bullet">•</span>
                                        <span id="innovationLightboxCategory"></span>
                                    </p>
                                </div>
                                <a id="innovationLightboxDetailLink" class="btn btn-outline-light btn-sm" href="#"
                                    target="_blank" rel="noopener">
                                    ดูรายละเอียด
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delete Confirmation Modal -->
                <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel">ยืนยันการลบ</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>คุณต้องการลบนวัตกรรม "<span id="deleteItemName"></span>" หรือไม่?</p>
                                <p class="text-danger"><small>การดำเนินการนี้ไม่สามารถย้อนกลับได้</small></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                                <form id="deleteForm" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">ลบ</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- School Info Timeline -->
                <div class="info-section">
                    <h2 class="section-title">ข้อมูลระบบ</h2>
                    <div class="system-info">
                        <div class="info-item">
                            <span class="info-label">รหัสโรงเรียน:</span>
                            <span class="info-value">#{{ str_pad($school->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">เพิ่มเข้าระบบ:</span>
                            <span class="info-value">{{ $school->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">แก้ไขล่าสุด:</span>
                            <span class="info-value">{{ $school->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script-content')
    <script src="{{ asset('assets/common/js/innovation-lightbox.js') }}"></script>
    <script src="{{ asset('assets/common/js/school-management.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.innovation-card');
            const modalEl = document.getElementById('innovationLightboxModal');

            if (modalEl && window.InnovationLightbox && typeof window.InnovationLightbox.create === 'function') {
                const lightboxController = window.InnovationLightbox.create(modalEl);
                if (lightboxController && typeof lightboxController.registerCards === 'function') {
                    lightboxController.registerCards(cards);
                    window.schoolInnovationLightbox = lightboxController;
                }
            }

            const filterCategoryEl = document.getElementById('filterCategory');
            const filterStatusEl = document.getElementById('filterStatus');

            if (filterCategoryEl) {
                filterCategoryEl.addEventListener('change', filterInnovations);
            }

            if (filterStatusEl) {
                filterStatusEl.addEventListener('change', filterInnovations);
            }

            document.querySelectorAll('.innovation-image img').forEach((img) => {
                if (img.complete) {
                    img.classList.add('loaded');
                } else {
                    img.addEventListener('load', function() {
                        this.classList.add('loaded');
                    });
                }
            });

            filterInnovations();
        });

        function filterInnovations() {
            const categoryEl = document.getElementById('filterCategory');
            const statusEl = document.getElementById('filterStatus');
            const categoryFilter = categoryEl ? categoryEl.value : '';
            const statusFilter = statusEl ? statusEl.value : '';
            const cards = document.querySelectorAll('.innovation-card');

            cards.forEach(card => {
                const category = card.dataset.category || '';
                const status = card.dataset.status;

                let showCard = true;

                if (categoryFilter && category !== categoryFilter) {
                    showCard = false;
                }

                if (statusFilter && status !== statusFilter) {
                    showCard = false;
                }

                card.style.display = showCard ? '' : 'none';
            });
        }

        function deleteInnovation(id, title) {
            document.getElementById('deleteItemName').textContent = title;
            document.getElementById('deleteForm').action = `/sandbox/schools/{{ $school->id }}/innovations/${id}`;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }

        function toggleStatus(id, newStatus) {
            if (confirm(`คุณต้องการ${newStatus ? 'เปิด' : 'ปิด'}ใช้งานนวัตกรรมนี้หรือไม่?`)) {
                fetch(`/sandbox/schools/{{ $school->id }}/innovations/${id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            is_active: newStatus
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('เกิดข้อผิดพลาดในการอัปเดต');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
                    });
            }
        }
    </script>
@endsection
