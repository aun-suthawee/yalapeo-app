@extends('home::layouts.master')

@section('app-content')
  <section class="page-contents">
    <div class="page-header">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-sm-12">
            <h2>ข่าวสารทั้งหมด</h2>
          </div>
        </div>
      </div>
    </div>

    <div class="page-detail">
      <div class="container-fluid">
        <div class="row justify-content-center py-3">
          <div class="col-sm-12 col-md-6">
            <form action="{{ route('news.index') }}"
                  method="GET">

              @if (request()->input('type'))
                <input type="hidden"
                       name="type"
                       value="{{ request()->input('type') }}">
              @endif

              <div class="input-group">
                <input type="search"
                       name="search"
                       class="form-control"
                       placeholder="ค้นหาตามหัวข้อ"
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
          <div class="col-sm-12 col-md-10">
            <ul class="nav nav-tabs news-nav-tabs mt-4">
              @foreach ($type_lists as $index => $type)
                <li class="nav-item">
                  <a
                    class="nav-link @if ($type->id == request()->type || (request()->type === null && $index == 0)) active @endif"
                    href="{{ route('news.index') . '?' . http_build_query(['type' => $type->id] + request()->all()) }}">
                    {{ $type->title }}
                  </a>
                </li>
              @endforeach
            </ul>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
              @foreach ($lists as $index => $item)
                <div class="col">
                  <div class="card card-news overflow-hidden">
                    <img src="{{ $item->cover }}"
                         class="img-height img-bd-5"
                         alt="{{ Request::getHost() }}">
                    <div class="card-body">
                      <h5 class="card-title cut-text-2">
                        <a href="{{ $item->publish_url }}"
                           target="{{ $item->target }}">
                          {{ $item->title }}
                        </a>
                      </h5>
                      <p class="card-detail">
                        {{ $item->description }}
                      </p>
                      <p class="card-text">
                        <i class="feather feather-calendar hor-icon"></i>
                        {{ $item->date_publish_format_1 }} | อ่าน {{ $item->view }} ครั้ง
                      </p>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
            <div class="d-flex justify-content-center my-5">
              {{ $lists->render() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
