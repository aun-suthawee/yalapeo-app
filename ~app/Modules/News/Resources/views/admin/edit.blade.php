@extends('admin::layouts.master')

@section('app-content')
  <div class="row justify-content-center">
    <div class="col-11">
      <x-alert-error-message />

      <form action="{{ route('admin.news.update', $result->id) }}"
            method="POST"
            enctype="multipart/form-data">
        @csrf
        {{ method_field('PUT') }}
        <div class="tile">
          <h3 class="tile-title">แก้ไขรายการ</h3>
          <div class="tile-body">
            <div class="form-row">
              <div class="form-group col-md-12">
                <label>หัวข้อ</label>
                <input type="text"
                       class="form-control"
                       name="title"
                       value="{{ isset($result->title) ? $result->title : '' }}"
                       placeholder="ระบุหัวข้อ"
                       required>
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
                            @if ($result->type_id == $type->id) selected @endif>
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
                         value="{{ $result->date }}"
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
                       accept="image/*"
                       data-initial-caption="{{ $result->cover }}">
                <small class="form-text text-muted">ขนาดรูปภาพที่เหมาะสม 670 × 405 pixel (กว้าง x สูง)</small>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-8">
                <label>ลิงค์เชื่อมโยง</label>
                <input type="text"
                       name="url"
                       value="{{ $result->url }}"
                       class="form-control"
                       placeholder="ระบุลิงค์เชื่อมโยง">
              </div>
              <div class="form-group col-md-4">
                <label>การแสดง (Target)</label>
                <select name="target"
                        class="form-control">
                  <option value="">-เลือก-</option>
                  <option value="_parent"
                          @if ($result->target == '_parent') selected @endif>_parent
                    (เปิดหน้าต่างที่เป็นหน้าต่างระดับ Parent)
                  </option>
                  <option value="_blank"
                          @if ($result->target == '_blank') selected @endif>_blank
                    (เปิดหน้าต่างใหม่ทุกครั้ง)
                  </option>
                  <option value="_self"
                          @if ($result->target == '_self') selected @endif>_self (เปิดหน้าต่างเดิม)
                  </option>
                  <option value="_top"
                          @if ($result->target == '_top') selected @endif>_top (เปิดหน้าต่างในระดับบนสุด)
                  </option>
                </select>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-12">
                <label>คำอธิบาย</label>
                <textarea class="form-control @error('description') is-invalid @enderror"
                          name="description"
                          placeholder="ระบุคำอธิบาย..."
                          require="true">{{ $result->description }}</textarea>
                <x-error-message title="description" />
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-12">
                <label>รายละเอียด</label>
                <textarea class="form-control"
                          name="detail"
                          id="detail"
                          placeholder="ระบุรายละเอียด...">{{ $result->detail }}</textarea>
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
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $(document).ready(function () {
      $("#input-attach").fileinput({
        language: "th",
        initialPreview: @php echo $result->initialPreview; @endphp,
        initialPreviewAsData: true,
        initialPreviewConfig: @php echo $result->initialPreviewConfig; @endphp,
        deleteUrl: "@php echo route('admin.news.attach-destroy', [$result->id])  @endphp",
        overwriteInitial: false,
        initialPreviewFileType: 'image',
        uploadAsync: false,
        browseClass: "btn btn-secondary btn-block",
        showCaption: false,
        showRemove: false,
        showUpload: false,
        allowedFileExtensions: ["doc", "docx", "pdf", "ppt", "pptx"],
        fileActionSettings: {
          showDrag: true,
          showZoom: true,
          showUpload: false,
          showDelete: true,
        },
        purifyHtml: true,
      }).on('filesorted', function (event, params) {
        // console.log('File sorted ', params.previewId, params.oldIndex, params.newIndex, params.stack);
        $.post("@php echo route('admin.news.attach-sort', [$result->id]) @endphp", {
          stack: params.stack
        }, function (data, textStatus, jqXHR) {
          console.log(data, textStatus, jqXHR);
        }, "JSON");
      });

    });
  </script>
@endsection
