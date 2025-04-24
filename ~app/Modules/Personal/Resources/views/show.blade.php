@extends('home::layouts.master')

@section('app-content')
    <section class="page-contents">
        <div class="page-header">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-sm-12">
                        <h2>ทำเนียบบุคลากร {{ $level1->title }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-detail">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-sm-12 col-md-12">
                        <div class="py-5">
                            @if (isset($level1->tree_format) && count($level1->tree_format))
                                @foreach ($level1->tree_format as $trees)
                                    <div class="row justify-content-center">
                                        @foreach ($trees as $value)
                                            <div class="col-md-4 col-sm-12">
                                                <div class="box-personal">
                                                    <div class="box-personal-cover">
                                                        <img src="{{ $value->cover }}">
                                                    </div>
                                                    <div class="fw-bold py-1">
                                                        {{ $value['title'] }} <br>
                                                        {{ $value['position'] }}
                                                    </div>
                                                    @if ($value['description'] != '')
                                                        <p class="mb-0">
                                                            {{ $value['description'] }}
                                                        </p>
                                                    @endif
													<div class="py-1">
                                                        <i class="fa fa-phone"></i>
                                                        {{ $value['tel'] }} <br>
                                                        <i class="fa fa-envelope"></i>
                                                        {{ $value['email'] }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
