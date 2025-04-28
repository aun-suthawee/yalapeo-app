<section class="diff diff-box4 pt-sm-auto bg-light videos-section">
    <div class="container">
        <h1 class="text-uppercase mb-5 text-center">
            <span class="text-highlight">วิดิโอ</span>ล่าสุด
        </h1>
        <div class="row">
            <div class="col-md-8 col-sm-12 mb-sm-0 box-large mb-3">
                <a href="{{ route('video.show', $videos[0]->slug) }}" class="video-card featured">
                    <div class="video-wrapper">
                        {!! $videos[0]->output !!}
                        <div class="video-overlay">
                            <span class="play-button"><i class="fas fa-play"></i></span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-12 mb-sm-0 mb-3">
                @foreach ($videos as $index => $value)
                    @if ($index >= 1)
                        <div class="row mb-3">
                            <div class="col-md-12 col-sm-12 mb-sm-0 box-small mb-3">
                                <a href="{{ route('video.show', $value->slug) }}" class="video-card">
                                    <div class="video-wrapper">
                                        {!! $value->output !!}
                                        <div class="video-overlay">
                                            <span class="play-button"><i class="fas fa-play"></i></span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('video.index') }}" class="btn btn-secondary animated-button">
                วิดิโอทั้งหมด <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>