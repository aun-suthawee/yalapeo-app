@extends('sandbox::layouts.master')

@section('title', $gallery->title . ' - ' . $school->name)

@section('stylesheet-content')
    <link rel="stylesheet" href="{{ asset('assets/common/css/school-gallery.css') }}">
@endsection

@section('content')
    <div class="container-fluid py-4">

        <!-- Header Section -->
        <div class="container">
            <div class="page-header mb-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h1 class="h2 mb-2">
                            <i class="fas fa-images text-primary"></i>
                            {{ $gallery->title }}
                        </h1>
                        <p class="text-muted mb-0">{{ $school->name }}</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('sandbox.schools.galleries.index', $school->id) }}"
                            class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> กลับสู่รายการ
                        </a>
                        <a href="{{ $gallery->folder_url }}" target="_blank" class="btn btn-outline-primary"
                            title="เปิดใน Google Drive">
                            <i class="fab fa-google-drive"></i> เปิดใน Drive
                        </a>
                        @auth
                            <a href="{{ route('sandbox.schools.galleries.edit', [$school->id, $gallery->id]) }}"
                                class="btn btn-outline-secondary">
                                <i class="fas fa-edit"></i> แก้ไข
                            </a>

                            <form action="{{ route('sandbox.schools.galleries.destroy', [$school->id, $gallery->id]) }}"
                                method="POST" class="d-inline" onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบคลังภาพนี้?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> ลบ
                                </button>
                            </form>
                        @endauth
                    </div>
                </div>

                @if ($gallery->description)
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        {{ $gallery->description }}
                    </div>
                @endif

                @if (!$gallery->is_active)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        คลังภาพนี้ไม่ได้เปิดให้แสดง
                    </div>
                @endif
            </div>
        </div>

        <!-- Gallery Viewer -->
        <div class="gallery-viewer-container">
            <div class="gallery-viewer-wrapper">
                <div class="loading-overlay" id="galleryLoading">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">กำลังโหลดภาพ...</p>
                </div>

                <!-- Gallery Info -->
                <div class="container mt-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <small class="text-muted d-block">โรงเรียน</small>
                                    <strong>{{ $school->name }}</strong>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted d-block">สร้างเมื่อ</small>
                                    <strong>{{ $gallery->created_at->format('d/m/Y H:i น.') }}</strong>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted d-block">แก้ไขล่าสุด</small>
                                    <strong>{{ $gallery->updated_at->format('d/m/Y H:i น.') }}</strong>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted d-block">สถานะ</small>
                                    @if ($gallery->is_active)
                                        <span class="badge bg-success">เปิดแสดง</span>
                                    @else
                                        <span class="badge bg-secondary">ไม่แสดง</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <iframe src="{{ $gallery->embed_url }}" class="gallery-iframe" frameborder="0" allow="autoplay"
                    onload="document.getElementById('galleryLoading').style.display='none'">
                </iframe>
            </div>
        </div>


    </div>
@endsection

@section('script-content')
    <script src="{{ asset('assets/common/js/school-gallery.js') }}"></script>
@endsection
