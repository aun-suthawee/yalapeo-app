@extends('home::layouts.master')

@section('app-content')
    <section id="init" class="bg-light py-5">
        <div>
            <div class="row align-items-center">
                <div class="col-md-3 col-sm-6 text-center mb-4 mb-md-0">
                    <div class="executive-profile">
                        <img src="{{ asset('assets/images/ท่านศึกษาธิการ.png') }}" alt="ศึกษาธิการจังหวัดยะลา"
                            class="img-fluid" style="max-height: 400px; border-radius: 0; border: none;">
                        <h4 class="mt-3 mb-1">นายสมพงศ์ พละไชย</h4>
                        <p class="text-primary fw-bold mb-0">ศึกษาธิการจังหวัดยะลา</p>
                    </div>
                </div>

                <div class="col-md-4 offset-md-1 text-center">
                    <h2 class="title">
                        สำนักงานศึกษาธิการจังหวัดยะลา
                    </h2>
                    <p>การบริหารและจัดการศึกษาแบบบูรณาการมีประสิทธิภาพ<br>
                        ผู้เรียนได้รับการเรียนรู้ตลอดชีวิตอย่างมีคุณภาพ<br>
                        และมีทักษะที่จำเป็นในศตวรรษที่ 21</p>
                </div>

                <div class="col-md-3 offset-md-1 col-sm-6 text-center mb-4 mb-md-0">
                    <div class="executive-profile">
                        <img src="{{ asset('assets/images/รองศึกษาธิการ.png') }}" alt="รองศึกษาธิการจังหวัดยะลา"
                            class="img-fluid" style="max-height: 400px; border-radius: 0; border: none;">
                        <h4 class="mt-3 mb-1">นางอภิญญา แพทย์ศรี</h4>
                        <p class="text-primary fw-bold mb-0">รองศึกษาธิการจังหวัดยะลา</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="wp-slide">
        <div class="flexslider image-slider">
            <ul class="slides">
                @foreach ($banners as $value)
                    <li>
                        <a href="{{ route('banner.large.show', $value->slug) }}">
                            <img src="{{ $value->cover }}" width="1920" height="476" class="aos-init aos-animate"
                                data-aos="fade-up" data-aos-offset="200" data-aos-delay="5" data-aos-duration="1000"
                                data-aos-easing="ease-in-out" data-aos-mirror="true" data-aos-once="true"
                                data-aos-anchor-placement="top-center" alt="{{ Request::getHost() }}">
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    <section id="outer-link">
        <div class="container-fluid">
            <div class="pt-4 pt-md-5 pb-md-5">
                <h2 class="title text-center">
                    <span class="text-highlight">E-service</span>
                </h2>
                <div class="row d-flex justify-content-md-center mb-md-0 mb-2">
                    @foreach ($outerlinks as $value)
                        <div class="col-6 col-md-auto col-sm-12 text-center">
                            <a href="{{ $value['link'] }}" target="_blank" class="outerlinks hvr-grow-shadow"
                                style="--background: url('{{ $value['logo'] }}');">
                                <span>{{ $value['title'] }}</span>
                                <span>{!! $value['description'] ?? '' !!}</span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="lasted-news">
        <div class="container-fluid px-md-5 py-5">
            <div class="row">
                <div class="col-md-8">
                    <h2 class="title text-center">
                        <span class="text-highlight">ข่าวกิจกรรม</span>ศธจ.ยะลา
                    </h2>
                    <div class="row justify-content-center mt-0 mt-md-5">
                        <div class="col-md-6">
                            <div class="card overflow-hidden">
                                <img src="{{ $lasted_news[0]->cover }}" class="img-fluid" alt="{{ Request::getHost() }}">
                                <div class="card-body">
                                    <h5 class="card-title focus-title cut-text-2">
                                        <a href="{{ $lasted_news[0]->publish_url }}"
                                            target="{{ $lasted_news[0]->target }}">
                                            {{ $lasted_news[0]->title }}
                                        </a>
                                    </h5>
                                    <p class="card-detail focus-detail">
                                        {{ $lasted_news[0]->description }}
                                    </p>
                                    <p class="card-text">
                                        <i class="feather feather-calendar hor-icon"></i>
                                        {{ $lasted_news[0]->date_publish_format_1 }} | อ่าน {{ $lasted_news[0]->view }}
                                        ครั้ง
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            @for ($n = 1; $n < count($lasted_news); $n++)
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <img src="{{ $lasted_news[$n]->cover }}" class="img-fluid"
                                                            alt="{{ Request::getHost() }}">
                                                    </div>
                                                    <div class="col-md-7 d-flex justify-content-between flex-column">
                                                        <h5 class="card-title cut-text-2">
                                                            <a href="{{ $lasted_news[$n]->publish_url }}"
                                                                target="{{ $lasted_news[$n]->target }}">
                                                                {{ $lasted_news[$n]->title }}
                                                            </a>
                                                        </h5>
                                                        <p class="card-detail">
                                                            {{ $lasted_news[$n]->description }}
                                                        </p>
                                                        <p class="card-text">
                                                            <i class="feather feather-calendar hor-icon"></i>
                                                            {{ $lasted_news[$n]->date_publish_format_1 }} | อ่าน
                                                            {{ $lasted_news[$n]->view }} ครั้ง
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endfor

                            <div class="text-end">
                                <a href="{{ route('news.index') }}/?type=7" class="btn btn-danger">
                                    ดูทั้งหมด
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($box->detail_1 != '')
                    <div class="col-md-4 mt-5 mt-md-0">
                        <div class="box-content h-100 d-flex flex-column justify-content-center p-3">
                            {!! $box->detail_1 !!}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="pt-4 pt-md-5 pb-4 pb-md-5">
        <div class="container-fluid px-md-5">
            <div class="row">
                <div class="col-md-6 mt-4 mt-md-0">
                    <div class="card h-100">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between mb-3">
                                <div class="card-title">
                                    <div class="h5 fw-bold">
                                        กระดานสนทนา
                                    </div>
                                </div>
                                <div>
                                    <a href="{{ route('webboard.create') }}"
                                        class="btn btn-outline-warning btn-sm rounded-2">
                                        <i class="fas fa-plus"></i> ตั้งกระทู้ใหม่
                                    </a>
                                    <a href="{{ route('webboard.index') }}"
                                        class="btn btn-outline-success btn-sm rounded-2">
                                        ดูทั้งหมด <i class="feather feather-arrow-right"></i>
                                    </a>
                                </div>
                            </div>

                            <table class="table">
                                @foreach ($webboard as $value)
                                    <tr>
                                        <td>
                                            <a href="{{ $value->url }}" class="text-decoration-none">
                                                {{ $value->title }}
                                            </a>
                                        </td>
                                        <td>{{ $value->author }}</td>
                                        <td width="100" class="text-nowrap">
                                            {{ $value->created_at }}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mt-4 mt-md-0">
                    <x-eit-iit />
                </div>
            </div>
        </div>
    </section>

    <section id="mis">
        <div class="container">
            <div class="pt-4 pt-md-5 pb-md-5">
                <h2 class="title text-center">
                    <span class="text-highlight">บริการหน่วยงานภายใน</span>
                </h2>
                <div class="row justify-content-center">
                    @foreach ($global_menus as $value)
                        <div class="col-6 col-sm-6 col-md-2 mt-5 text-center">
                            <div class="menu-item-clickable" data-bs-toggle="modal" data-bs-target="#menuModal{{ $loop->index }}">
                                <img src="{{ $value['cover'] }}" alt="{{ Request::getHost() }}" height="100px"
                                    class="menu-image-clickable">
                                <p class="menu-text-clickable">
                                    {!! $value['title'] !!}
                                    <small class="d-block fw-lighter">
                                        {!! $value['sub_title'] !!}
                                    </small>
                                </p>
                            </div>

                            <div class="modal fade" id="menuModal{{ $loop->index }}" tabindex="-1"
                                aria-labelledby="menuModalLabel{{ $loop->index }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="menuModalLabel{{ $loop->index }}">
                                                {!! $value['title'] !!}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <p>กรุณาเลือกประเภทข้อมูลที่ต้องการ</p>
                                            <div class="row mt-4">
                                                <div class="col-6">
                                                    <a href="{{ $value['url'] }}" class="btn btn-primary w-100">
                                                        <i class="fas fa-users mb-2"></i>
                                                        <span class="d-block">ข้อมูลบุคลากร</span>
                                                    </a>
                                                </div>
                                                <div class="col-6">
                                                    <a href="{{ $value['url'] }}/downloads"
                                                        class="btn btn-success w-100">
                                                        <i class="fa-solid fa-file-pdf mb-2"></i>
                                                        <span class="d-block">แบบฟอร์มกฏหมาย/ระเบียบ</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <style>
                .menu-item-clickable {
                    cursor: pointer;
                }
                .menu-image-clickable {
                    transition: transform 0.3s ease;
                }
                .menu-item-clickable:hover .menu-image-clickable {
                    transform: scale(1.05);
                }
                .menu-text-clickable {
                    color: #212529;
                    transition: color 0.3s ease;
                }
                .menu-item-clickable:hover .menu-text-clickable {
                    color: #0d6efd;
                }
            </style>
    </section>

    <section id="news-publish">
        <div class="container py-5">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="title text-center">
                        <span class="text-highlight">ข่าวสาร</span>ล่าสุด
                    </h2>

                    <ul class="nav nav-tabs justify-content-center news-nav-tabs pb-5">
                        @foreach ($news as $index => $value)
                            <li class="nav-item">
                                <a class="nav-link @if ($index == 0) active @endif" data-bs-toggle="tab"
                                    href="#tab{{ $index }}">
                                    {{ $value['type_name'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="tab-content">
                        @foreach ($news as $index => $list)
                            <div class="tab-pane fade show @if ($index == 0) active @endif container"
                                id="tab{{ $index }}" aria-labelledby="tab-{{ $index }}">
                                <div class="row g-4">
                                    @foreach ($list['lists'] as $key => $value)
                                        <div
                                            class="col-12 col-md-3 @if ($key >= 3) d-none d-md-block @endif">
                                            <div class="card card-news overflow-hidden">
                                                <img src="{{ $value->cover }}" class="img-height img-bd-5"
                                                    alt="{{ Request::getHost() }}">
                                                <div class="card-body">
                                                    <h5 class="card-title cut-text-2">
                                                        <a href="{{ $value->publish_url }}"
                                                            target="{{ $value->target }}">
                                                            {{ $value->title }}
                                                        </a>
                                                    </h5>
                                                    <p class="card-detail">
                                                        {{ $value->description }}
                                                    </p>
                                                    <p class="card-text">
                                                        <i class="feather feather-calendar hor-icon"></i>
                                                        {{ $value->date_publish_format_1 }} | อ่าน {{ $value->view }}
                                                        ครั้ง
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mt-5 text-center">
                                        <a href="{{ route('news.index') }}/?type=7" class="btn btn-danger">
                                            ดูทั้งหมด
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- โซนข่าวจัดซื้อจัดจ้าง --}}
    <section>
        <div class="container py-5">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="title text-center">
                        <span class="text-highlight">ข่าวจัดซื้อ</span>จัดจ้าง
                    </h2>

                    <ul class="news-list">
                        @foreach ($news_purchase as $value)
                            <li>
                                <a href="{{ $value->publish_url }}" target="{{ $value->target }}">
                                    <i class="typcn typcn-news me-1"></i>
                                    {{ $value->title }}
                                </a>
                            </li>
                        @endforeach
                        <div class="text-end">
                            <a href="{{ route('news.index') }}/?type=5" class="btn btn-danger mt-3">
                                ดูทั้งหมด
                            </a>
                        </div>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h2 class="title text-center">
                        <span class="text-highlight">สรุปผล</span>จัดซื้อจัดจ้างรายเดือน
                    </h2>

                    <ul class="news-list">
                        @foreach ($news_purchase_summary as $value)
                            <li>
                                <a href="{{ $value->publish_url }}" target="{{ $value->target }}">
                                    <i class="typcn typcn-news me-1"></i>
                                    {{ $value->title }}
                                </a>
                            </li>
                        @endforeach
                        <div class="text-end">
                            <a href="{{ route('news.index') }}/?type=9" class="btn btn-danger mt-3">
                                ดูทั้งหมด
                            </a>
                        </div>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="diff diff-box4 pt-sm-auto bg-light">
        <div class="container">
            <h1 class="text-uppercase mb-5 text-center">
                <span class="text-highlight">วิดิโอ</span>ล่าสุด
            </h1>
            <div class="row">
                <div class="col-md-8 col-sm-12 mb-sm-0 box-large mb-3">
                    <a href="{{ route('video.show', $videos[0]->slug) }}">
                        {!! $videos[0]->output !!}
                    </a>
                </div>
                <div class="col-md-4 col-sm-12 mb-sm-0 mb-3">
                    @foreach ($videos as $index => $value)
                        @if ($index >= 1)
                            <div class="row mb-3">
                                <div class="col-md-12 col-sm-12 mb-sm-0 box-small mb-3">
                                    <a href="{{ route('video.show', $value->slug) }}">
                                        {!! $value->output !!}
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('video.index') }}" class="btn btn-secondary">
                    วิดิโอทั้งหมด
                </a>
            </div>
        </div>
    </section>

    <section class="pt-4 pt-md-5">
        <div class="container">
            <h2 class="title text-center mb-4">
                <span class="text-highlight">สารสนเทศทางการศึกษา</span>จังหวัดยะลา
            </h2>

            <div class="row justify-content-center mb-4">
                <div class="col-md-6 text-center">
                    <div class="btn-group" role="group" aria-label="สลับแดชบอร์ด">
                        <button type="button" class="btn btn-primary active" id="dashboard1-btn"
                            onclick="showDashboard('dashboard1')">
                            สารสนเทศทางการศึกษา
                        </button>
                        <button type="button" class="btn btn-outline-primary" id="dashboard2-btn"
                            onclick="showDashboard('dashboard2')">
                            ข้อมูลติดตามเด็กนอกระบบ
                        </button>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center dashboard-container" id="dashboard1">
                <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden;">
                    <iframe
                        src="https://lookerstudio.google.com/embed/reporting/cce3a597-75c9-417f-885a-3c7dba974cbd/page/ZAkEF"
                        frameborder="0" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"
                        allowfullscreen
                        sandbox="allow-storage-access-by-user-activation allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox"></iframe>
                </div>
            </div>

            <div class="row justify-content-center dashboard-container" id="dashboard2" style="display:none;">
                <div style="position: relative; padding-bottom: 62.25%; height: 0; overflow: hidden;">
                    <iframe title="เด็กนอกระบบ 26-3"
                        src="https://app.powerbi.com/view?r=eyJrIjoiY2U3YjI3NDYtZjI2NC00N2M4LWE2NDItMjY0NzU4YmJmNTUwIiwidCI6ImZhNGU3MjMyLWUyODgtNDhmMS05MzMyLWM3N2Q4ZmVhNzhhNyIsImMiOjEwfQ%3D%3D"
                        frameborder="0" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"
                        allowFullScreen="true"></iframe>
                </div>
            </div>
        </div>

        <script>
            function showDashboard(dashboardId) {
                document.querySelectorAll('.dashboard-container').forEach(function(dashboard) {
                    dashboard.style.display = 'none';
                });

                document.getElementById(dashboardId).style.display = 'block';

                document.getElementById('dashboard1-btn').classList.remove('active');
                document.getElementById('dashboard1-btn').classList.remove('btn-primary');
                document.getElementById('dashboard1-btn').classList.add('btn-outline-primary');

                document.getElementById('dashboard2-btn').classList.remove('active');
                document.getElementById('dashboard2-btn').classList.remove('btn-primary');
                document.getElementById('dashboard2-btn').classList.add('btn-outline-primary');

                document.getElementById(dashboardId + '-btn').classList.remove('btn-outline-primary');
                document.getElementById(dashboardId + '-btn').classList.add('btn-primary');
                document.getElementById(dashboardId + '-btn').classList.add('active');
            }
        </script>
    </section>

    <section id="book">
        <div class="container py-5">
            <h2 class="title text-center text-light">
                <span class="text-highlight">หนังสือ</span>อิเล็กทรอนิกส์
            </h2>

            <div class="row persuade-read-box">
                <div class="col-md-12">
                    <div class="owl-persuade owl-carousel owl-theme">
                        @foreach ($books as $value)
                            <div class="item">
                                <a href="{{ $value->url }}" target="{{ $value->target }}">
                                    <img src="{{ $value->cover }}" class="img-fluid" />
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($box->detail_2 != '')
        <section class="pt-4 pt-md-5 pb-md-5">
            <div class="row">
                <div class="col-12 text-center">
                    {!! $box->detail_2 !!}
                </div>
            </div>
        </section>
    @endif

    <section id="assessment" data-aos="fade-up" data-aos-offset="200" data-aos-delay="5" data-aos-duration="1000"
        data-aos-easing="ease-in-out" data-aos-mirror="true" data-aos-once="true" data-aos-anchor-placement="top-center"
        class="aos-init aos-animate">
        <div class="container py-5">
            <h2 class="title text-center">
                <span class="text-highlight">แบบ</span>ประเมิน
            </h2>

            <div class="row d-flex justify-content-center">
                @foreach ($assessments as $value)
                    <div class="col-6 col-sm-6 col-md-4 col-lg-2 text-center">
                        <a href="{{ $value['url'] }}">
                            <img src="{{ $value['cover'] }}" class="img-bd-5 mb-3">
                            <span class="d-block">
                                {!! $value['title'] !!}
                            </span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
