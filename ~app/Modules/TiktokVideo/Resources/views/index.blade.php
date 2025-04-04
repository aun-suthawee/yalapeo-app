@extends('home::layouts.master')

@section('app-content')
  <section class="page-contents">
    <div class="page-header">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-sm-12">
            <h2>วิดีโอ TikTok ทั้งหมด</h2>
          </div>
        </div>
      </div>
    </div>

    <div class="page-detail">
      <div class="container">
        <div class="row justify-content-center py-3 pb-5">
          <div class="col-sm-12 col-md-6">
            <form action="{{ route('tiktokvideo.index') }}" method="GET">
              <div class="input-group">
                <input type="search"
                       name="search"
                       class="form-control"
                       placeholder="ค้นหาตามชื่อวิดีโอ"
                       value="@if (request()->input('search')) {{ request()->input('search') }} @endif"
                       aria-describedby="button-addon2">
                <button class="btn btn-outline-secondary"
                        type="submit"
                        id="button-addon2">
                  <i class="fas fa-search"></i>
                </button>
              </div>
            </form>
          </div>
        </div>

        <div class="row justify-content-center">
          <div class="col-sm-12 col-md-12">
            <div class="row">
              @forelse ($tiktok_videos as $tiktok)
                <div class="col-6 col-md-3 col-sm-6 mb-4">
                  <div class="box-video box-video-all">
                    <div class="tiktok-iframe-container" style="position: relative; width: auto; padding-bottom: 177.77%; height: 0; overflow: hidden;">
                      <iframe src="https://www.tiktok.com/embed/v2/{{ $tiktok->video_id }}"
                          style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"
                          frameborder="0" allowfullscreen scrolling="no" loading="lazy"
                          allow="encrypted-media;">
                      </iframe>
                    </div>
                    <div class="tiktok-info p-2 bg-light">
                      <h6 class="mb-1 video-title fw-bolder my-3 text-truncate" title="{{ $tiktok->title }}">{{ $tiktok->title }}</h6>
                      <p class="small text-muted mb-0">
                        <i class="far fa-calendar-alt"></i> {{ $tiktok->created_at->format('d/m/Y') }}
                      </p>
                    </div>
                  </div>
                </div>
              @empty
                <div class="col-12 text-center py-5">
                  <div class="alert alert-info">
                    <i class="far fa-frown fa-2x mb-3"></i>
                    <h4>ยังไม่มีวิดีโอ TikTok</h4>
                    <p class="mb-0">ขณะนี้ยังไม่มีวิดีโอ TikTok ที่เผยแพร่ โปรดกลับมาเยี่ยมชมในภายหลัง</p>
                  </div>
                </div>
              @endforelse
            </div>
            {{ $tiktok_videos->links() }}
          </div>
        </div>
      </div>
    </div>
  </section>

  <style>
    .box-video-all {
      display: block;
      margin-bottom: 15px;
      transition: all 0.3s ease;
      height: 100%;
      overflow: hidden;
      text-decoration: none;
      color: inherit;
    }
    
    .box-video-all:hover {
      transform: translateY(-5px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .tiktok-iframe-container {
      background-color: #f8f9fa;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    
    .tiktok-info {
      border-top: 1px solid #e9ecef;
    }
    
    .video-title {
      color: #212529;
      font-size: 1rem;
      line-height: 1.4;
      margin-top: 0.5rem;
    }
  </style>
@endsection
