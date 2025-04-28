@extends('home::layouts.master')

@section('app-content')
    @include('home::partials.executive-section')
    @include('home::partials.banner-slider')
    @include('home::partials.outer-links')
    <div id="infographics">
        @include('home::partials.image-box-slider')
    </div>
    @include('home::partials.webboard')
    @include('home::partials.personnel')
    <div id="articles">
        @include('home::partials.news-tabs')
    </div>
    <div id="purchase">
        @include('home::partials.purchase-news')
    </div>
    <div id="videos">
        @include('home::partials.videos')
    </div>
    @include('home::partials.tiktok-videos')
    <div id="statistics">
        @include('home::partials.dashboard')
    </div>
    <div id="ebooks">
        @include('home::partials.books')
    </div>
    @if ($box->detail_2 != '')
        <section id="downloads" class="pt-4 pt-md-5 pb-md-5">
            <div class="row">
                <div class="col-12 text-center">
                    {!! $box->detail_2 !!}
                </div>
            </div>
        </section>
    @endif
    <div id="assessment">
        @include('home::partials.assessment')
    </div>
@endsection
