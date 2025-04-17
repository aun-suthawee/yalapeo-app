@extends('home::layouts.master')

@section('app-content')
    @include('home::partials.executive-section')
    @include('home::partials.banner-slider')
    @include('home::partials.outer-links')
    @include('home::partials.image-box-slider')
    @include('home::partials.webboard')
    @include('home::partials.personnel')
    @include('home::partials.news-tabs')
    @include('home::partials.purchase-news')
    @include('home::partials.videos')
    @include('home::partials.tiktok-videos')
    @include('home::partials.dashboard')
    @include('home::partials.books')
    @if ($box->detail_2 != '')
        <section class="pt-4 pt-md-5 pb-md-5">
            <div class="row">
                <div class="col-12 text-center">
                    {!! $box->detail_2 !!}
                </div>
            </div>
        </section>
    @endif
    @include('home::partials.assessment')
@endsection
