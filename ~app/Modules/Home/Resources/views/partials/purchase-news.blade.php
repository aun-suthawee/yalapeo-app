<section class="purchase-news-section" data-aos="fade-up">
    <div class="container py-5">
        <div class="row">
            <div class="col-md-6" data-aos="fade-right" data-aos-delay="100">
                <h2 class="title text-center">
                    <span class="text-highlight">ข่าวจัดซื้อ</span>จัดจ้าง
                </h2>

                <ul class="news-list">
                    @foreach ($news_purchase as $index => $value)
                        <li class="news-item" data-aos="fade-up" data-aos-delay="{{ 150 + ($index * 50) }}">
                            <a href="{{ $value->publish_url }}" target="{{ $value->target }}" class="news-link">
                                <i class="typcn typcn-news me-1"></i>
                                {{ $value->title }}
                            </a>
                        </li>
                    @endforeach
                    <div class="text-end" data-aos="fade-up" data-aos-delay="300">
                        <a href="{{ route('news.index') }}/?type=5" class="btn btn-danger mt-3 view-all-btn">
                            ดูทั้งหมด
                        </a>
                    </div>
                </ul>
            </div>
            <div class="col-md-6" data-aos="fade-left" data-aos-delay="200">
                <h2 class="title text-center">
                    <span class="text-highlight">สรุปผล</span>จัดซื้อจัดจ้างรายเดือน
                </h2>

                <ul class="news-list">
                    @foreach ($news_purchase_summary as $index => $value)
                        <li class="news-item" data-aos="fade-up" data-aos-delay="{{ 150 + ($index * 50) }}">
                            <a href="{{ $value->publish_url }}" target="{{ $value->target }}" class="news-link">
                                <i class="typcn typcn-news me-1"></i>
                                {{ $value->title }}
                            </a>
                        </li>
                    @endforeach
                    <div class="text-end" data-aos="fade-up" data-aos-delay="300">
                        <a href="{{ route('news.index') }}/?type=9" class="btn btn-danger mt-3 view-all-btn">
                            ดูทั้งหมด
                        </a>
                    </div>
                </ul>
            </div>
        </div>
    </div>
</section>