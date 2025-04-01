@extends('admin::layouts.master')

@section('app-content')
  <div class="tile">
    <div class="tile-title-w-btn">
      <h3 class="title">ข้อมูลรายการ</h3>
      <p>
        @can($permission_prefix . '@*create')
          <a class="btn btn-primary icon-btn"
             href="{{ route('admin.user.create') }}"><i class="fa fa-plus-circle fa-fw"></i>สร้าง </a>
        @endcan
        <a class="btn btn-secondary icon-btn"
           href="{{ url()->current() }}"><i class="fas fa-sync fa-fw"></i>Refresh </a>
      </p>
    </div>
    <div class="tile-body">
      <div class="table-responsive">
        <table class="table-hover table-sm table">
          <thead>
          <tr>
            <th class="text-nowrap text-center">#</th>
            <th class="text-nowrap text-center">ชื่อผู้ใช้งาน</th>
            <th class="text-nowrap text-center">ชื่อ-สกุล</th>
            <th class="text-nowrap text-center">ชื่อเล่น</th>
            <th class="text-nowrap text-center">ตำแหน่ง</th>
            <th class="text-nowrap text-center">สร้างเมื่อ</th>
            <th class="text-nowrap text-center">ดำเนินการ</th>
          </tr>
          </thead>
          <tbody>
          @foreach ($lists as $index => $value)
            <tr>
              <td width="1">{{ $lists->firstItem() + $index }}</td>
              <td>{{ $value->username }}</td>
              <td>{{ $value->name }}</td>
              <td>{{ $value->nickname }}</td>
              <td>{{ $value->position }}</td>
              <td class="text-nowrap text-center"
                  width="1">{{ $value->date_created_format_1 }}</td>
              <td width="1">
                <div class="option-link">
                  <form action="{{ route('admin.user.destroy', $value->id) }}"
                        method="POST">
                    @can($permission_prefix . '@*edit')
                      <a class="btn btn-sm btn-info"
                         href="{{ route('admin.user.edit', [$value->id]) }}"> แก้ไข</a>
                    @endcan
                    @can($permission_prefix . '@*delete')
                      @csrf
                      {{ method_field('DELETE') }}
                      <button class="btn btn-sm btn-danger"
                              onclick="return confirm('ท่านต้องการลบรายการนี้ใช่หรือไม่ ?')"
                              type="submit">ยกเลิก
                      </button>
                    @endcan
                  </form>
                </div>
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    </div>
    <div class="tile-footer">
      {{ $lists->render() }}
    </div>
  </div>
@endsection
