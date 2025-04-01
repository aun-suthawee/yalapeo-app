@extends('admin::layouts.master')

@section('app-content')
  <div class="row justify-content-center">
    <div class="col-8">
      <div class="tile">
        <div class="tile-title-w-btn">
          <h3 class="title">ข้อมูลรายการ</h3>
          <p>
            @can($permission_prefix.'@*create')
              <a class="btn btn-primary icon-btn"
                 href="{{ route('admin.news.type.create') }}"><i class="fa fa-plus-circle fa-fw"></i>สร้าง </a>
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
                <th scope="col"
                    class="text-nowrap text-center"
                    colspan="2">#
                </th>
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
                     data-href="{{ route('admin.news.type.sort') }}">
              @foreach ($lists as $index => $value)
                <tr id="item_{{ $value->id }}">
                  <td width="1">
																						<span class="handle ui-sortable-handle">
																								<i class="fas fa-sort-alpha-down fa-lg"></i>
																						</span>
                  </td>
                  <th scope="row"
                      width="1">{{ $index + 1 }}</th>
                  <td>{{ $value->title }}</td>
                  <td width="100"
                      class="text-nowrap">{{ $value->date_created_format_1 }}</td>
                  <td width="1">
                    @if ($value->id != 4)
                      <div class="option-link">
                        <form method="POST"
                              action="{{ route('admin.news.type.destroy', $value->id) }}">
                          @can($permission_prefix.'@*edit')
                            <a class="btn btn-sm btn-info"
                               href="{{ route('admin.news.type.edit', [$value->id]) }}"> แก้ไข</a>
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
                    @endif
                  </td>
                </tr>
              @endforeach
              </tbody>
            </table>
          </div>
        </div>
        <div class="tile-footer">

        </div>
      </div>
    </div>
  </div>
@endsection
@section('script-content')
  <script type="text/javascript">
    $("#sort-me").juiSortable();
  </script>
@endsection
