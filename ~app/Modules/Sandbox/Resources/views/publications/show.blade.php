@extends('sandbox::layouts.master')

@section('title', $publication->title)

@section('stylesheet-content')
    <link rel="stylesheet" href="{{ asset('assets/common/css/publications.css') }}">
@endsection

@section('content')
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="container">
            <div class="mb-3">
                <a href="{{ route('sandbox.publications.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> กลับสู่รายการเอกสาร
                </a>
            </div>

            <div class="page-header mb-4">
                <h1 class="h2 mb-2">
                    <i class="fas fa-file-pdf text-danger"></i>
                    {{ $publication->title }}
                </h1>
                @if($publication->description)
                    <p class="text-muted mb-0">{{ $publication->description }}</p>
                @endif
            </div>
        </div>

        <!-- PDF Viewer -->
        <div class="pdf-viewer-container">
            <div class="pdf-viewer-wrapper">
                <div class="loading-overlay" id="pdfLoading">
                    <div class="spinner-border text-danger" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">กำลังโหลดเอกสาร...</p>
                </div>
                
                <iframe 
                    src="{{ route('sandbox.publications.pdf', $publication->pdf_path) }}#toolbar=1&navpanes=1&scrollbar=1" 
                    class="pdf-iframe"
                    frameborder="0"
                    onload="document.getElementById('pdfLoading').style.display='none'">
                </iframe>
            </div>
        </div>

        <!-- Document Info & Actions -->
        <div class="container mt-4">
            <!-- Document Info Card -->
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <small class="text-muted d-block">ชื่อไฟล์</small>
                            <strong>{{ $publication->pdf_filename }}</strong>
                        </div>
                        <div class="col-md-2">
                            <small class="text-muted d-block">ขนาดไฟล์</small>
                            <strong>{{ $publication->formatted_file_size }}</strong>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block">เผยแพร่เมื่อ</small>
                            <strong>{{ $publication->created_at->format('d/m/Y H:i น.') }}</strong>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">แก้ไขล่าสุด</small>
                            <strong>{{ $publication->updated_at->format('d/m/Y H:i น.') }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons Card -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-cog"></i> การจัดการ
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Navigation Actions -->
                        <div class="col-md-4">
                            <h6 class="text-muted mb-2">
                                <i class="fas fa-bars"></i> การนำทาง
                            </h6>
                            <div class="d-grid gap-2">
                                <a href="{{ route('sandbox.publications.index') }}" 
                                   class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left"></i> กลับสู่รายการ
                                </a>
                                <a href="{{ route('sandbox.dashboard') }}" 
                                   class="btn btn-outline-secondary">
                                    <i class="fas fa-home"></i> กลับสู่หน้าหลัก
                                </a>
                            </div>
                        </div>

                        <!-- External Actions -->
                        <div class="col-md-4">
                            <h6 class="text-muted mb-2">
                                <i class="fas fa-external-link-alt"></i> เปิดภายนอก
                            </h6>
                            <div class="d-grid gap-2">
                                <a href="{{ $publication->pdf_url }}" 
                                   target="_blank" 
                                   class="btn btn-danger">
                                    <i class="fas fa-file-pdf"></i> เปิด PDF ในแท็บใหม่
                                </a>
                                <a href="{{ $publication->pdf_url }}" 
                                   download="{{ $publication->title }}.pdf"
                                   class="btn btn-success">
                                    <i class="fas fa-download"></i> ดาวน์โหลดเอกสาร
                                </a>
                            </div>
                        </div>

                        <!-- Management Actions -->
                        @auth
                        <div class="col-md-4">
                            <h6 class="text-muted mb-2">
                                <i class="fas fa-tools"></i> จัดการเอกสาร
                            </h6>
                            <div class="d-grid gap-2">
                                <a href="{{ route('sandbox.publications.edit', $publication->id) }}" 
                                   class="btn btn-warning">
                                    <i class="fas fa-edit"></i> แก้ไขเอกสาร
                                </a>
                                <form action="{{ route('sandbox.publications.destroy', $publication->id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบเอกสารนี้?\n\nการลบจะไม่สามารถกู้คืนได้');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="fas fa-trash"></i> ลบเอกสาร
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
