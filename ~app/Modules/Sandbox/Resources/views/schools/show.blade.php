@extends('sandbox::layouts.master')

@section('title', 'รายละเอียด ' . $school->name)

@section('stylesheet-content')
    <link rel="stylesheet" href="{{ asset('assets/common/css/school-detail.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/common/css/innovation-shared.css') }}">
    <style>
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
                                <div class="innovation-card {{ $innovation->is_active ? 'active' : 'inactive' }}"
                                    data-category="{{ $innovation->category }}"
                                    data-status="{{ $innovation->is_active ? '1' : '0' }}"
                                    data-innovation-id="{{ $innovation->id }}"
                                    data-innovation-title="{{ htmlspecialchars($innovation->title, ENT_QUOTES) }}"
                                    data-innovation-description="{{ htmlspecialchars($innovation->description ?? '', ENT_QUOTES) }}"
                                    data-innovation-category="{{ $innovation->category }}"
                                    data-innovation-year="{{ $innovation->year }}"
                                    data-innovation-active="{{ $innovation->is_active ? '1' : '0' }}"
                                    data-innovation-files="{{ $innovation->image_paths ? json_encode($innovation->image_paths) : '[]' }}"
                                    onclick="viewInnovationFast(this)" style="cursor: pointer;">

                                    @if ($innovation->image_paths && count($innovation->image_paths) > 0)
                                        @php
                                            $firstFile = $innovation->image_paths[0];
                                            $fileName = basename($firstFile);
                                            $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                                            $isPDF = $extension === 'pdf';
                                        @endphp
                                        <div class="innovation-image {{ $isPDF ? 'pdf-container' : '' }}">
                                            @if ($isPDF)
                                                <!-- PDF Preview -->
                                                <div class="pdf-preview-card">
                                                    <i class="fas fa-file-pdf pdf-icon"></i>
                                                </div>
                                            @else
                                                <!-- Image Preview -->
                                                @php
                                                    $imageUrl = route('sandbox.innovation.image', [
                                                        $school->id,
                                                        $fileName,
                                                    ]);
                                                @endphp
                                                <img src="{{ $imageUrl }}" alt="{{ $innovation->title }}"
                                                    loading="lazy" style="width: 100%; height: 200px; object-fit: cover;"
                                                    onerror="this.onerror=null; this.parentElement.classList.add('placeholder'); this.style.display='none';">
                                                @if (count($innovation->image_paths) > 1)
                                                    <div class="file-count-badge">
                                                        <i class="fas fa-images"></i>
                                                        {{ count($innovation->image_paths) }}
                                                    </div>
                                                @endif
                                            @endif

                                            <div class="image-overlay">
                                                <div
                                                    class="status-badge {{ $innovation->is_active ? 'active' : 'inactive' }}">
                                                    {{ $innovation->is_active ? 'ใช้งาน' : 'ไม่ใช้งาน' }}
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="innovation-image placeholder">
                                            <div class="placeholder-icon">
                                                <i class="fas fa-lightbulb"></i>
                                            </div>
                                            <div
                                                class="status-badge {{ $innovation->is_active ? 'active' : 'inactive' }}">
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
                                            @auth
                                                <a href="{{ route('sandbox.schools.innovations.edit', [$school->id, $innovation->id]) }}"
                                                    class="btn btn-sm btn-warning" onclick="event.stopPropagation();">
                                                    <i class="fas fa-edit"></i> แก้ไข
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    onclick="event.stopPropagation(); deleteInnovation({{ $innovation->id }}, '{{ addslashes($innovation->title) }}')">
                                                    <i class="fas fa-trash"></i> ลบ
                                                </button>
                                            @else
                                                <div class="text-muted small">
                                                    <i class="fas fa-hand-pointer"></i> คลิกเพื่อดูรายละเอียด
                                                </div>
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

                <!-- Innovation Detail Modal -->
                <div class="modal fade" id="innovationModal" tabindex="-1" aria-labelledby="innovationModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="innovationModalLabel">รายละเอียดนวัตกรรม</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="innovationModalBody">
                                <!-- Content will be loaded here -->
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
    <script src="{{ asset('assets/common/js/school-management.js') }}"></script>
    <script>
        // Image load handler for fade-in animation
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.innovation-image img').forEach(img => {
                // Add loaded class when image finishes loading
                if (img.complete) {
                    img.classList.add('loaded');
                } else {
                    img.addEventListener('load', function() {
                        this.classList.add('loaded');
                    });
                }
            });
        });

        // Filtering functionality
        document.getElementById('filterCategory').addEventListener('change', function() {
            filterInnovations();
        });

        document.getElementById('filterStatus').addEventListener('change', function() {
            filterInnovations();
        });

        function filterInnovations() {
            const categoryFilter = document.getElementById('filterCategory').value;
            const statusFilter = document.getElementById('filterStatus').value;
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

                card.style.display = showCard ? 'block' : 'none';
            });
        }

        function viewInnovationFast(cardElement) {
            // Get data from card attributes (instant, no API call)
            const title = cardElement.dataset.innovationTitle;
            const description = cardElement.dataset.innovationDescription;
            const category = cardElement.dataset.innovationCategory;
            const year = cardElement.dataset.innovationYear;
            const isActive = cardElement.dataset.innovationActive === '1';
            const filesData = cardElement.dataset.innovationFiles;

            // Generate files HTML
            let filesHtml = '';
            if (filesData && filesData !== '[]') {
                try {
                    const filePaths = JSON.parse(filesData);
                    if (Array.isArray(filePaths) && filePaths.length > 0) {
                        if (filePaths.length === 1) {
                            // Single file
                            const filePath = filePaths[0];
                            const fileName = filePath.split('/').pop();
                            const extension = fileName.split('.').pop().toLowerCase();
                            const isPDF = extension === 'pdf';

                            if (isPDF) {
                                filesHtml = `
                            <div class="file-display">
                                <div class="pdf-viewer">
                                    <div class="pdf-header">
                                        <i class="fas fa-file-pdf text-danger"></i>
                                        <span class="file-name">${fileName}</span>
                                        <a href="/sandbox/innovation-image/{{ $school->id }}/${fileName}" target="_blank" class="btn btn-sm btn-primary">
                                            <i class="fas fa-external-link-alt"></i> เปิดดู
                                        </a>
                                    </div>
                                    <div class="pdf-preview-container">
                                        <embed src="/sandbox/innovation-image/{{ $school->id }}/${fileName}" type="application/pdf" width="100%" height="400px" />
                                    </div>
                                </div>
                            </div>
                        `;
                            } else {
                                const imageUrl = `/sandbox/innovation-image/{{ $school->id }}/${fileName}`;
                                filesHtml =
                                    `<img src="${imageUrl}" alt="${title}" class="img-fluid mb-3 rounded" style="max-height: 400px; object-fit: contain; width: 100%;">`;
                            }
                        } else {
                            // Multiple files - show carousel
                            filesHtml = `
                        <div id="filesCarousel" class="carousel slide mb-3" data-bs-ride="carousel">
                            <div class="carousel-inner">`;

                            filePaths.forEach((filePath, index) => {
                                const fileName = filePath.split('/').pop();
                                const extension = fileName.split('.').pop().toLowerCase();
                                const isPDF = extension === 'pdf';

                                filesHtml += `
                            <div class="carousel-item ${index === 0 ? 'active' : ''}">`;

                                if (isPDF) {
                                    filesHtml += `
                                <div class="pdf-viewer">
                                    <div class="pdf-header">
                                        <i class="fas fa-file-pdf text-danger"></i>
                                        <span class="file-name">${fileName}</span>
                                        <a href="/sandbox/innovation-image/{{ $school->id }}/${fileName}" target="_blank" class="btn btn-sm btn-primary">
                                            <i class="fas fa-external-link-alt"></i> เปิดดู
                                        </a>
                                    </div>
                                    <div class="pdf-preview-container">
                                        <embed src="/sandbox/innovation-image/{{ $school->id }}/${fileName}" type="application/pdf" width="100%" height="350px" />
                                    </div>
                                </div>
                            `;
                                } else {
                                    const imageUrl = `/sandbox/innovation-image/{{ $school->id }}/${fileName}`;
                                    filesHtml +=
                                        `<img src="${imageUrl}" alt="${title}" class="d-block w-100" style="max-height: 400px; object-fit: contain;">`;
                                }

                                filesHtml += `</div>`;
                            });

                            filesHtml += `
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#filesCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#filesCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                            <div class="carousel-indicators">`;

                            filePaths.forEach((_, index) => {
                                filesHtml += `
                            <button type="button" data-bs-target="#filesCarousel" data-bs-slide-to="${index}" 
                                    ${index === 0 ? 'class="active" aria-current="true"' : ''} 
                                    aria-label="Slide ${index + 1}"></button>`;
                            });

                            filesHtml += `
                            </div>
                        </div>
                    `;
                        }
                    }
                } catch (e) {
                    console.error('Error parsing files data:', e);
                }
            }

            // Build modal content instantly
            document.getElementById('innovationModalBody').innerHTML = `
        <div class="innovation-detail-modal">
            ${filesHtml}
            <h4 class="mb-3">${title}</h4>
            ${category ? `<span class="badge bg-secondary mb-3">${category}</span>` : ''}
            ${description ? `<p class="text-muted" style="white-space: pre-wrap;">${description}</p>` : '<p class="text-muted">ไม่มีรายละเอียด</p>'}
            <hr>
            <div class="mt-3">
                ${year ? `<div class="mb-2"><i class="fas fa-calendar text-primary"></i> <strong>ปีที่เริ่มใช้งาน:</strong> พ.ศ. ${year}</div>` : ''}
                <div><i class="fas fa-toggle-${isActive ? 'on' : 'off'} text-${isActive ? 'success' : 'secondary'}"></i> <strong>สถานะ:</strong> ${isActive ? '<span class="text-success">ใช้งานอยู่</span>' : '<span class="text-muted">ไม่ใช้งาน</span>'}</div>
            </div>
        </div>
    `;

            // Show modal instantly (no delay!)
            new bootstrap.Modal(document.getElementById('innovationModal')).show();
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
