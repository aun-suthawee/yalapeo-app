@extends('home::layouts.master')

@section('app-content')
  <section class="page-contents">
    <div class="page-header">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-sm-12">
            <h2>{{ $result->title }}</h2>
            <span>ประกาศเมื่อ {{ thaiDate($result->date, 'long') }}</span>
          </div>
        </div>
      </div>
    </div>

    <div class="page-detail">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-sm-12 col-md-12">
            <div class="py-5">
              {!! $result->detail !!}

              @if (!empty($result->attach))
                <p class="m-0 mt-3">ไฟล์แนบ</p>
                <ul>
                  @foreach ($result->attach as $attach)
                    <li>
                      {!! __fileExtension($attach['name']) !!}
                      <a href="{{ route('news.download', [$result->id, $attach['name_uploaded'], time()]) }}">
                        {{ $attach['name'] }}
                      </a>
                    </li>
                  @endforeach
                </ul>
                <hr>
                @foreach ($result->attach as $attach)
                  <p>
                    <code class="d-block">{{$attach['name']}}</code>
                    <iframe src="{{ Storage::url('news/' . gen_folder($result->id) . '/attach/' .$attach['name_uploaded']) }}"
                            width="800px"
                            height="600px"></iframe>
                  </p>
                @endforeach
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
