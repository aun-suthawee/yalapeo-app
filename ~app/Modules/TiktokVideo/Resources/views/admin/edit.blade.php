@extends('admin::layouts.master')

@section('app-content')
    <div class="row justify-content-center">
        <div class="col-11">
            <x-alert-error-message />

            <form action="{{ route('admin.tiktokvideo.update', $video->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="tile">
                    <h3 class="tile-title">แก้ไขวิดีโอ TikTok</h3>
                    <div class="tile-body">
                        <div class="form-row">
                            <div class="form-group col-md-7">
                                <label>ที่อยู่วิดิโอ <span class="badge badge-info">TikTok</span></label>
                                <input type="text" class="form-control @error('url') is-invalid @enderror" name="url" value="{{ old('url', $video->url) }}" placeholder="ex. https://www.tiktok.com/@username/video/1234567890123456789">
                                <x-error-message title="url" />
                            </div>
                            <div class="form-group col-md-5">
                                <label>หัวข้อ</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title', $video->title) }}" placeholder="ระบุหัวข้อ" required>
                                <x-error-message title="title" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label>รายละเอียด</label>
                                <textarea class="form-control" name="detail" id="detail" placeholder="ระบุรายละเอียด...">{{ old('detail', $video->detail) }}</textarea>
                                {!! ckeditor_advanced_url('detail') !!}
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" {{ $video->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        เผยแพร่
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- แสดงตัวอย่างวิดีโอ -->
                        <div class="form-row mt-3">
                            <div class="col-md-12">
                                <label>ตัวอย่างวิดีโอ:</label>
                                <div style="max-width: 300px; margin-top: 10px;">
                                    <blockquote class="tiktok-embed" cite="{{ $video->url }}" 
                                        data-video-id="{{ $video->video_id }}" 
                                        style="max-width: 100%;">
                                        <section></section>
                                    </blockquote>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tile-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check-circle fa-fw"></i>บันทึก
                        </button>
                        <a href="{{ route('admin.tiktokvideo.index') }}" class="btn btn-light">
                            <i class="fa fa-arrow-circle-left fa-fw"></i>ย้อนกลับ
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script-content')
    <script async src="https://www.tiktok.com/embed.js"></script>
@endsection