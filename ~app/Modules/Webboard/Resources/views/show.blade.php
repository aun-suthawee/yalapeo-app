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
                <div class="card-body">
                  <form action="{{ route('webboard.answer.store', $result->id) }}" method="POST"
                        enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                      <div class="col-4">
                        <label class="form-label">ชื่อผู้ตอบ</label>
                        <input type="text" class="form-control" name="author" placeholder="ระบุชื่อผู้ตอบ" required>
                      </div>
                      <div class="col-12">
                        <label class="form-label">รายละเอียด</label>
                        <textarea class="form-control summernote" name="detail" rows="8" placeholder="ระบุรายละเอียด..."
                                  required></textarea>
                      </div>
                      @if (config('services.recaptcha.key'))
                        <div class="col-12">
                          <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.key') }}"></div>
                        </div>
                      @endif
                      <div class="col-12">
                        <x-alert-error-message/>

                        <button class="btn btn-primary" type="submit">ส่งข้อมูล</button>
                        <button class="btn btn-secondary" type="reset">ยกเลิก</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

