<section class="tiktok-videos py-5 bg-light">
    <div class="container-fluid px-md-5">
        <h2 class="title text-center mb-4">
            <span class="text-highlight">TikTok</span>ล่าสุด
        </h2>

        <div class="tiktok-slider owl-carousel owl-theme">
            @if (isset($tiktok_videos) && count($tiktok_videos) > 0)
                @foreach ($tiktok_videos as $index => $tiktok)
                    @if ($index < 4)
                        <div class="tiktok-slide-item">
                            <div class="tiktok-container">
                                <div class="tiktok-iframe-container"
                                    style="position: relative; width: auto; padding-bottom: 100%; height: auto; overflow: hidden; cursor: pointer;"
                                    onclick="expandTikTok(this)">
                                    <iframe src="https://www.tiktok.com/embed/v2/{{ $tiktok->video_id }}"
                                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"
                                        frameborder="0" allowfullscreen scrolling="no" loading="lazy"
                                        allow="encrypted-media;">
                                    </iframe> 
                                    <div class="tiktok-overlay"
                                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.01); z-index: 1;">
                                    </div>
                                </div>

                                <div class="tiktok-info p-2 bg-light">
                                    <h6 class="mb-1 text-truncate" title="{{ $tiktok->title }}">{{ $tiktok->title }}
                                    </h6>
                                    <p class="small text-muted mb-0">
                                        <i class="far fa-calendar-alt"></i> {{ $tiktok->created_at->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach

                <div class="tiktok-slide-item">
                    <a href="{{ route('tiktokvideo.index') }}" class="tiktok-view-all">
                        <div class="tiktok-view-all-content">
                            <div class="tiktok-thumbnails">
                                <div class="tiktok-thumbnail tiktok-thumbnail-1"></div>
                                <div class="tiktok-thumbnail tiktok-thumbnail-2"></div>
                                <div class="tiktok-thumbnail tiktok-thumbnail-3"></div>
                            </div>
                            <div class="view-all-text">
                                <h5 class="fw-bold">ดูวิดีโอ TikTok ทั้งหมด</h5>
                                <p class="mb-0 text-muted">มีทั้งหมด {{ count($tiktok_videos) }} วิดีโอ</p>
                            </div>
                        </div>
                    </a>
                </div>
            @else
                <div class="col-12 text-center">
                    <p>ยังไม่มีวิดีโอ TikTok</p>
                </div>
            @endif
        </div>
    </div>
</section>