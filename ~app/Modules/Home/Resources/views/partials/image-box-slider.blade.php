<section class="image-box-slider py-5">
    <div class="container-fluid px-md-5">
        <div class="slider-container position-relative overflow-hidden">
            @if (count($image_boxsliders) > 3)
                <button class="slider-nav-btn slider-prev-btn" id="prevBtn">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="slider-nav-btn slider-next-btn" id="nextBtn">
                    <i class="fas fa-chevron-right"></i>
                </button>
            @endif

            <div class="slider-outer-wrapper overflow-hidden">
                <div class="slider-wrapper d-flex {{ count($image_boxsliders) < 2 ? 'justify-content-center' : '' }}"
                    id="imageBoxSlider">
                    @foreach ($image_boxsliders as $slider)
                        <div class="slider-item flex-shrink-0"
                            style="width: calc(100% / {{ min(count($image_boxsliders), 3) }}); padding: 0 10px; {{ count($image_boxsliders) < 2 ? 'max-width: 800px;' : '' }}">
                            <a href="{{ $slider->url ? $slider->url : route('imageboxslider.show', $slider->slug) }}"
                                target="{{ $slider->target }}" class="d-block h-100">
                                <div class="image-container position-relative h-100">

                                    <img src="{{ $slider->image_url }}" alt="{{ $slider->title }}"
                                        style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">


                                    @if ($slider->hasPdf())
                                        <div class="pdf-badge"
                                            style="position: absolute; top: 10px; right: 10px; background-color: #dc3545; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                                            <i class="fas fa-file-pdf"></i> PDF
                                        </div>
                                    @endif
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('imageboxslider.index') }}" class="btn btn-primary btn-view-all px-4 py-2">
                <i class="fas fa-photo-video mr-2"></i>ดูรูปภาพและเอกสารทั้งหมด
                <span class="badge badge-light ml-2">({{ count($image_boxsliders) }})</span>
            </a>
        </div>
    </div>
</section>

<style>
    .slider-container {
        position: relative;
        padding: 0;
    }

    .slider-outer-wrapper {
        width: 100%;
        position: relative;
        overflow: hidden;
    }

    .slider-nav-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background-color: rgba(255, 255, 255, 0.8);
        color: #0056b3;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        z-index: 10;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }

    .slider-nav-btn:hover {
        background-color: #0056b3;
        color: white;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }

    .slider-prev-btn {
        left: 10px;
    }

    .slider-next-btn {
        right: 10px;
    }

    /* ซ่อนปุ่มบนมือถือ */
    @media (max-width: 576px) {
        .slider-nav-btn {
            width: 32px;
            height: 32px;
            font-size: 0.8rem;
        }
    }

    /* สไตล์สำหรับรูปภาพและอื่นๆ */
    .slider-wrapper {
        transition: transform 0.6s ease-in-out;
    }

    .slider-item img {
        transition: transform 0.3s ease;
    }

    .slider-item:hover img {
        transform: scale(1.03);
    }

    .btn-view-all {
        font-weight: 500;
        border-radius: 30px;
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.2);
        transition: all 0.3s ease;
    }

    .btn-view-all:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(0, 123, 255, 0.3);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sliderWrapper = document.getElementById('imageBoxSlider');
        const totalItems = sliderWrapper ? sliderWrapper.children.length : 0;
        const visibleCount = Math.min(totalItems, 3);
        let currentIndex = 0;
        let isAnimating = false;
        let autoSlideInterval;

        if (!sliderWrapper || totalItems <= 3) {
            return;
        }

        function slide(direction = 1) {
            if (isAnimating) return;
            isAnimating = true;

            currentIndex += direction;
            
            if (direction > 0) {
                // เลื่อนไปข้างหน้า
                if (currentIndex >= totalItems - visibleCount + 1) {
                    currentIndex = 0;
                }
            } else {
                // เลื่อนไปข้างหลัง
                if (currentIndex < 0) {
                    currentIndex = totalItems - visibleCount;
                }
            }

            updateSliderPosition();
            setTimeout(() => {
                isAnimating = false;
            }, 600);
        }

        function updateSliderPosition() {
            const itemWidth = sliderWrapper.children[0].offsetWidth;
            sliderWrapper.style.transform = `translateX(-${itemWidth * currentIndex}px)`;
        }

        function startAutoSlide() {
            autoSlideInterval = setInterval(() => {
                slide(1);
            }, 5000);
        }

        function stopAutoSlide() {
            clearInterval(autoSlideInterval);
        }

        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');

        if (prevBtn && nextBtn) {
            prevBtn.addEventListener('click', function() {
                slide(-1);
                stopAutoSlide();
                startAutoSlide();
            });

            nextBtn.addEventListener('click', function() {
                slide(1);
                stopAutoSlide();
                startAutoSlide();
            });
        }

        startAutoSlide();

        sliderWrapper.addEventListener('mouseenter', stopAutoSlide);
        sliderWrapper.addEventListener('mouseleave', startAutoSlide);

        window.addEventListener('resize', function() {
            updateSliderPosition();
        });
    });
</script>
