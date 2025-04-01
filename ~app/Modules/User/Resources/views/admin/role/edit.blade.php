@extends('admin::layouts.master')

@section('app-content')
  <div class="row justify-content-center">
    <div class="col-6">
      <x-alert-error-message />

      <form action="{{ route('admin.user.role.update', $result->id) }}"
            enctype="multipart/form-data"
            method="POST">
        @csrf
        {{ method_field('PUT') }}
        <div class="tile">
          <h3 class="tile-title">แก้ไขรายการ</h3>
          <div class="tile-body">
            <div class="form-row">
              <div class="form-group col-md-6">
                <label>ชื่อบทบาท</label>
                <input class="form-control @error('name') is-invalid @enderror"
                       name="name"
                       pattern="^[a-zA-Z]+$"
                       placeholder="ระบุชื่อสิทธิ์การใช้งาน"
                       type="text"
                       value="{{ $result->name }}">
                <small class="form-text text-muted">ต้องเป็นภาษาอังกฤษเท่านั้น</small>
                <x-error-message title="name" />
              </div>
              <div class="form-group col-md-6">
                <label>ชื่อที่แสดง</label>
                <input class="form-control @error('name') is-invalid @enderror"
                       name="display_name"
                       placeholder="ระบุชื่อที่แสดง"
                       type="text"
                       value="{{ $result->display_name }}">
                <x-error-message title="display_name" />
              </div>
            </div>
            <div class="form-row">
              <div class="col-md-12">
                <label>สิทธิ์การใช้งาน</label>
                <table class="table-striped table-sm table">
                  @foreach ($permissions as $permission)
                    <tr>
                      <th colspan="2">{{ $permission['prefix'] }}</th>
                      @foreach ($permission['method'] as $method)
                        <td>
                          <label class="m-0">
                            <input
                              @if (false !== array_search($method['id'], array_column($role_permission, 'id'))) checked
                              @endif name="permission[]"
                              type="checkbox"
                              value="{{ $method['id'] }}">
                            {{ $method['name'] }}
                          </label>
                        </td>
                      @endforeach
                    </tr>
                  @endforeach
                </table>
              </div>
            </div>
          </div>
          <div class="tile-footer">
            <button class="btn btn-primary"
                    type="submit">
              <i class="fas fa-check-circle fa-fw"></i>บันทึก
            </button>
            <button class="btn btn-light"
                    type="reset">
              <i class="fa fa-times-circle fa-fw"></i>ยกเลิก
            </button>

          </div>
        </div>
      </form>
    </div>
  </div>
@endsection
