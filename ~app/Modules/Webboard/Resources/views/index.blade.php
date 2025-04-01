@extends('home::layouts.master')

@section('app-content')
  <section class="page-contents">
    <div class="page-header">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-sm-12">
            <h2>กระทู้กระดานทั้งหมด</h2>
          </div>
        </div>
      </div>
    </div>

    <div class="page-detail">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-sm-12 col-md-12">
            <div class="py-5">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex justify-content-between mx-2 my-3">
                    <div aria-label="Basic example"
                         class="btn-group d-inline btn-group-sm mt-2"
                         role="group">
                      <a class="btn btn-warning rounded-2"
                         href="{{ route('webboard.create') }}">ตั้งกระทู้ใหม่</a>
                    </div>
                  </div>

                  <table class="table">
                    @foreach ($lists as $value)
                      <tr>
                        <td>
                          <a class="text-decoration-none"
                             href="{{ route('webboard.show', [$value->slug]) }}">
                            {{ $value->title }}
                            <small class="d-block">
                              <span class="text-secondary">จากคุณ {{ $value->author }}</span>
                              <span class="text-secondary">/ IP Address: {{ $value->ip }}</span>
                              <span class="text-danger">[ ตอบคำถาม {{ $value->answers()->count() }}]</span>
                            </small>
                          </a>
                        </td>
                        <td class="text-nowrap"
                            width="100">
                          {{ $value->date_created_format_1 }}
                        </td>
                      </tr>
                    @endforeach
                  </table>
                </div>
                <div class="card-footer d-flex justify-content-center">
                  {{ $lists->render() }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
