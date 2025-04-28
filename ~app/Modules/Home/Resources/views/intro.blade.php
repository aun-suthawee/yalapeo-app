<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <title>สำนักงานศึกษาธิการจังหวัดยะลา | ศธจ.ยะลา</title>
    <meta name="description"
        content="เว็บไซต์ทางการของสำนักงานศึกษาธิการจังหวัดยะลา (ศธจ.ยะลา) ข้อมูลการศึกษา ข่าวสาร และบริการทางการศึกษาสำหรับประชาชนจังหวัดยะลา">
    <meta name="keywords"
        content="ศธจ.ยะลา, สำนักงานศึกษาธิการจังหวัดยะลา, การศึกษายะลา, ศึกษาธิการยะลา, ข้อมูลการศึกษายะลา, yalapeo, yalaedu, yala, education, yala education">


    <meta charset="utf-8">
    <title>{{ isset($body['title']) ? $body['title'] : '' }} - {{ $cacheMeta->title }}</title>
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="{{ csrf_token() }}" name="csrf-token" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta content="{{ request()->getHttpHost() }}" name="author">
    <link href="{{ asset('assets/images/favicon.ico') }}" rel="shortcut icon" />
    <meta content="{{ URL::current() }}" property="og:url" />
    <meta content="website" property="og:type" />
    <meta content="{{ isset($body['title']) ? $body['title'] : '' }} - {{ $cacheMeta->title }}" property="og:title" />
    <meta content="{{ isset($body['description']) ? $body['description'] : '' }} - {{ $cacheMeta->description }}"
        property="og:description" />
    <meta content="{{ asset('assets/images/meta-image.png') }}" property="og:image" />

    <link href="https://{{ request()->getHttpHost() }}" rel="canonical" />

    <meta content="congratulations-{{ date('Y') }}" property="og:site_name" />
    <meta content="summary" name="twitter:card" />
    <meta content="{{ isset($body['description']) ? $body['description'] : '' }} - {{ $cacheMeta->description }}"
        name="twitter:description" />
    <meta content="{{ isset($body['title']) ? $body['title'] : '' }} - {{ $cacheMeta->title }}"
        name="twitter:title" />
    <link href="{{ URL::current() }}" hreflang="th" rel="alternate" />
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/node_modules/bootstrap/dist/css/bootstrap.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/node_modules/animate.css/animate.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/node_modules/@fortawesome/fontawesome-free/css/all.min.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;700&display=swap" rel="stylesheet"> --}}
    <link href="{{ asset('assets/common/css/bundle.min.css?v=' . time()) }}" rel="stylesheet" type="text/css">
    <style type="text/css">
        html,
        body {
            background-color: rgb({{ $result->background }}) !important;
            background-image: none !important;
        }
    </style>

    <meta name="google-site-verification" content="LknSQzidBvbV45J0DPXpbnhhWYyUadC0wCitAeqLgCU" />

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
</head>

<body id="intro">
    <div class="container">
        <div class="row justify-content-center mt-4">
            <div class="col-md-10 col-sm-12 backdrop-fixed text-center">
                <img class="w-100 img-fluid shd-1" src="{{ $result->image }}">
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-12 text-center">
                <a class="btn btn-outline-success btn-lg" href="https://wellwishes.royaloffice.th/">ลงนามถวายพระพร</a>
                <a class="btn btn-success btn-lg" href="{{ route('home.intro.to.homepage') }}">เข้าสู่เว็บไซต์</a>
            </div>

            <div class="col-md-12 p-5 text-center">
                <h2 class="font-weight-bolder {{ (int) $result->background ? 'text-white' : 'text-secondary' }} ">
                    สำนักงานศึกษาธิการจังหวัดยะลา</h2>
                <p class="{{ (int) $result->background ? 'text-light' : 'text-secondary' }}">3/4 ถนนอาคารสงเคราะห์
                    ตำบลสะเตง
                    อำเภอเมืองยะลา จังหวัดยะลา 95000 <br>
                    โทรศัพท์: 073-729828 E-mail: yalaedu01@gmail.com<br>
                    Support Browser: IE9, IE10, Chrome, FireFox
                </p>
            </div>
        </div>
    </div>
</body>

</html>
