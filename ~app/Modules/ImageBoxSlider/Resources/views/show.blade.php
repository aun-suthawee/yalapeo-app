@extends('home::layouts.master')

@section('app-content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('imageboxslider.index') }}">รูปภาพสไลด์</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $slider->title }}</li>
                </ol>
            </nav>
            
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-4 mb-md-0">
                            <!-- รูปภาพ -->
                            <div class="text-center">
                                <img src="{{ $slider->image_url }}" class="img-fluid rounded" alt="{{ $slider->title }}" style="max-width: 100%; max-height: 400px;">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <!-- รายละเอียด -->
                            <h1 class="mb-3">{{ $slider->title }}</h1>
                            
                            <div class="mb-4">
                                {!! nl2br(e($slider->description)) !!}
                            </div>
                            
                            <div class="mb-4">
                                <span class="text-muted">เผยแพร่: {{ $slider->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>
                    
                    @if($slider->hasPdf())
                    <!-- แสดงเอกสาร PDF แบบเดียวกับ News Module -->
                    <div class="mt-4">
                        <p class="m-0 mb-2">เอกสารแนบ</p>
                        <ul>
                            @foreach($slider->pdf_file as $pdf)
                            <li>
                                <i class="fas fa-file-pdf text-danger"></i>
                                <span>{{ $pdf['name'] }}</span>
                            </li>
                            @endforeach
                        </ul>
                        <hr>
                        
                        @foreach($slider->pdf_file as $index => $pdf)
                        <p>
                            <code class="d-block">{{ $pdf['name'] }}</code>
                            <iframe src="{{ asset('storage/image_box_slider/pdf/' . $pdf['name_uploaded']) }}" 
                                    width="100%" height="600px" class="border mb-4"></iframe>
                        </p>
                        @endforeach
                    </div>
                    @endif
                    
                    <div class="mt-4">
                        <a href="{{ route('imageboxslider.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> กลับสู่หน้ารายการ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    iframe {
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        border-radius: 4px;
    }
    
    code {
        display: inline-block;
        padding: 4px 8px;
        background-color: #f8f9fa;
        border-radius: 4px;
        margin-bottom: 10px;
    }
</style>
@endpush