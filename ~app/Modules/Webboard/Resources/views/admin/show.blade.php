@extends('admin::layouts.master')

@section('app-content')
  <div class="row justify-content-center">
    <div class="col-11">
      <div class="tile">
        <h3 class="tile-title">มีหลักฐานที่เป็นข้อเท็จจริงยืนยันมานานแล้ว
          ว่าเนื้อหาที่อ่านรู้เรื่องนั้นจะไปกวนสมาธิของคนอ่านให้เขวไปจากส่วนที้เป็น</h3>
        <div class="tile-body">
          {!! $result->detail !!}
        </div>
        <div class="tile-footer d-flex justify-content-between">
          <span>{{ $result->created_at }} / IP Address: {{ $result->ip }}</span>
          <span>จากคุณ {{ $result->author }}</span>
        </div>
      </div>
    </div>
  </div>

  @foreach ($result->answers as $value)
    <div class="row justify-content-center">
      <div class="col-11">
        <div class="tile">
          <div class="tile-title-w-btn">
            <h3 class="tile-title">จากคุณ {{ $value->author }}</h3>
            <div class="btn-group">
              <form method="POST"
                    action="{{ route('admin.webboard.answer.destroy', [$result->id, $value->id]) }}">
                @can($permission_prefix.'@*delete')
                  @csrf
                  {{ method_field('DELETE') }}
                  <button type="submit"
                          class="btn btn-sm btn-danger"
                          onclick="return confirm('ท่านต้องการลบรายการนี้ใช่หรือไม่ ?')">
                    <i class="fa fa-lg fa-trash"></i>
                  </button>
                @endcan
              </form>
            </div>
          </div>
          <div class="tile-body">
            {!! $value->detail !!}
          </div>
          <div class="tile-footer d-flex justify-content-between">
            <span>{{ $value->date_created_format_1 }} / IP Address: {{ $value->ip }}</span>
          </div>
        </div>
      </div>
    </div>
  @endforeach

  <div class="row justify-content-center">
    <div class="col-11">
      <x-alert-error-message />

      <form action="{{ route('admin.webboard.answer.store', $result->id) }}"
            method="POST"
            enctype="multipart/form-data">
        @csrf
        <div class="tile">
          <h3 class="tile-title">ตอบกระทู้</h3>
          <div class="tile-body">
            <div class="form-row">
              <div class="form-group col-md-4">
                <label>ชื่อผู้ตอบ</label>
                <input type="text"
                       class="form-control"
                       name="author"
                       placeholder="ระบุชื่อผู้ตอบ">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-12">
                <label>รายละเอียด</label>
                <textarea class="form-control"
                          name="detail"
                          id="detail"
                          rows="8"
                          placeholder="ระบุรายละเอียด..."></textarea>
                {!! ckeditor_advanced_url('detail') !!}
              </div>
            </div>
          </div>
          <div class="tile-footer">
            <button type="submit"
                    class="btn btn-primary">
              <i class="fas fa-check-circle fa-fw"></i>บันทึก
            </button>
            <button type="reset"
                    class="btn btn-light">
              <i class="fa fa-times-circle fa-fw"></i>ยกเลิก
            </button>

          </div>
        </div>
      </form>
    </div>
  </div>
@endsection
