<section id="assessment" class="assessment-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8" data-aos="fade-up">
                <h2 class="title text-center mb-4">
                    <span class="text-highlight">แบบ</span>ประเมิน
                </h2>
                <p class="text-center text-muted mb-5" data-aos="fade-up" data-aos-delay="100">
                    เลือกแบบประเมินต่างๆ เพื่อช่วยให้เราปรับปรุงและพัฒนาบริการให้ดียิ่งขึ้น
                </p>
            </div>
        </div>

        <div class="row justify-content-center">
            @foreach ($assessments as $index => $value)
                <div class="col-6 col-sm-6 col-md-4 col-lg-2 mb-4" data-aos="fade-up" data-aos-delay="{{ 150 + ($index * 50) }}">
                    <div class="assessment-card">
                        <a href="{{ $value['url'] }}" class="assessment-link">
                            <div class="assessment-icon-wrapper">
                                <img src="{{ $value['cover'] }}" class="assessment-icon" alt="{!! strip_tags($value['title']) !!}">
                                <div class="assessment-hover-effect"></div>
                            </div>
                            <h5 class="assessment-title">
                                {!! $value['title'] !!}
                            </h5>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>