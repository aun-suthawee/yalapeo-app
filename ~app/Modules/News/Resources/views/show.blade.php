@extends('home::layouts.master')

@section('app-content')
  <section class="page-contents">
    <div class="page-header">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-sm-12">
            <h2>{{ $result->title }}</h2>
            {{-- <span>ประกาศเมื่อ {{ thaiDate($result->date, 'long') }}</span> --}}
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
                  @php
                    $extension = strtolower(pathinfo($attach['name'], PATHINFO_EXTENSION));
                    $isPdf = $extension === 'pdf';
                    $fileUrl = Storage::url('news/' . gen_folder($result->id) . '/attach/' .$attach['name_uploaded']);
                  @endphp
                  
                  <p>
                    <code class="d-block text-center mb-2">{{$attach['name']}}</code>
                    
                    @if ($isPdf)
                      <div class="d-flex justify-content-center">
                        <iframe src="{{ $fileUrl }}"
                                width="800px"
                                height="1000px"
                                style="border: 1px solid #dee2e6; max-width: 100%;"
                                allowfullscreen></iframe>
                      </div>
                    @else
                      <div class="alert alert-info text-center">
                        <i class="fas fa-download"></i>
                        <a href="{{ route('news.download', [$result->id, $attach['name_uploaded'], time()]) }}">
                          ดาวน์โหลด {{ $attach['name'] }}
                        </a>
                      </div>
                    @endif
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

