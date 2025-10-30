<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sandbox - โรงเรียนพื้นที่นวัตกรรม')</title>

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

    {{-- <link rel="stylesheet"
        href="{{ asset('assets/plugins/node_modules/vanilla-cookieconsent/dist/cookieconsent.css') }}" media="print"
        onload="this.media='all'"> --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/common/css/bundle.min.css?v=' . time()) }}">

    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" /> --}}

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Sandbox Breadcrumb CSS -->
    <link rel="stylesheet" href="{{ asset('assets/common/css/sandbox-breadcrumb.css?v=' . time()) }}">

    <!-- Custom Styles -->
    @yield('stylesheet-content')

    <!-- ไวอาลัย - Mourning Style -->
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
        }

        /* ทำให้ทั้งเว็บเป็นขาวดำ */
        /* html {
            filter: grayscale(100%) brightness(0.95);
            -webkit-filter: grayscale(100%) brightness(0.95);
        } */
        
        /* ให้ภาพและวิดีโอเป็นขาวดำด้วย */
        /* img, video, iframe {
            filter: grayscale(100%);
            -webkit-filter: grayscale(100%);
        } */
        
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

    
</head>

<body>
    @include('home::layouts.header')
    {{-- @include('sandbox::layouts.header') --}}

    <!-- Ribbon ไวอาลัย -->
    <a href="/" class="wpm-ribbon" title="กลับหน้าแรก"> 
        <img src="https://bict.moe.go.th/wp-content/uploads/2025/10/wp-mourning-ribbon.png" alt="ไวอาลัย">
    </a>
    
    @include('sandbox::layouts.breadcrumb')

    @yield('content')

    <footer class="font-weight-light">
        <div class="container-fluid py-3">
            <div class="row">
                <div class="col-md-12 text-center">
                    Copyright © yalapeo.go.th All right reserved.
                </div>
            </div>
        </div>
    </footer>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>

    <!-- PDF.js Library for PDF thumbnails -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.10.111/pdf.min.js"></script>
    <script>
        // Configure PDF.js worker
        if (typeof pdfjsLib !== 'undefined') {
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.10.111/pdf.worker.min.js';
        }
    </script>

    <!-- Custom Scripts -->
    @yield('script-content')
</body>

</html>
