<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" ng-app="myApp">

<head>
    <title>สำนักงานศึกษาธิการจังหวัดยะลา | ศธจ.ยะลา</title>
    <meta name="description"
        content="เว็บไซต์ทางการของสำนักงานศึกษาธิการจังหวัดยะลา (ศธจ.ยะลา) ข้อมูลการศึกษา ข่าวสาร และบริการทางการศึกษาสำหรับประชาชนจังหวัดยะลา">
    <meta name="keywords"
        content="ศธจ.ยะลา, สำนักงานศึกษาธิการจังหวัดยะลา, การศึกษายะลา, ศึกษาธิการยะลา, ข้อมูลการศึกษายะลา, yalapeo, yalaedu, yala, education, yala education">

    <meta charset="utf-8">
    {{-- <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> --}}
    <title>{{ isset($body['title']) ? $body['title'] : '' }} - {{ $cacheMeta->title }}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    {{-- <meta name="app-base-url" content="{{ url('/') }}" />
    <script>
        window.APP_BASE_URL = window.APP_BASE_URL || '{{ url('/') }}';
    </script> --}}
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="author" content="{{ request()->getHttpHost() }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{ isset($body['title']) ? $body['title'] : '' }} - {{ $cacheMeta->title }}" />
    <meta property="og:description"
        content="{{ isset($body['description']) ? $body['description'] : '' }} - {{ $cacheMeta->description }}" />
    <meta property="og:image" content="{{ asset('assets/images/meta-image.png') }}" />

    <!-- Canonical and Alternate URLs for SEO -->
    <link rel="canonical" href="{{ url()->current() }}" />
    <meta property="og:site_name" content="สำนักงานศึกษาธิการจังหวัดยะลา" />
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:description"
        content="{{ isset($body['description']) ? $body['description'] : '' }} - {{ $cacheMeta->description }}" />
    <meta name="twitter:title"
        content="{{ isset($body['title']) ? $body['title'] : '' }} - {{ $cacheMeta->title }}" />

    <!-- Alternate language versions -->
    <link rel="alternate" href="{{ url()->current() }}" hreflang="th" />

    <!-- Paginations -->
    @if (isset($paginator) && $paginator->hasPages())
        @if ($paginator->onFirstPage())
            <link rel="next" href="{{ $paginator->nextPageUrl() }}" />
        @elseif($paginator->hasMorePages())
            <link rel="prev" href="{{ $paginator->previousPageUrl() }}" />
            <link rel="next" href="{{ $paginator->nextPageUrl() }}" />
        @else
            <link rel="prev" href="{{ $paginator->previousPageUrl() }}" />
        @endif
    @endif

    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
    {{-- <link href="https://fonts.googleapis.com/css2?family=Anuphan:wght@400;500;700&display=swap" rel="stylesheet"> --}}

    @if (isset($stylesheets))
        @foreach ($stylesheets as $stylesheet)
            <link rel="stylesheet" type="text/css" href="{{ $stylesheet }}">
        @endforeach
    @endif
    <link rel="stylesheet"
        href="{{ asset('assets/plugins/node_modules/vanilla-cookieconsent/dist/cookieconsent.css') }}" media="print"
        onload="this.media='all'">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/common/css/bundle.min.css?v=' . time()) }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "GovernmentOrganization",
          "name": "สำนักงานศึกษาธิการจังหวัดยะลา",
          "alternateName": "ศธจ.ยะลา",
          "url": "https://yalapeo.go.th/",
          "logo": "{{ asset('assets/images/logo.png') }}",
          "sameAs": [
            "https://www.facebook.com/yalaedu"
          ],
          "address": {
            "@type": "PostalAddress",
            "streetAddress": "3/4 ถนนอาคารสงเคราะห์ ตำบลสะเตง",
            "addressLocality": "อำเภอเมืองยะลา",
            "addressRegion": "ยะลา",
            "postalCode": "95000",
            "addressCountry": "TH"
          }
        }
    </script>

    <!-- ไวอาลัย - Mourning Style -->
    <style>
        /* ทำให้ทั้งเว็บเป็นขาวดำ */
        html {
            filter: grayscale(100%) brightness(0.95);
            -webkit-filter: grayscale(100%) brightness(0.95);
        }

        /* ให้ภาพและวิดีโอเป็นขาวดำด้วย */
        img,
        video,
        iframe {
            filter: grayscale(100%);
            -webkit-filter: grayscale(100%);
        }

        /* Ribbon ไวอาลัย */
        .wpm-ribbon {
            position: fixed;
            z-index: 99999999;
            left: -1rem;
            top: -0.8rem;
            max-width: 30%;
            cursor: pointer;
            -webkit-backface-visibility: hidden;
            filter: none !important;
            -webkit-filter: none !important;
        }

        /* ป้องกันไม่ให้ ribbon เป็นขาวดำ */
        .wpm-ribbon img {
            filter: none !important;
            -webkit-filter: none !important;
        }
    </style>

    @yield('stylesheet-content')
</head>

<body id="{{ isset($module_name) ? $module_name : '' }}">

    <!-- Ribbon ไวอาลัย -->
    <a href="/" class="wpm-ribbon" title="กลับหน้าแรก">
        <img src="https://bict.moe.go.th/wp-content/uploads/2025/10/wp-mourning-ribbon.png" alt="ไวอาลัย">
    </a>

    @include('home::layouts.header')

    <main>
        @yield('app-content')
    </main>

    <div class="position-fixed floating-action-menu text-end" style="bottom: 150px;right: 60px;">
        <div class="action-menu">

            {{--    <div class="floating-action"> --}}
            {{--      <div class="badge bg-dark">One Stop Service</div> --}}
            {{--      <a class="btn-floating btn btn-sm btn-warning" --}}
            {{--         href="https://www.dla.go.th/oss.htm" --}}
            {{--         target="_blank"> --}}
            {{--        <i class="fas fa-headset"></i> --}}
            {{--      </a> --}}
            {{--    </div> --}}
            <div class="floating-action">
                <div class="badge bg-dark">073-729828</div>
                <a class="btn-floating btn btn-sm btn-info" href="tel:073729828">
                    <i class="fas fa-phone"></i>
                </a>
            </div>
            <div class="floating-action">
                <div class="badge bg-dark">yalaedu01@gmail.com</div>
                <a class="btn-floating btn btn-sm btn-danger" href="mailto:yalaedu01@gmail.com">
                    <i class="fas fa-at"></i>
                </a>
            </div>
            {{-- <div class="floating-action">
      <div class="badge bg-dark">Line Official</div>
      <a class="btn-floating btn btn-sm btn-success">
        <i class="fab fa-line"></i>
      </a>
    </div> --}}
            <div class="floating-action">
                <div class="badge bg-dark">Facebook Messenger</div>
                <a class="btn-floating btn btn-sm btn-primary" href="http://m.me/1184810181627589" target="_blank">
                    <i class="fab fa-facebook-messenger"></i>
                </a>
            </div>
        </div>
        <div class="d-block action-button">
            <a class="btn btn-primary btn-floating"
                onclick="$(this).closest('div.floating-action-menu').toggleClass('active')">
                <i class="fas fa-comments bounceIn"></i>
            </a>
            {{-- Social Network --}}
        </div>
    </div>

    <section class="diff diff-box5">
        <div class="container">
            <div class="row justify-content-center">
                <!-- Column 1: Contact Information -->
                <div class="col-lg-4 col-md-6 col-sm-12 text-white mb-4">
                    <div class="footer-section h-100">
                        <h4 class="section-title border-bottom pb-2 mb-3">ติดต่อเรา</h4>
                        <div class="contact-info">
                            <div class="text-center text-md-start mb-3">
                                <img src="{{ asset('assets/images/footer-logo.png') }}" class="img-fluid"
                                    alt="{{ Request::getHost() }}" style="max-height: 80px;">
                            </div>
                            <p>
                                สำนักงานศึกษาธิการจังหวัดยะลา<br>
                                เลขที่ 3/4 ถนนอาคารสงเคราะห์ ตำบลสะเตง<br>
                                อำเภอเมืองยะลา จังหวัดยะลา 95000<br>
                                ทุกวัน จันทร์-ศุกร์ เวลา 08.30 น. – 16.30 น.
                            </p>
                            <p>โทรศัพท์: 0 7372 9828<br>
                                โทรสาร: 0 7372 9827<br>
                                ติดต่องานคุรุสภายะลา: 0 7329 9310<br>
                                อีเมล: yalaedu01@gmail.com
                            </p>

                            <div class="visitor-counter mt-3">
                                <h4 class="text-highlight">สถิติผู้เข้าชม</h4>
                                <script type="text/javascript" src="https://counter.websiteout.com/js/7/7/42055/1"></script>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Column 2: Facebook -->
                <div class="col-lg-4 col-md-6 col-sm-12 text-white mb-4">
                    <div class="footer-section h-100">
                        <h4 class="section-title border-bottom pb-2 mb-3">Facebook</h4>
                        <div class="facebook-container">
                            <div id="fb-root"></div>
                            <script async="1" defer="1" crossorigin="anonymous"
                                src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v23.0"></script>
                            <div class="fb-page" data-href="https://www.facebook.com/yalaedu" data-height="400"
                                data-small-header="false" data-adapt-container-width="true" data-hide-cover="false"
                                data-show-facepile="true" data-show-posts="true" data-width="">
                                <blockquote cite="https://www.facebook.com/yalaedu" class="fb-xfbml-parse-ignore">
                                    <a href="https://www.facebook.com/yalaedu">สำนักงานศึกษาธิการจังหวัดยะลา</a>
                                </blockquote>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Column 3: Map and Useful Links -->
                <div class="col-lg-4 col-md-12 col-sm-12 text-white mb-4">
                    <div class="footer-section h-100">
                        <h4 class="section-title border-bottom pb-2 mb-3">แผนที่</h4>
                        <div class="map-container mb-3">
                            <iframe width="100%" height="220" frameborder="0" scrolling="no" marginheight="0"
                                marginwidth="0"
                                src="https://maps.google.com/maps?width=100%25&amp;height=350&amp;hl=th&amp;q=+(%E0%B8%AA%E0%B8%B3%E0%B8%99%E0%B8%B1%E0%B8%81%E0%B8%87%E0%B8%B2%E0%B8%99%E0%B8%A8%E0%B8%B6%E0%B8%81%E0%B8%A9%E0%B8%B2%E0%B8%98%E0%B8%B4%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B8%88%E0%B8%B1%E0%B8%87%E0%B8%AB%E0%B8%A7%E0%B8%B1%E0%B8%94%E0%B8%A2%E0%B8%B0%E0%B8%A5%E0%B8%B2)&amp;t=&amp;z=16&amp;ie=UTF8&amp;iwloc=B&amp;output=embed">
                            </iframe>
                        </div>

                        <div class="useful-links mt-3">
                            <h4 class="border-bottom pb-2">ลิงก์ที่เป็นประโยชน์</h4>
                            <div class="row">
                                <div class="col-md-12">
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><a href="/เกี่ยวกับเรา" class="text-white"><i
                                                    class="fas fa-angle-right me-2"></i>เกี่ยวกับกระทรวง</a></li>
                                        <li class="mb-2"><a href="/news?type=7" class="text-white"><i
                                                    class="fas fa-angle-right me-2"></i>ข่าวสารกิจกรรม</a></li>
                                        <li class="mb-2"><a href="/news?type=5" class="text-white"><i
                                                    class="fas fa-angle-right me-2"></i>จัดซื้อจัดจ้าง</a></li>
                                        <li class="mb-2"><a
                                                href="/ช่องทางแจ้งเรื่องร้องเรียนการทุจริตและประพฤติมิชอบ"
                                                class="text-white"><i
                                                    class="fas fa-angle-right me-2"></i>แจ้งเรื่องร้องเรียนการทุจริตและประพฤติมิชอบ</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="d-md-none d-block mt-4">
                            <h4 class="border-bottom pb-2">ช่องทางการติดต่อ</h4>
                            <div class="text-center">
                                <a target="_blank" href="https://www.facebook.com/yalaedu" class="me-3">
                                    <span class="fa-stack">
                                        <i class="fas fa-circle fa-stack-2x"></i>
                                        <i class="fab fa-facebook-square fa-stack-1x fa-inverse"></i>
                                    </span>
                                </a>
                                <a target="_blank"
                                    href="https://lineit.line.me/share/ui?url=https%3A%2F%2Fyalapeo.moe.go.th%2F4ita-2567%2F"
                                    class="me-3">
                                    <span class="fa-stack">
                                        <i class="fas fa-circle fa-stack-2x"></i>
                                        <i class="fab fa-line fa-stack-1x fa-inverse"></i>
                                    </span>
                                </a>
                                <a target="_blank" href="tel:073-729828" class="me-3">
                                    <span class="fa-stack">
                                        <i class="fas fa-circle fa-stack-2x"></i>
                                        <i class="fas fa-phone-alt fa-stack-1x fa-inverse"></i>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="font-weight-light">
        <div class="container-fluid py-3">
            <div class="row">
                <div class="col-md-12 text-center">
                    Copyright © yalapeo.go.th All right reserved.
                </div>
            </div>
        </div>
    </footer>

    <!-- Essential javascripts for application to work-->
    <script src="{{ asset('assets/common/js/bundle.min.js') }}"></script>
    <script type="text/javascript"
        src="{{ asset('assets/plugins/node_modules/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    <script type="text/javascript"
        src="{{ asset('assets/plugins/node_modules/jquery-validation/dist/additional-methods.js') }}"></script>
    <script src="{{ asset('assets/plugins/node_modules/angular/angular.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/node_modules/jpkleemans-angular-validate/dist/angular-validate.min.js') }}">
    </script>
    <script src="{{ asset('assets/common/js/app/app.js') }}"></script>
    <script src="{{ asset('assets/plugins/node_modules/wowjs/dist/wow.min.js') }}"></script>
    <script defer src="{{ asset('assets/plugins/node_modules/vanilla-cookieconsent/dist/cookieconsent.js') }}"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>

    @if (isset($scripts))
        @foreach ($scripts as $script)
            @if (is_array($script))
                <script {{ $script['defer'] }} src="{{ $script['link'] }}" type="text/javascript"></script>
            @else
                <script src="{{ $script }}" type="text/javascript"></script>
            @endif
        @endforeach
    @endif
    @yield('script-content')
    <script src="{{ asset('assets/common/js/script.min.js') }}"></script>
</body>

</html>
