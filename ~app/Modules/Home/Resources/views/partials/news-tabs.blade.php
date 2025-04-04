<section id="news-publish">
    <div class="container py-5">
        <div class="row">
            <div class="col-md-12">
                <h2 class="title text-center" data-aos="fade-up">
                    <span class="text-highlight">ข่าวสาร</span>ล่าสุด
                </h2>

                <ul class="nav nav-tabs justify-content-center news-nav-tabs pb-5" id="newsTab" data-aos="fade-up" data-aos-delay="100">
                    @foreach ($news as $index => $value)
                        <li class="nav-item">
                            <a class="nav-link @if ($index == 0) active @endif" data-bs-toggle="tab"
                                href="#tab{{ $index }}">
                                {{ $value['type_name'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
                <div class="tab-content" id="newsTabContent" data-aos="fade-up" data-aos-delay="200">
                    @foreach ($news as $index => $list)
                        <div class="tab-pane fade show @if ($index == 0) active @endif container"
                            id="tab{{ $index }}" aria-labelledby="tab-{{ $index }}">
                            <div class="row g-4">
                                @foreach ($list['lists'] as $key => $value)
                                    <div
                                        class="col-12 col-md-3 @if ($key >= 3) d-none d-md-block @endif">
                                        <div class="card card-news overflow-hidden">
                                            <img src="{{ $value->cover }}" class="img-height img-bd-5"
                                                alt="{{ Request::getHost() }}">
                                            <div class="card-body">
                                                <h5 class="card-title cut-text-2">
                                                    <a href="{{ $value->publish_url }}"
                                                        target="{{ $value->target }}">
                                                        {{ $value->title }}
                                                    </a>
                                                </h5>
                                                <p class="card-detail">
                                                    {{ $value->description }}
                                                </p>
                                                <p class="card-text">
                                                    <i class="feather feather-calendar hor-icon"></i>
                                                    {{ $value->date_publish_format_1 }} | อ่าน {{ $value->view }}
                                                    ครั้ง
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="row">
                                <div class="col-md-12 mt-5 text-center"  data-aos="fade-up" data-aos-delay="300">
                                    <a href="{{ route('news.index') }}/?type=7" class="btn btn-danger">
                                        ดูทั้งหมด
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>