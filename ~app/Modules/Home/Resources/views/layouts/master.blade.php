<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" ng-app="myApp">

<head>

    <title>สำนักงานศึกษาธิการจังหวัดยะลา | ศธจ.ยะลา</title>
    <meta name="description"
        content="เว็บไซต์ทางการของสำนักงานศึกษาธิการจังหวัดยะลา (ศธจ.ยะลา) ข้อมูลการศึกษา ข่าวสาร และบริการทางการศึกษาสำหรับประชาชนจังหวัดยะลา">
    <meta name="keywords"
        content="ศธจ.ยะลา, สำนักงานศึกษาธิการจังหวัดยะลา, การศึกษายะลา, ศึกษาธิการยะลา, ข้อมูลการศึกษายะลา, yalapeo, yalaedu, yala, education, yala education">

    <meta charset="utf-8">
    <title>{{ isset($body['title']) ? $body['title'] : '' }} - {{ $cacheMeta->title }}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
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
    @if(isset($paginator) && $paginator->hasPages())
        @if($paginator->onFirstPage())
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
            <link rel="stylesheet" href="{{ $stylesheet }}">
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

    @yield('stylesheet-content')
</head>

<body id="{{ isset($module_name) ? $module_name : '' }}">

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
                <div class="col-md-auto col-xl-auto col-sm-12 text-white">
                    <img src="{{ asset('assets/images/footer-logo.png') }}" class="img-fluid me-3"
                        alt="{{ Request::getHost() }}">

                    <h4 class="mt-3">ติดต่อเรา</h4>
                    <p>
                        สำนักงานศึกษาธิการจังหวัดยะลา<br>
                        เลขที่ 3/4 ถนนอาคารสงเคราะห์ ตำบลสะเตง<br>
                        อำเภอเมืองยะลา จังหวัดยะลา 95000<br>
                        ทุกวัน จันทร์-ศุกร์ เวลา 08.30 น. – 16.30 น.
                    </p>

                    <h4 class="text-highlight mt-3">สถิติผู้เข้าชม</h4>
                    <div class="visitor-counter">
                        <script type="text/javascript" src="https://counter.websiteout.com/js/7/7/42055/1"></script>
                    </div>
                </div>
                <div class="col-md-auto col-xl-auto col-sm-12 text-white">
                    <h4 class="mt-3">กระทรวงศึกษาธิการ</h4>
                    <ul>
                        <li><a href="#">เกี่ยวกับกระทรวง</a></li>
                        <li><a href="#">ข่าวสารกิจกรรม</a></li>
                        <li><a href="#">การดำเนินงาน</a></li>
                        <li><a href="#">แผนผังเว็บไซต์</a></li>
                        <li><a href="#">ข่าวรับสมัครงาน</a></li>
                        <li><a href="#">จัดซื้อจัดจ้าง</a></li>
                    </ul>
                </div>
                <div class="col-md-auto col-xl-auto col-sm-12 text-white">
                    <h4 class="mt-3">แหล่งความรู้</h4>
                    <ul>
                        <li><a href="#">บทความที่น่าสนใจ</a></li>
                        <li><a href="#">Infographic</a></li>
                        <li><a href="#">บริการ Download การเรียนการสอน</a></li>
                        <li><a href="#">สถิติการศึกษา</a></li>
                        <li><a href="#">e-Book</a></li>
                        <li><a href="#">e-library</a></li>
                        <li><a href="#">งานวิจัย</a></li>
                    </ul>
                </div>
                <div class="col-md-auto col-xl-auto col-sm-12 text-white">
                    <h4 class="mt-3">ช่องทางการรับเรื่อง/ร้องเรียน</h4>
                    <ul>
                        <li><a href="#">แจ้งเรื่องร้องเรียนการทุจริตประพฤติมิชอบ</a></li>
                        <li><a href="#">การรับฟังความคิดเห็น</a></li>
                    </ul>

                    <h4 class="mt-3">Social Media</h4>
                    <p class="social-link">
                        <a target="_blank" href="https://www.facebook.com/yalaedu">
                            <span class="fa-stack">
                                <i class="fas fa-circle fa-stack-2x"></i>
                                <i class="fab fa-facebook-square fa-stack-1x fa-inverse"></i>
                            </span>
                        </a>
                        <a target="_blank"
                            href="https://lineit.line.me/share/ui?url=https%3A%2F%2Fyalapeo.moe.go.th%2F4ita-2567%2F">
                            <span class="fa-stack">
                                <i class="fas fa-circle fa-stack-2x"></i>
                                <i class="fab fa-line fa-stack-1x fa-inverse"></i>
                            </span>
                        </a>
                        <a target="_blank" href="tel:073-729828">
                            <span class="fa-stack">
                                <i class="fas fa-circle fa-stack-2x"></i>
                                <i class="fas fa-phone-alt fa-stack-1x fa-inverse"></i>
                            </span>
                        </a>
                    </p>
                    <p>
                        Support Browser: IE9, IE10, Chrome, FireFox
                    </p>
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
