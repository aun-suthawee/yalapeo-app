<section class="image-box-slider py-5" data-aos="fade-up">
    <div class="container-fluid px-md-5">
        <div class="slider-container position-relative overflow-hidden">
            @if(count($image_boxsliders) > 3)
                <button class="slider-nav-btn slider-prev-btn" id="prevBtn">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="slider-nav-btn slider-next-btn" id="nextBtn">
                    <i class="fas fa-chevron-right"></i>
                </button>
            @endif
            
            <div class="slider-outer-wrapper overflow-hidden">
                <div class="slider-wrapper d-flex {{ count($image_boxsliders) < 2 ? 'justify-content-center' : '' }}" id="imageBoxSlider">
                    @foreach ($image_boxsliders as $slider)
                        <div class="slider-item flex-shrink-0" style="width: calc(100% / {{ min(count($image_boxsliders), 3) }}); padding: 0 10px; {{ count($image_boxsliders) < 2 ? 'max-width: 800px;' : '' }}">
                            <a href="{{ route('imageboxslider.show', $slider->slug) }}" class="d-block h-100">
                                <div class="image-container position-relative h-100">
                                    <img src="{{ $slider->image_url }}" alt="{{ $slider->title }}"
                                        style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                                    
                                    @if ($slider->hasPdf())
                                        <div class="pdf-badge" style="position: absolute; top: 10px; right: 10px; background-color: #dc3545; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
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

        <div class="text-center mt-4" data-aos="fade-up" data-aos-delay="300">
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
        const totalItems = sliderWrapper.children.length;
        const visibleCount = Math.min(totalItems, 3);
        let currentIndex = 0;
        let isAnimating = false;
        
        // ถ้ามีรูปน้อยกว่าหรือเท่ากับ 3 ไม่ต้องมีการเลื่อนอัตโนมัติ
        if (totalItems <= 3) {
            return;
        }
        
        // สร้างสำเนาของสไลด์เพื่อทำให้เลื่อนแบบไม่มีที่สิ้นสุด
        function setupInfiniteSlider() {
            // สร้างสำเนาของสไลด์ทั้งหมดและเพิ่มต่อท้าย
            const slidesHTMLContent = sliderWrapper.innerHTML;
            sliderWrapper.innerHTML = slidesHTMLContent + slidesHTMLContent;
        }
        
        setupInfiniteSlider();
        
        function slide(direction = 1) {
            if (isAnimating) return;
            isAnimating = true;
            
            const duplicatedTotalItems = totalItems * 2;
            
            if (direction > 0) {
                currentIndex++;
                
                if (currentIndex >= totalItems) {
                    updateSliderPosition();
                    
                    setTimeout(() => {
                        currentIndex = currentIndex - totalItems;
                        sliderWrapper.style.transition = 'none';
                        updateSliderPosition();
                        
                        setTimeout(() => {
                            sliderWrapper.style.transition = 'transform 0.6s ease-in-out';
                            isAnimating = false;
                        }, 10);
                    }, 600);
                } else {
                    updateSliderPosition();
                    setTimeout(() => {
                        isAnimating = false;
                    }, 600);
                }
            } else {
                currentIndex--;
                
                if (currentIndex < 0) {
                    currentIndex = totalItems - 1;
                    updateSliderPosition();
                    setTimeout(() => {
                        isAnimating = false;
                    }, 600);
                } else {
                    updateSliderPosition();
                    setTimeout(() => {
                        isAnimating = false;
                    }, 600);
                }
            }
        }
        
        function updateSliderPosition() {
            sliderWrapper.style.transform = `translateX(-${(100 / visibleCount) * currentIndex}%)`;
        }
        
        // ตั้งค่าปุ่มเลื่อน
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        
        if (prevBtn && nextBtn) {
            prevBtn.addEventListener('click', function() {
                slide(-1);
                clearInterval(slidingInterval);
                slidingInterval = setInterval(() => slide(1), 5000);
            });
            
            nextBtn.addEventListener('click', function() {
                slide(1);
                clearInterval(slidingInterval);
                slidingInterval = setInterval(() => slide(1), 5000);
            });
        }
        
        let slidingInterval = setInterval(() => slide(1), 5000);
        
        sliderWrapper.addEventListener('mouseenter', function() {
            clearInterval(slidingInterval);
        });
        
        // เริ่มการเลื่อนอัตโนมัติอีกครั้งเมื่อเมาส์ออกจาก slider
        sliderWrapper.addEventListener('mouseleave', function() {
            clearInterval(slidingInterval);
            slidingInterval = setInterval(() => slide(1), 5000);
        });
    });
</script>
