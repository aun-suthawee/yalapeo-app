@extends('sandbox::layouts.master')

@section('title', 'คลังภาพกิจกรรม - ' . $school->name)

@section('stylesheet-content')
    <link rel="stylesheet" href="{{ asset('assets/common/css/school-gallery.css') }}">
@endsection

@section('content')
    <div class="container py-4">
        <!-- Header Section -->
        <div class="page-header mb-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h1 class="h2 mb-2">
                        <i class="fas fa-images text-primary"></i>
                        คลังภาพกิจกรรม
                    </h1>
                    <p class="text-muted mb-0">{{ $school->name }}</p>
                </div>
                @auth
                    <a href="{{ route('sandbox.schools.galleries.create', $school->id) }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> เพิ่มคลังภาพใหม่
                    </a>
                @endauth
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Galleries Grid -->
        @if($galleries->count() > 0)
            <div class="row g-4">
                @foreach($galleries as $gallery)
                    <div class="col-md-6 col-lg-4">
                        <div class="gallery-card {{ !$gallery->is_active ? 'inactive' : '' }}">
                            <div class="gallery-card-body">
                                <div class="gallery-header">
                                    <h3 class="gallery-title">
                                        <a href="{{ route('sandbox.schools.galleries.show', [$school->id, $gallery->id]) }}">
                                            {{ $gallery->title }}
                                        </a>
                                    </h3>
                                    @if(!$gallery->is_active)
                                        <span class="badge bg-secondary">ไม่แสดง</span>
                                    @else
                                        <span class="badge bg-success">แสดง</span>
                                    @endif
                                </div>

                                @if($gallery->description)
                                    <p class="gallery-description">{{ Str::limit($gallery->description, 100) }}</p>
                                @endif

                                <div class="gallery-meta">
                                    <small class="text-muted">
                                        <i class="far fa-calendar"></i>
                                        {{ $gallery->created_at->format('d/m/Y') }}
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-sort-numeric-down"></i>
                                        ลำดับ: {{ $gallery->display_order }}
                                    </small>
                                </div>

                                <div class="gallery-actions">
                                    <a href="{{ route('sandbox.schools.galleries.show', [$school->id, $gallery->id]) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i> ดูภาพ
                                    </a>
                                    @auth
                                        <a href="{{ route('sandbox.schools.galleries.edit', [$school->id, $gallery->id]) }}" 
                                           class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-edit"></i> แก้ไข
                                        </a>
                                        <form action="{{ route('sandbox.schools.galleries.destroy', [$school->id, $gallery->id]) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบคลังภาพนี้?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i> ลบ
                                            </button>
                                        </form>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $galleries->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-images"></i>
                </div>
                <h3>ยังไม่มีคลังภาพกิจกรรม</h3>
                <p class="text-muted">เริ่มต้นสร้างคลังภาพกิจกรรมจาก Google Drive ของโรงเรียน</p>
                @auth
                    <a href="{{ route('sandbox.schools.galleries.create', $school->id) }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> เพิ่มคลังภาพแรก
                    </a>
                @endauth
            </div>
        @endif
    </div>
@endsection
