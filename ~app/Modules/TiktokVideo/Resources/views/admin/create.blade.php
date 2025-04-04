@extends('admin::layouts.master')

@section('app-content')
    <div class="row justify-content-center">
        <div class="col-11">
            <x-alert-error-message />

            <form action="{{ route('admin.tiktokvideo.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="tile">
                    <h3 class="tile-title">เพิ่มวิดีโอ TikTok</h3>
                    <div class="tile-body">
                        <div class="form-row">
                            <div class="form-group col-md-7">
                                <label>ที่อยู่วิดิโอ <span class="badge badge-info">TikTok</span></label>
                                <input type="text" class="form-control @error('url') is-invalid @enderror" name="url" value="{{ old('url') }}" placeholder="ex. https://www.tiktok.com/@username/video/1234567890123456789">
                                <x-error-message title="url" />
                            </div>
                            <div class="form-group col-md-5">
                                <label>หัวข้อ</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" placeholder="ระบุหัวข้อ" required>
                                <x-error-message title="title" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label>รายละเอียด</label>
                                <textarea class="form-control" name="detail" id="detail" placeholder="ระบุรายละเอียด..."></textarea>
                                {!! ckeditor_advanced_url('detail') !!}
                            </div>
                        </div>
                    </div>
                    <div class="tile-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check-circle fa-fw"></i>บันทึก
                        </button>
                        <button type="reset" class="btn btn-light">
                            <i class="fa fa-times-circle fa-fw"></i>ยกเลิก
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script-content')
    <script type="text/javascript"></script>
@endsection