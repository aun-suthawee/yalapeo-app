@extends('admin::layouts.master')

@section('app-content')
  <form action="{{ route('admin.box.update', $result->id) }}"
        method="POST">
    @csrf
    {{ method_field('PUT') }}
    <div class="row">
      <div class="col-md-12">
        <div class="tile">
          <h3 class="tile-title">Box 1</h3>
          <div class="tile-body">
            <div class="form-row">
              <div class="form-group col-md-12">
                <label class="control-label">รายละเอียด</label>
                <textarea class="form-control"
                          name="detail_1"
                          id="detail_1"
                          rows="8"
                          placeholder="ระบุรายละเอียด...">{{ $result->detail_1 }}</textarea>
                {!! ckeditor_advanced_url('detail_1', 1) !!}
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
      </div>
      <div class="col-md-12">
        <div class="tile">
          <h3 class="tile-title">Box 2</h3>
          <div class="tile-body">
            <div class="form-row">
              <div class="form-group col-md-12">
                <label class="control-label">รายละเอียด</label>
                <textarea class="form-control"
                          name="detail_2"
                          id="detail_2"
                          rows="8"
                          placeholder="ระบุรายละเอียด...">{{ $result->detail_2 }}</textarea>
                {!! ckeditor_advanced_url('detail_2', 2) !!}
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
      </div>
    </div>
  </form>
@endsection
