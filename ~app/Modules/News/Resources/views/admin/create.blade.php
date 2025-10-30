@extends('admin::layouts.master')

@section('app-content')
  <div class="row justify-content-center">
    <div class="col-11">
      <x-alert-error-message />
 
      <form action="{{ route('admin.news.store') }}"
            method="POST"
            enctype="multipart/form-data">
        @csrf
        <div class="tile">
          <h3 class="tile-title">สร้างใหม่</h3>
          <div class="tile-body">
            <div class="form-row">
              <div class="form-group col-md-12">
                <label>หัวข้อ <span class="text-danger">*</span> <small class="text-muted">(ไม่เกิน 191 ตัวอักษร)</small></label>
                <input type="text"
                       class="form-control @error('title') is-invalid @enderror"
                       name="title"
                       maxlength="191"
                       value="{{ old('title') }}"
                       placeholder="ระบุหัวข้อ"
                       required>
                <x-error-message title="title" />
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-4">
                <label>ประเภท</label>
                <select name="type_id"
                        class="form-control @error('type_id') is-invalid @enderror">
                  <option value="">-เลือกประเภท-</option>
                  @foreach ($types as $type)
                    <option value="{{ $type['id'] }}"
                            @if (old('type_id') == $type->id) selected @endif>
                      {{ $type->title }}
                    </option>
                  @endforeach
                </select>
                <x-error-message title="type_id" />
              </div>
              <div class="form-group col-md-3">
                <label>ลงประกาศวันที่</label>
                <div class="input-group">
                  <input type="text"
                         name="date"
                         class="form-control datepicker @error('date') is-invalid @enderror"
                         value="{{ old('date') }}"
                         placeholder="ระบุวันที่">
                  <div class="input-group-prepend">
                    <span class="input-group-text"
                          id="basic-addon1"><i class="fas fa-calendar-alt"></i></span>
                  </div>
                </div>
                <x-error-message title="date" />
              </div>
              <div class="form-group col-md-5">
                <label>หน้าปก</label>
                <input type="file"
                       name="cover"
                       class="form-control krajee-input"
                       data-msg-placeholder="เลือกไฟล์หน้าปก"
                       accept="image/*">
                <small class="form-text text-muted">ขนาดรูปภาพที่เหมาะสม 670 × 405 (กว้าง x สูง)</small>
                
                <!-- เพิ่มส่วนแสดงภาพตั้งต้น -->
                <div class="mt-2">
                  <p><small>หากไม่มีการอัพโหลดรูปภาพ ระบบจะใช้รูปภาพนี้เป็นค่าเริ่มต้น:</small></p>
                  <img src="{{ asset('assets/images/new_thumbnail.jpg') }}" alt="รูปภาพตั้งต้น" style="max-height: 100px;">
                </div>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-8">
                <label>ลิงค์เชื่อมโยง</label>
                <input type="text"
                       name="url"
                       class="form-control"
                       placeholder="ระบุลิงค์เชื่อมโยง">
              </div>
              <div class="form-group col-md-4">
                <label>การแสดง (Target)</label>
                <select name="target"
                        class="form-control">
                  <option value="">-เลือก-</option>
                  <option value="_parent">_parent (เปิดหน้าต่างที่เป็นหน้าต่างระดับ Parent)</option>
                  <option value="_blank">_blank (เปิดหน้าต่างใหม่ทุกครั้ง)</option>
                  <option value="_self">_self (เปิดหน้าต่างเดิม)</option>
                  <option value="_top">_top (เปิดหน้าต่างในระดับบนสุด)</option>
                </select>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-12">
                <label>คำอธิบาย</label>
                <textarea class="form-control @error('description') is-invalid @enderror"
                          name="description"
                          placeholder="ระบุคำอธิบาย..."
                          require="true"></textarea>
                <x-error-message title="description" />
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-12">
                <label>รายละเอียด</label>
                <textarea class="form-control"
                          name="detail"
                          id="detail"
                          placeholder="ระบุรายละเอียด..."></textarea>
                {!! ckeditor_advanced_url('detail') !!}
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-12">
                <label>แนบไฟล์</label>
                <div class="file-loading">
                  <input type="file"
                         id="input-attach"
                         name="attach[]"
                         multiple>
                </div>
                <small class="form-text text-muted">
                  รองรับไฟล์: PDF, Word (.doc, .docx), Excel (.xls, .xlsx), PowerPoint (.ppt, .pptx)
                </small>
              </div>
            </div>
          </div>
          <div class="tile-footer">
            <button type="submit"
                    class="btn btn-primary">
              <i class="fas fa-check-circle fa-fw"></i>บันทึก
            </button>
            <button type="reset"
                    class="btn btn-light"><i class="fas fa-times-circle fa-fw"></i>ยกเลิก
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection
 
@section('script-content')
  <script>
    $(document).ready(function () {
      $("#input-attach").fileinput({
        language: "th",
        browseClass: "btn btn-secondary btn-block",
        showCaption: false,
        showRemove: false,
        showUpload: false,
        allowedFileExtensions: ["doc", "docx", "pdf", "ppt", "pptx", "xls", "xlsx"],
        fileActionSettings: {
          showDrag: false,
          showZoom: true,
          showUpload: false,
          showDelete: true,
        }
      });
    });
  </script>
@endsection

