@extends('admin::layouts.master')

@section('app-content')
  <div class="tile">
    <div class="tile-title-w-btn">
      <h3 class="title">ข้อมูลรายการ</h3>
      <p>
        @can($permission_prefix.'@*create')
          <a class="btn btn-primary icon-btn"
             href="{{ route('admin.page.create') }}"><i class="fa fa-plus-circle fa-fw"></i>สร้าง </a>
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
            <th class="text-nowrap text-center">หัวข้อ</th>
            <th scope="col"
                class="text-nowrap text-center">สร้างเมื่อ
            </th>
            <th scope="col"
                class="text-nowrap text-center">จัดการ
            </th>
          </tr>
          </thead>
          <tbody>
          @foreach ($lists as $index => $value)
            <tr>
              <th scope="row"
                  width="1">{{ $lists->firstItem() + $index }}</th>
              <td>
                {{ $value->title }}
                <small class="d-block text-muted text-light">
                  <i class="fas fa-link"></i> {{ $value->publish_url }}
                </small>
              </td>
              <td width="100"
                  class="text-nowrap">{{ $value->date_created_format_1 }}</td>
              <td width="1">
                <div class="option-link">
                  <form method="POST"
                        action="{{ route('admin.page.destroy', $value->id) }}">
                    @can($permission_prefix.'@*edit')
                      <a class="btn btn-sm btn-info"
                         href="{{ route('admin.page.edit', [$value->id]) }}"> แก้ไข</a>
                    @endcan
                    @can($permission_prefix.'@*delete')
                      @csrf
                      {{ method_field('DELETE') }}
                      <button type="submit"
                              class="btn btn-sm btn-danger"
                              onclick="return confirm('ท่านต้องการลบรายการนี้ใช่หรือไม่ ?')">ยกเลิก
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
