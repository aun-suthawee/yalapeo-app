@extends('home::layouts.master')

@section('app-content')
  <section class="page-contents">
    <div class="page-header">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-sm-12">
            <h2>{{ $result->title }}</h2>
          </div>
        </div>
      </div>
    </div>

    <div class="page-detail">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-sm-12 col-md-12">
            <div class="py-sm-5 py-2">
              <p class="text-center">
                {!! $result->output !!}
              </p>
              {!! $result->detail !!}
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
