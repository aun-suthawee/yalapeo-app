@extends('home::layouts.master')

@section('app-content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4">รูปภาพสไลด์</h1>
            
            <!-- ส่วนค้นหา -->
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('imageboxslider.index') }}" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="ค้นหารูปภาพ..." name="search" value="{{ request()->get('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fa fa-search"></i> ค้นหา
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- แสดงรูปภาพสไลด์ -->
            <div class="row">
                @forelse($image_sliders as $slider)
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card h-100">
                        <div class="ratio ratio-1x1">
                            <img src="{{ $slider->image_url }}" class="card-img-top" alt="{{ $slider->title }}" style="object-fit: cover; height: 100%;">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ Str::limit($slider->title, 50) }}</h5>
                            <p class="card-text small text-muted">{{ Str::limit($slider->description, 100) }}</p>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('imageboxslider.show', $slider->slug) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> รายละเอียด
                                </a>
                                
                                @if($slider->pdf_file)
                                <span class="badge badge-danger">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-info">
                        ไม่พบรูปภาพสไลด์
                    </div>
                </div>
                @endforelse
            </div>
            
            <!-- แสดง pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $image_sliders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .ratio {
        position: relative;
        width: 100%;
    }
    .ratio-1x1 {
        padding-top: 100%;
    }
    .ratio > * {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
</style>
@endpush
