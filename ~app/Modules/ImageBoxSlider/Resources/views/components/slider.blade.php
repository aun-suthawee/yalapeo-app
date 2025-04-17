@php
    // Get latest active image box sliders
    use Modules\ImageBoxSlider\Entities\ImageBoxSliderModel;
    $sliders = ImageBoxSliderModel::where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->limit(8)
                ->get();
@endphp

@if($sliders->count() > 0)
<div class="image-box-slider-container my-4">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="section-title mb-3">รูปภาพและเอกสาร</h3>
            </div>
        </div>
        
        <div class="image-box-slider-wrapper">
            <div class="swiper-container image-box-swiper">
                <div class="swiper-wrapper">
                    @foreach($sliders as $slider)
                    <div class="swiper-slide">
                        <div class="image-box-card">
                            <a href="{{ route('imageboxslider.show', $slider->slug) }}" class="image-box-link">
                                <div class="image-box-img-wrapper">
                                    <img src="{{ $slider->image_url }}" alt="{{ $slider->title }}" class="image-box-img">
                                    @if($slider->pdf_file)
                                    <span class="pdf-badge">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </span>
                                    @endif
                                </div>
                                <div class="image-box-content">
                                    <h5 class="image-box-title">{{ Str::limit($slider->title, 30) }}</h5>
                                    <p class="image-box-desc">{{ Str::limit($slider->description, 60) }}</p>
                                </div>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Navigation buttons -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                
                <!-- Pagination -->
                <div class="swiper-pagination"></div>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-12 text-center">
                <a href="{{ route('imageboxslider.index') }}" class="btn btn-outline-primary">ดูทั้งหมด</a>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .image-box-slider-container {
        padding: 20px 0;
        background-color: #f8f9fa;
    }
    
    .image-box-card {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        height: 100%;
        background-color: #fff;
        transition: transform 0.3s ease;
    }
    
    .image-box-card:hover {
        transform: translateY(-5px);
    }
    
    .image-box-link {
        text-decoration: none;
        color: inherit;
        display: block;
    }
    
    .image-box-img-wrapper {
        position: relative;
        padding-bottom: 100%; /* 1:1 aspect ratio */
        overflow: hidden;
    }
    
    .image-box-img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .image-box-content {
        padding: 15px;
    }
    
    .image-box-title {
        font-size: 16px;
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
    }
    
    .image-box-desc {
        font-size: 14px;
        color: #666;
        margin-bottom: 0;
    }
    
    .pdf-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: #dc3545;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
    }
    
    .swiper-container {
        padding: 10px 5px 40px;
    }
    
    .swiper-slide {
        height: auto;
        padding: 10px;
    }
    
    .swiper-pagination {
        bottom: 0;
    }
    
    .swiper-button-next, .swiper-button-prev {
        color: #007bff;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Swiper for ImageBoxSlider
        new Swiper('.image-box-swiper', {
            slidesPerView: 1,
            spaceBetween: 10,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                // When window width is >= 576px
                576: {
                    slidesPerView: 2,
                    spaceBetween: 20
                },
                // When window width is >= 768px
                768: {
                    slidesPerView: 3,
                    spaceBetween: 20
                },
                // When window width is >= 992px
                992: {
                    slidesPerView: 4,
                    spaceBetween: 20
                }
            }
        });
    });
</script>
@endpush
@endif