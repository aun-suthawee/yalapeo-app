@extends('admin::layouts.master')

@section('app-content')
  <div class="tile">
    <div class="tile-title-w-btn">
      <h3 class="title">ข้อมูลรายการ</h3>
      <p>
        @can($permission_prefix.'@*create')
          <a class="btn btn-primary icon-btn"
             href="{{ route('admin.banner.create') }}"><i class="fa fa-plus-circle fa-fw"></i>สร้าง </a>
        @endcan
        <a class="btn btn-secondary icon-btn"
           href="{{ url()->current() }}"><i class="fas fa-sync fa-fw"></i>Refresh </a>
      </p>
    </div>
    <div class="tile-body">
      <div class="table-responsive">
        <table class="table table-hover table-sm">
          <thead>
          <tr>
            <th></th>
            <th class="text-nowrap text-center">หัวข้อ</th>
            <th scope="col"
                class="text-nowrap text-center">สร้างเมื่อ
            </th>
            <th scope="col"
                class="text-nowrap text-center">จัดการ
            </th>
          </tr>
          </thead>
          <tbody id="sort-me"
                 data-href="{{ route('admin.banner.sort') }}">
          @foreach ($lists as $index => $value)
            <tr id="item_{{ $value->id }}">
              <td width="1">
                            <span class="handle ui-sortable-handle">
                                <i class="fas fa-sort-alpha-down fa-lg"></i>
                            </span>
              </td>
              <td>
                <a data-toggle="tooltip-image"
                   title="<img src='{{ Storage::url('banner/'.gen_folder($value->id).'/crop/'.$value->cover) }}' height='120'>">
                  {{ $value->title }}
                </a>
              </td>
              <td width="100"
                  class="text-nowrap">{{ $value->date_created_format_1 }}</td>
              <td width="1">
                <div class="option-link">
                  <form method="POST"
                        action="{{ route('admin.banner.destroy', $value->id) }}">
                    <a class="btn btn-sm btn-outline-warning"
                       target="_blank"
                       href="{{ route('banner.show', [$value->slug]) }}"> ดูเพิ่มเติม</a>
                    @can($permission_prefix.'@*edit')
                      <a class="btn btn-sm btn-info"
                         href="{{ route('admin.banner.edit', [$value->id]) }}"> แก้ไข</a>
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
      {{ $lists->render()  }}
    </div>
  </div>
@endsection
@section('script-content')
  <script type="text/javascript">
    $("#sort-me").juiSortable();
  </script>
@endsection
