@extends('sandbox::layouts.master')

@section('title', 'เอกสารเผยแพร่')

@section('stylesheet-content')
    <link rel="stylesheet" href="{{ asset('assets/common/css/publications.css') }}">
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="container">
            <!-- Header -->
            <div class="page-header mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h2 mb-2">
                            <i class="fas fa-file-pdf text-danger"></i>
                            เอกสารเผยแพร่
                        </h1>
                        <p class="text-muted mb-0">เอกสาร PDF สำหรับเผยแพร่ทั่วไป</p>
                    </div>
                    @auth
                        <div>
                            <a href="{{ route('sandbox.publications.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> เพิ่มเอกสารใหม่
                            </a>
                        </div>
                    @endauth
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Publications List -->
            @if($publications->count() > 0)
                <div class="publications-list">
                    @foreach($publications as $publication)
                        <div class="publication-row-wrapper">
                            <a href="{{ route('sandbox.publications.show', $publication->id) }}" 
                               class="publication-row-card"
                               title="ดูเอกสาร: {{ $publication->title }}">
                                <div class="publication-row-icon-large">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <div class="publication-row-details">
                                    <div class="publication-row-header">
                                        <h4 class="publication-row-title-large">
                                            {{ $publication->title }}
                                        </h4>
                                    </div>
                                    
                                    @if($publication->description)
                                        <p class="publication-row-description">{{ Str::limit($publication->description, 200) }}</p>
                                    @endif

                                    <div class="publication-row-meta">
                                        <span class="meta-badge">
                                            <i class="far fa-calendar"></i>
                                            {{ $publication->created_at->format('d/m/Y H:i น.') }}
                                        </span>
                                        <span class="meta-badge">
                                            <i class="far fa-file"></i>
                                            {{ $publication->formatted_file_size }}
                                        </span>
                                        <span class="meta-badge">
                                            <i class="far fa-clock"></i>
                                            อัปเดต {{ $publication->updated_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                            @auth
                                <div class="publication-row-admin-actions">
                                    <a href="{{ route('sandbox.publications.edit', $publication->id) }}" 
                                       class="btn btn-sm btn-warning"
                                       onclick="event.stopPropagation();">
                                        <i class="fas fa-edit"></i> แก้ไข
                                    </a>
                                    <form action="{{ route('sandbox.publications.destroy', $publication->id) }}" 
                                          method="POST" 
                                          style="display: inline;"
                                          onsubmit="event.stopPropagation(); return confirm('คุณแน่ใจหรือไม่ที่จะลบเอกสารนี้?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> ลบ
                                        </button>
                                    </form>
                                </div>
                            @endauth
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $publications->links() }}
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <h3>ยังไม่มีเอกสารเผยแพร่</h3>
                    <p>{{ auth()->check() ? 'เริ่มต้นด้วยการเพิ่มเอกสาร PDF แรก' : 'ยังไม่มีเอกสารในระบบ' }}</p>
                    @auth
                        <a href="{{ route('sandbox.publications.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> เพิ่มเอกสารใหม่
                        </a>
                    @endauth
                </div>
            @endif
        </div>
    </div>
@endsection

@section('script-content')
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
@endsection
