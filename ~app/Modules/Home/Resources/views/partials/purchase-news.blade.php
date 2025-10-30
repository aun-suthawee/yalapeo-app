<section id="purchase-news" class="purchase-news-section">
    <div class="container py-5">
        <div class="row">
            <div class="col-md-6">
                <h2 class="title text-center">
                    <span class="text-highlight">ข่าวจัดซื้อ</span>จัดจ้าง
                </h2>

                <ul class="news-list">
                    @foreach ($news_purchase as $index => $value)
                        <li class="news-item">
                            <a href="{{ $value->publish_url }}" target="{{ $value->target }}" class="news-link text-break">
                                <i class="typcn typcn-news me-1"></i>
                                {{ $value->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
                <div class="text-end">
                    <a href="{{ route('news.index') }}/?type=5" class="btn btn-danger mt-3 view-all-btn">
                        ดูทั้งหมด
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <h2 class="title text-center">
                    <span class="text-highlight">สรุปผล</span>จัดซื้อจัดจ้างรายเดือน
                </h2>

                <ul class="news-list">
                    @foreach ($news_purchase_summary as $index => $value)
                        <li class="news-item">
                            <a href="{{ $value->publish_url }}" target="{{ $value->target }}" class="news-link text-break">
                                <i class="typcn typcn-news me-1"></i>
                                {{ $value->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
                <div class="text-end">
                    <a href="{{ route('news.index') }}/?type=9" class="btn btn-danger mt-3 view-all-btn">
                        ดูทั้งหมด
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>