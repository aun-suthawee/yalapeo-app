<section id="book" class="book-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h2 class="title text-center mb-4" data-aos="fade-up">
                    <span class="text-highlight">หนังสือ</span>อิเล็กทรอนิกส์
                </h2>
                <p class="text-center text-muted mb-5" data-aos="fade-up" data-aos-delay="100">
                    รวบรวมหนังสืออิเล็กทรอนิกส์ที่น่าสนใจเพื่อการเรียนรู้ตลอดการเดินทางชีวิต
                </p>
            </div>
        </div>

        <div class="row persuade-read-box" data-aos="fade-up" data-aos-delay="200">
            <div class="col-md-12">
                <div class="owl-persuade owl-carousel owl-theme">
                    @forelse ($books as $index => $value)
                        <div class="item book-card" data-aos="fade-up" data-aos-delay="{{ 200 + ($index * 50) }}">
                            <div class="book-item">
                                <div class="book-cover-wrapper">
                                    <a href="{{ $value->url }}" target="{{ $value->target ?? '_blank' }}" class="book-link">
                                        <img src="{{ $value->cover }}" class="img-fluid book-cover" alt="{{ $value->title ?? 'หนังสืออิเล็กทรอนิกส์' }}" />
                                        <div class="book-overlay">
                                            <span class="btn-read">อ่านเลย</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="book-info mt-3">
                                    <h5 class="book-title">{{ $value->title ?? 'หนังสืออิเล็กทรอนิกส์' }}</h5>
                                    @if(isset($value->description))
                                        <p class="book-desc">{{ Str::limit($value->description, 100) }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5" data-aos="fade-up">
                            <div class="empty-state">
                                <i class="fas fa-book-open fa-3x mb-3 text-muted"></i>
                                <h5>ยังไม่มีหนังสืออิเล็กทรอนิกส์</h5>
                                <p class="text-muted">หนังสืออิเล็กทรอนิกส์กำลังอยู่ในระหว่างการจัดเตรียม</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="text-center mt-4" data-aos="fade-up" data-aos-delay="300">
            <a href="{{ url('/book') }}" class="btn btn-outline-primary btn-view-all">
                ดูหนังสือทั้งหมด <i class="fas fa-chevron-right ms-1"></i>
            </a>
        </div>
    </div>
</section>