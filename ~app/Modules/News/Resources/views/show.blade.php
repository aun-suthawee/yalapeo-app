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
                    @php
                      $attachName = $attach['name'] ?? '';
                      $storedName = $attach['name_uploaded'] ?? '';
                      $attachPath = 'news/' . gen_folder($result->id) . '/attach/' . $storedName;
                      $exists = $storedName !== '' && Storage::disk('public')->exists($attachPath);
                    @endphp
                    @if($exists)
                      <li>
                        {!! __fileExtension($attachName) !!}
                        <a href="{{ route('news.download', [$result->id, $storedName, time()]) }}">
                          {{ $attachName }}
                        </a>
                      </li>
                    @endif
                  @endforeach
                </ul>
                <hr>
                @foreach ($result->attach as $attach)
                  @php
                    $attachName = $attach['name'] ?? '';
                    $storedName = $attach['name_uploaded'] ?? '';
                    $extension = strtolower(pathinfo($attachName, PATHINFO_EXTENSION));
                    $isPdf = $extension === 'pdf';
                    $attachPath = 'news/' . gen_folder($result->id) . '/attach/' . $storedName;
                    $exists = $storedName !== '' && Storage::disk('public')->exists($attachPath);
                    $fileUrl = $exists ? Storage::url($attachPath) : '';
                  @endphp
                  
                  <p>
                    <code class="d-block text-center mb-2">{{$attachName}}</code>
                    
                    @if ($isPdf && $exists)
                      <div class="d-flex justify-content-center">
                        <iframe src="{{ $fileUrl }}"
                                width="800px"
                                height="1000px"
                                style="border: 1px solid #dee2e6; max-width: 100%;"
                                allowfullscreen></iframe>
                      </div>
                    @elseif($exists)
                      <div class="alert alert-info text-center">
                        <i class="fas fa-download"></i>
                        <a href="{{ route('news.download', [$result->id, $storedName, time()]) }}">
                          ดาวน์โหลด {{ $attachName }}
                        </a>
                      </div>
                    @else
                      <div class="alert alert-warning text-center">
                        ไฟล์แนบไม่พร้อมใช้งาน
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

