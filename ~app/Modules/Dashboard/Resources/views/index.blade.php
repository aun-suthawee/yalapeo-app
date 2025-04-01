@extends('admin::layouts.master')

@section('app-content')
  <div class="row">
    <div class="col-md-6 col-lg-3">
      <div class="widget-small primary coloured-icon">
        <i class="icon fas fa-store-alt fa-3x"></i>
        <div class="info">
          <h4>xxxx</h4>
          <p><b>0</b></p>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3">
      <div class="widget-small info coloured-icon">
        <i class="icon fas fa-house-user fa-3x"></i>
        <div class="info">
          <h4>xxxx</h4>
          <p><b>0 </b></p>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3">
      <div class="widget-small warning coloured-icon">
        <i class="icon fas fa-boxes fa-3x"></i>
        <div class="info">
          <h4>xxxx</h4>
          <p><b>0 </b></p>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3">
      <div class="widget-small danger coloured-icon"><i class="icon fas fa-money-check-alt fa-3x"></i>
        <div class="info">
          <h4>xxxx</h4>
          <p><b>0 </b></p>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <div class="tile">
        <div class="tile-title-w-btn">
          <h3 class="title">ข่าวสารล่าสุด</h3>
        </div>
        <div class="tile-body ">
          <div class="table-responsive">
            <table class="table table-hover table-sm">
              <thead>
              <tr>
                <th scope="col"
                    class="text-nowrap text-center">#
                </th>
                <th class="text-nowrap text-center">หัวข้อ</th>
                <th scope="col"
                    class="text-nowrap text-center">สร้างเมื่อ
                </th>
              </tr>
              </thead>
              <tbody>
              @foreach ($news_lists as $index => $value)
                <tr>
                  <th scope="row"
                      width="1">{{ $index+1 }}</th>
                  <td class="text-nowrap">
                    <a class="d-block"
                       target="_blank"
                       href="{{$value->publish_url }}">
                      {{  Str::limit($value->title, 80) }}
                    </a>
                  </td>
                  <td width="100"
                      class="text-nowrap">{{ $value->extra_date_created_thai1 }} </td>
                </tr>
              @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="tile">
        <div class="tile-title-w-btn">
          <h3 class="title">Page ล่าสุด</h3>
        </div>
        <div class="tile-body">
          <div class="table-responsive">
            <table class="table table-hover table-sm">
              <thead>
              <tr>
                <th scope="col"
                    class="text-nowrap text-center">#
                </th>
                <th class="text-nowrap text-center">หัวข้อ</th>
                <th scope="col"
                    class="text-nowrap text-center">สร้างเมื่อ
                </th>
              </tr>
              </thead>
              <tbody>
              @foreach ($page_lists as $index => $value)
                <tr>
                  <th scope="row"
                      width="1">{{ $index+1 }}</th>
                  <td class="text-nowrap">
                    <a class="d-block"
                       target="_blank"
                       href="{{$value->publish_url }}">
                      {{  Str::limit($value->title, 80) }}
                    </a>
                  </td>
                  <td width="100"
                      class="text-nowrap">
                    {{ $value->extra_date_created_thai1 }}
                  </td>
                </tr>
              @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
