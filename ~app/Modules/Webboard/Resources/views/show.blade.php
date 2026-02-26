@extends('home::layouts.master')

@section('app-content')
  <section class="page-contents">
    <div class="page-header">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-sm-12">
            <h2>ตอบกระทู้กระดานสนทนา</h2>
            <a href="{{ route('webboard.index') }}"
              class="text-decoration-none text-primary">กลับไปยังหน้ากระทู้ทั้งหมด</a>
          </div>
        </div>
      </div>
    </div>

    <div class="page-detail">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-sm-12 col-md-12">
            <div class="py-5">

              <div class="card my-4">
                <div class="card-body">
                  <h5 class="card-title fw-bold">
                    หัวข้อ : {{ $result->title }}
                  </h5>
                  <h6 class="card-subtitle text-muted mb-2">จากคุณ: {{ $result->author }}</h6>
                  <p class="card-text">
                    {!! $result->detail !!}
                  </p>
                  <div class="card-link text-end">
                    {{ $result->created_at }}
                  </div>
                </div>
              </div>

              @foreach ($result->answers as $value)
                <div class="card my-4">
                  <div class="card-body">
                    <h5 class="card-title fw-bold">
                      <i class="feather-icon feather-hash text-black-50"></i> จากคุณ: {{ $value->author }}
                    </h5>
                    <p class="card-text">
                      {!! $value->detail !!}
                    </p>
                    <div class="card-link text-end">
                      {{ $value->date_created_format_1 }}
                    </div>
                  </div>
                </div>
              @endforeach

              <div class="card">
                <div class="card-body text-center">
                  <div class="alert alert-warning mb-0">
                    ระบบงดรับการตอบกระทู้ชั่วคราว เพื่อป้องกันข้อมูลสแปม
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection