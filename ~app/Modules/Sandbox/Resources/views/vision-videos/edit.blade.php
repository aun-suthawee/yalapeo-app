@extends('sandbox::layouts.master')

@section('title', 'แก้ไขวิดีโอวิสัยทัศน์ - ' . $school->name)

@section('stylesheet-content')
    <link rel="stylesheet" href="{{ asset('assets/common/css/school-innovation-form.css') }}">
    <style>
        .video-preview {
            margin-top: 1.5rem;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 12px;
            display: none;
        }

        .video-preview.active {
            display: block;
        }

        .video-preview iframe {
            width: 100%;
            max-width: 640px;
            height: 360px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .current-video {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: #f1f3f5;
            border-radius: 12px;
            border-left: 4px solid #3498db;
        }

        .current-video h4 {
            margin-bottom: 1rem;
            color: #2c3e50;
        }

        .url-example {
            font-size: 0.875rem;
            color: #6c757d;
            margin-top: 0.5rem;
            padding: 0.75rem;
            background: #f8f9fa;
            border-left: 3px solid #3498db;
            border-radius: 4px;
            display: block;
        }

        .url-example strong {
            color: #2c3e50;
            display: block;
            margin-bottom: 0.25rem;
        }

        .delete-section {
            margin-top: 2rem;
            padding: 1.5rem;
            background: #fff3cd;
            border-radius: 12px;
            border-left: 4px solid #ffc107;
        }

        .delete-section h4 {
            color: #856404;
            margin-bottom: 1rem;
        }
    </style>
@endsection

@section('content')
    <div class="innovation-form-container">
        <div class="container">
            <!-- Header -->
            <div class="page-header">
                <div class="header-content">
                    <h1 class="page-title">
                        <i class="fas fa-edit"></i>
                        แก้ไขวิดีโอวิสัยทัศน์
                    </h1>
                    <p class="page-subtitle">สำหรับ {{ $school->name }}</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('sandbox.schools.show', $school->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> กลับ
                    </a>
                </div>
            </div>

            <!-- Current Video Display -->
            <div class="current-video">
                <h4><i class="fas fa-video"></i> วิดีโอปัจจุบัน</h4>
                <div style="margin-bottom: 1rem;">
                    <strong>{{ $video->title }}</strong>
                    <br>
                    <small class="text-muted">{{ $video->video_type }} • สร้างเมื่อ {{ $video->created_at->format('d/m/Y H:i') }}</small>
                </div>
                <iframe src="{{ $video->embed_url }}" width="100%" height="360" frameborder="0" 
                    style="max-width: 640px; border-radius: 8px;" allowfullscreen></iframe>
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

                <form action="{{ route('sandbox.schools.vision-videos.update', [$school->id, $video->id]) }}" method="POST"
                    class="innovation-form" id="visionVideoForm">
                    @csrf
                    @method('PUT')

                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-info-circle"></i>
                            ข้อมูลวิดีโอ
                        </h3>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="video_type" class="form-label required">
                                    <i class="fas fa-film"></i>
                                    ประเภทวิดีโอ
                                </label>
                                <select class="form-control @error('video_type') is-invalid @enderror" id="video_type"
                                    name="video_type" required>
                                    <option value="">เลือกประเภทวิดีโอ...</option>
                                    <option value="youtube" {{ old('video_type', $video->video_type) == 'youtube' ? 'selected' : '' }}>
                                        YouTube
                                    </option>
                                    <option value="facebook" {{ old('video_type', $video->video_type) == 'facebook' ? 'selected' : '' }}>
                                        Facebook
                                    </option>
                                    <option value="tiktok" {{ old('video_type', $video->video_type) == 'tiktok' ? 'selected' : '' }}>
                                        TikTok
                                    </option>
                                    <option value="other" {{ old('video_type', $video->video_type) == 'other' ? 'selected' : '' }}>
                                        อื่นๆ
                                    </option>
                                </select>
                                @error('video_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="video_url" class="form-label required">
                                    <i class="fas fa-link"></i>
                                    URL วิดีโอ
                                </label>
                                <input type="url" class="form-control @error('video_url') is-invalid @enderror"
                                    id="video_url" name="video_url" value="{{ old('video_url', $video->video_url) }}"
                                    placeholder="โปรดระบุ URL วิดีโอ" required>
                                @error('video_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="url-example" id="urlExample">
                                    <strong>ตัวอย่าง:</strong> https://www.youtube.com/watch?v=dQw4w9WgXcQ
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="title" class="form-label required">
                                    <i class="fas fa-heading"></i>
                                    หัวข้อวิดีโอ
                                </label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title', $video->title) }}"
                                    placeholder="ระบุหัวข้อหรือชื่อวิดีโอ" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="description" class="form-label">
                                    <i class="fas fa-align-left"></i>
                                    คำอธิบาย
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                                    name="description" rows="4"
                                    placeholder="คำอธิบายเกี่ยวกับวิดีโอวิสัยทัศน์นี้...">{{ old('description', $video->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="order" class="form-label">
                                    <i class="fas fa-sort-numeric-down"></i>
                                    ลำดับการแสดง
                                </label>
                                <input type="number" class="form-control @error('order') is-invalid @enderror"
                                    id="order" name="order" value="{{ old('order', $video->order) }}" min="0">
                                @error('order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">กำหนดลำดับการแสดงผล (เลขน้อยแสดงก่อน)</small>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active"
                                        {{ old('is_active', $video->is_active) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_active">
                                        <i class="fas fa-eye"></i>
                                        เผยแพร่วิดีโอนี้
                                    </label>
                                </div>
                                <small class="form-text text-muted">เปิดให้สาธารณะสามารถชมวิดีโอนี้ได้</small>
                            </div>
                        </div>
                    </div>

                    <!-- Video Preview Section -->
                    <div id="videoPreview" class="video-preview">
                        <h4 style="margin-bottom: 1rem;">
                            <i class="fas fa-eye"></i> ตัวอย่างวิดีโอที่แก้ไข
                        </h4>
                        <div id="videoEmbed"></div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> บันทึกการแก้ไข
                        </button>
                        <button type="button" class="btn btn-outline" id="previewBtn">
                            <i class="fas fa-eye"></i> ดูตัวอย่าง
                        </button>
                        <a href="{{ route('sandbox.schools.show', $school->id) }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> ยกเลิก
                        </a>
                    </div>
                </form>

                <!-- Delete Section -->
                <div class="delete-section">
                    <h4><i class="fas fa-trash-alt"></i> ลบวิดีโอนี้</h4>
                    <p>หากคุณต้องการลบวิดีโอนี้ออกจากระบบ คลิกปุ่มด้านล่าง</p>
                    <form action="{{ route('sandbox.schools.vision-videos.destroy', [$school->id, $video->id]) }}" 
                        method="POST" onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบวิดีโอนี้?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash-alt"></i> ลบวิดีโอนี้
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script-content')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const videoTypeSelect = document.getElementById('video_type');
            const videoUrlInput = document.getElementById('video_url');
            const urlExample = document.getElementById('urlExample');
            const previewBtn = document.getElementById('previewBtn');
            const videoPreview = document.getElementById('videoPreview');
            const videoEmbed = document.getElementById('videoEmbed');

            const urlExamples = {
                youtube: 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                facebook: 'https://www.facebook.com/username/videos/1234567890/',
                tiktok: 'https://www.tiktok.com/@username/video/1234567890',
                other: 'https://example.com/video.mp4'
            };

            const placeholderExamples = {
                youtube: 'https://www.youtube.com/watch?v=...',
                facebook: 'https://www.facebook.com/username/videos/...',
                tiktok: 'https://www.tiktok.com/@username/video/...',
                other: 'https://example.com/video.mp4'
            };

            function updateUrlExample(type) {
                if (type && urlExamples[type]) {
                    urlExample.innerHTML = `<strong>ตัวอย่าง URL:</strong> ${urlExamples[type]}`;
                    urlExample.style.display = 'block';
                } else {
                    urlExample.innerHTML = '<strong>ตัวอย่าง:</strong> กรุณาเลือกประเภทวิดีโอก่อน';
                    urlExample.style.display = 'block';
                }
                updatePlaceholder(type);
            }

            function updatePlaceholder(type) {
                if (type && placeholderExamples[type]) {
                    videoUrlInput.placeholder = placeholderExamples[type];
                } else {
                    videoUrlInput.placeholder = 'โปรดระบุ URL วิดีโอ';
                }
            }

            videoTypeSelect.addEventListener('change', function() {
                updateUrlExample(this.value);
            });

            const initialType = videoTypeSelect.value;
            if (initialType) {
                updateUrlExample(initialType);
            } else {
                updatePlaceholder(null);
            }

            previewBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const videoType = videoTypeSelect.value;
                const videoUrl = videoUrlInput.value;

                if (!videoType || !videoUrl) {
                    alert('กรุณาเลือกประเภทวิดีโอและกรอก URL');
                    return;
                }

                const embedHtml = generateEmbedHtml(videoUrl, videoType);
                if (embedHtml) {
                    videoEmbed.innerHTML = embedHtml;
                    videoPreview.classList.add('active');
                    videoPreview.scrollIntoView({
                        behavior: 'smooth',
                        block: 'nearest'
                    });
                } else {
                    alert('ไม่สามารถสร้างตัวอย่างวิดีโอได้ กรุณาตรวจสอบ URL');
                }
            });

            function generateEmbedHtml(url, type) {
                try {
                    switch (type) {
                        case 'youtube':
                            return generateYouTubeEmbed(url);
                        case 'facebook':
                            return generateFacebookEmbed(url);
                        case 'tiktok':
                            return generateTikTokEmbed(url);
                        default:
                            return `<video controls style="width: 100%; max-width: 640px; height: 360px; border-radius: 8px;"><source src="${url}"></video>`;
                    }
                } catch (error) {
                    console.error('Error generating embed:', error);
                    return null;
                }
            }

            function generateYouTubeEmbed(url) {
                let videoId = null;

                if (url.includes('youtu.be/')) {
                    const match = url.match(/youtu\.be\/([a-zA-Z0-9_-]+)/);
                    if (match) videoId = match[1];
                } else if (url.includes('youtube.com/watch')) {
                    const match = url.match(/[?&]v=([a-zA-Z0-9_-]+)/);
                    if (match) videoId = match[1];
                } else if (url.includes('youtube.com/shorts/')) {
                    const match = url.match(/shorts\/([a-zA-Z0-9_-]+)/);
                    if (match) videoId = match[1];
                }

                if (videoId) {
                    return `<iframe width="640" height="360" src="https://www.youtube.com/embed/${videoId}" 
                        frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen></iframe>`;
                }
                return null;
            }

            function generateFacebookEmbed(url) {
                const encodedUrl = encodeURIComponent(url);
                return `<iframe src="https://www.facebook.com/plugins/video.php?href=${encodedUrl}&show_text=false&width=734" 
                    width="640" height="360" style="border:none;overflow:hidden" 
                    scrolling="no" frameborder="0" allowfullscreen="true" 
                    allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" 
                    allowFullScreen="true"></iframe>`;
            }

            function generateTikTokEmbed(url) {
                const match = url.match(/\/video\/(\d+)/);
                if (match) {
                    const videoId = match[1];
                    return `<blockquote class="tiktok-embed" cite="${url}" data-video-id="${videoId}" style="max-width: 605px; min-width: 325px;">
                        <section></section>
                    </blockquote>
                    <script async src="https://www.tiktok.com/embed.js"><\/script>`;
                }
                return null;
            }
        });
    </script>
@endsection
