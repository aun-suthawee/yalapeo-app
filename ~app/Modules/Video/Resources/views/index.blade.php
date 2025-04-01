@extends('home::layouts.master')

@section('app-content')
  <section class="page-contents">
    <div class="page-header">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-sm-12">
            <h2>วิดิโอทั้งหมด</h2>
          </div>
        </div>
      </div>
    </div>

    <div class="page-detail">
      <div class="container">
        <div class="row justify-content-center py-3 pb-5">
          <div class="col-sm-12 col-md-6">
            <form action="{{ route('video.index') }}"
                  method="GET">
              <div class="input-group">
                <input type="search"
                       name="search"
                       class="form-control"
                       placeholder="ค้นหาตามชื่อวิดิโอ"
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
              @foreach ($lists as $value)
                <div class="col-6 col-md-3 col-sm-6">
                  <a href="{{ route('video.show', $value->slug) }}"
                     class="box-video box-video-all">
                    <div class="video-logo">
                      {!! $value->output !!}
                    </div>
                    <div class="video-title fw-bolder my-4">
                      {{ \Str::limit(_stripTags($value->title), 50) }}
                    </div>
                  </a>
                </div>
              @endforeach
            </div>
            {{ $lists->render() }}
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
