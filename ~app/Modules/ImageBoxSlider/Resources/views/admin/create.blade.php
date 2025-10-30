@extends('admin::layouts.master')
@section('app-content')

<div class="row justify-content-center">
    <div class="col-11">
        <x-alert-error-message />
        
        <form action="{{ route('admin.imageboxslider.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="tile">
                <h3 class="tile-title">สร้างรูปภาพสไลด์ใหม่</h3>
                <div class="tile-body">
                    <div class="form-row">
                        <div class="form-group col-md-9">
                            <label>หัวข้อ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                  name="title" value="{{ old('title') }}" placeholder="ระบุหัวข้อ" required>
                            <x-error-message title="title" />
                        </div>
                        
                        <!-- เพิ่มส่วน is_active -->
                        <div class="form-group col-md-3">
                            <label>สถานะการแสดงผล</label>
                            <div class="custom-control custom-switch mt-2">
                                <label>
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" value="1" checked>
                                    แสดงรูปภาพนี้ในหน้าเว็บไซต์
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- เพิ่มส่วนลิงค์เชื่อมโยง -->
                    <div class="form-row">
                        <div class="form-group col-md-8">
                            <label>ลิงค์เชื่อมโยง</label>
                            <input type="text" name="url" class="form-control @error('url') is-invalid @enderror" 
                                value="{{ old('url') }}" placeholder="ระบุลิงค์เชื่อมโยง">
                            <small class="form-text text-muted">ระบุ URL ที่ต้องการให้ลิงค์ไปเมื่อคลิกที่รูปภาพ</small>
                            <x-error-message title="url" />
                        </div>
                        <div class="form-group col-md-4">
                            <label>การแสดง (Target)</label>
                            <select name="target" class="form-control @error('target') is-invalid @enderror">
                                <option value="">-เลือก-</option>
                                <option value="_parent" {{ old('target') == '_parent' ? 'selected' : '' }}>_parent (เปิดหน้าต่างที่เป็นหน้าต่างระดับ Parent)</option>
                                <option value="_blank" {{ old('target') == '_blank' ? 'selected' : '' }}>_blank (เปิดหน้าต่างใหม่ทุกครั้ง)</option>
                                <option value="_self" {{ old('target') == '_self' ? 'selected' : '' }}>_self (เปิดหน้าต่างเดิม)</option>
                                <option value="_top" {{ old('target') == '_top' ? 'selected' : '' }}>_top (เปิดหน้าต่างในระดับบนสุด)</option>
                            </select>
                            <x-error-message title="target" />
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label>รายละเอียด</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                     name="description" rows="5" placeholder="ระบุรายละเอียด...">{{ old('description') }}</textarea>
                            <x-error-message title="description" />
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>รูปภาพ (สี่เหลี่ยมจัตุรัส) <span class="text-danger">*</span></label>
                            <input type="file" name="image" class="form-control krajee-input @error('image') is-invalid @enderror" 
                                  data-msg-placeholder="เลือกไฟล์รูปภาพ" accept="image/*" required>
                            <small class="form-text text-muted">รองรับไฟล์ภาพ JPG, PNG ขนาดไม่เกิน 20MB (ขนาดแนะนำ 1200x1200)</small>
                            <x-error-message title="image" />
                        </div>
                        
                        <div class="form-group col-md-6">
                            <label>ไฟล์ PDF (สามารถเลือกได้หลายไฟล์)</label>
                            <div class="file-loading">
                                <input type="file" id="input-pdf-files" name="pdf_files[]" class="file-input" multiple>
                            </div>
                            <small class="form-text text-muted">รองรับไฟล์ PDF ขนาดไม่เกิน 20MB ต่อไฟล์</small>
                            <x-error-message title="pdf_files" />
                        </div>
                    </div>
                </div>
                <div class="tile-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check-circle fa-fw"></i>บันทึก
                    </button>
                    <button type="reset" class="btn btn-light">
                        <i class="fas fa-times-circle fa-fw"></i>ยกเลิก
                    </button>
                    <a class="btn btn-secondary" href="{{ route('admin.imageboxslider.index') }}">
                        <i class="fa fa-arrow-circle-left fa-fw"></i>ย้อนกลับ
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('script-content')
<script>
    $(document).ready(function() {
        // สำหรับการเปลี่ยนสถานะ is_active
        $('#isActive').change(function() {
            if($(this).is(':checked')) {
                $('#statusText').text('เปิดใช้งาน').removeClass('text-danger').addClass('text-success');
            } else {
                $('#statusText').text('ปิดใช้งาน').removeClass('text-success').addClass('text-danger');
            }
        });
        
        // สำหรับการเลือกไฟล์รูปภาพ
        $("input[name='image']").fileinput({
            language: "th",
            browseClass: "btn btn-primary",
            showCaption: true,
            showRemove: true,
            showUpload: false,
            allowedFileExtensions: ["jpg", "jpeg", "png", "gif"],
            maxFileSize: 20480, // 20MB
            fileActionSettings: {
                showDrag: false,
                showZoom: true,
                showUpload: false,
                showDelete: true,
            }
        });
        
        // สำหรับการเลือกไฟล์ PDF หลายไฟล์
        $("#input-pdf-files").fileinput({
            language: "th",
            theme: "fa5",
            browseClass: "btn btn-secondary",
            showCaption: true,
            showRemove: true,
            showUpload: false,
            showPreview: true,
            showCancel: false,
            maxFileCount: 10,
            allowedFileExtensions: ["pdf"],
            maxFileSize: 20480, // 20MB
            initialPreviewFileType: 'pdf',
            previewFileIcon: '<i class="fas fa-file-pdf text-danger"></i>',
            fileActionSettings: {
                showDrag: true,
                showZoom: false,
                showUpload: false,
                showDelete: true,
            }
        });
    });
</script>
@endsection