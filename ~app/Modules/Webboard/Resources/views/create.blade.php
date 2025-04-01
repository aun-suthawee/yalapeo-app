@extends('home::layouts.master')

@section('app-content')
  <section class="page-contents">
    <div class="page-header">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-sm-12">
            <h2>สร้างกระทู้กระดานสนทนา</h2>
            <a class="text-decoration-none text-primary"
               href="{{ route('webboard.index') }}">กลับไปยังหน้ากระทู้ทั้งหมด</a>
          </div>
        </div>
      </div>
    </div>

    <div class="page-detail">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-sm-12 col-md-12">
            <div class="py-5">

              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <x-alert-error-message/>
                    </div>
                  </div>
                  <form action="{{ route('webboard.store') }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="row g-3">
                      <div class="col-8">
                        <label class="form-label">หัวข้อ</label>
                        <input class="form-control @error('title') is-invalid @enderror" name="title"
                               placeholder="ระบุหัวข้อ" required type="text" value="{{ old('title') }}">
                      </div>
                      <div class="col-4">
                        <label class="form-label">ชื่อผู้เขียน</label>
                        <input class="form-control @error('author') is-invalid @enderror" name="author"
                               placeholder="ระบุผู้เขียน" required type="text" value="{{ old('author') }}">
                      </div>
                      <div class="col-12">
                        <label class="form-label">รายละเอียด</label>
                        <textarea class="form-control @error('detail') is-invalid @enderror summernote" name="detail"
                                  placeholder="ระบุรายละเอียด..." required rows="8">{{ old('detail') }}</textarea>
                      </div>
                      @if (config('services.recaptcha.key'))
                        <div class="col-12">
                          <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.key') }}"></div>
                        </div>
                      @endif
                      <div class="col-12">
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
