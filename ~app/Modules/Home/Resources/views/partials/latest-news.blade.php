<section class="lasted-news" data-aos="fade-up">
    <div class="container-fluid px-md-5 py-5">
        <div class="row">
            <div class="col-md-8">
                <h2 class="title text-center" data-aos="fade-up">
                    <span class="text-highlight">ข่าวกิจกรรม</span>ศธจ.ยะลา
                </h2>
                <div class="row justify-content-center mt-0 mt-md-5">
                    <div class="col-md-6" data-aos="fade-right" data-aos-delay="100">
                        <div class="card overflow-hidden news-card main-news-card">
                            <img src="{{ $lasted_news[0]->cover }}" class="img-fluid news-image" alt="{{ Request::getHost() }}">
                            <div class="card-body">
                                <h5 class="card-title focus-title cut-text-2">
                                    <a href="{{ $lasted_news[0]->publish_url }}"
                                        target="{{ $lasted_news[0]->target }}">
                                        {{ $lasted_news[0]->title }}
                                    </a>
                                </h5>
                                <p class="card-detail focus-detail">
                                    {{ $lasted_news[0]->description }}
                                </p>
                                <p class="card-text">
                                    <i class="feather feather-calendar hor-icon"></i>
                                    {{ $lasted_news[0]->date_publish_format_1 }} | อ่าน {{ $lasted_news[0]->view }}
                                    ครั้ง
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6" data-aos="fade-left" data-aos-delay="200">
                        @for ($n = 1; $n < count($lasted_news); $n++)
                            <div class="row mb-4" data-aos="fade-left" data-aos-delay="{{ 200 + ($n * 50) }}">
                                <div class="col-md-12">
                                    <div class="card news-card secondary-news-card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <img src="{{ $lasted_news[$n]->cover }}" class="img-fluid news-image"
                                                        alt="{{ Request::getHost() }}">
                                                </div>
                                                <div class="col-md-7 d-flex justify-content-between flex-column">
                                                    <h5 class="card-title cut-text-2">
                                                        <a href="{{ $lasted_news[$n]->publish_url }}"
                                                            target="{{ $lasted_news[$n]->target }}">
                                                            {{ $lasted_news[$n]->title }}
                                                        </a>
                                                    </h5>
                                                    <p class="card-detail">
                                                        {{ $lasted_news[$n]->description }}
                                                    </p>
                                                    <p class="card-text">
                                                        <i class="feather feather-calendar hor-icon"></i>
                                                        {{ $lasted_news[$n]->date_publish_format_1 }} | อ่าน
                                                        {{ $lasted_news[$n]->view }} ครั้ง
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endfor

                        <div class="text-end" data-aos="fade-up" data-aos-delay="300">
                            <a href="{{ route('news.index') }}/?type=7" class="btn btn-danger view-all-btn">
                                ดูทั้งหมด
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            @if ($box->detail_1 != '')
                <div class="col-md-4 mt-5 mt-md-0" data-aos="fade-left" data-aos-delay="250">
                    <div class="box-content h-100 d-flex flex-column justify-content-center p-3">
                        {!! $box->detail_1 !!}
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>