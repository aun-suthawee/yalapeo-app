<!DOCTYPE html>
<html lang="en" ng-app="myApp">

<head>
    <meta charset="utf-8">
    <title>{{ isset($body['title']) ? $body['title'] . ' ::' . env('APP_NAME') . '::' : 'Admin Panel' }}</title>
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="{{ csrf_token() }}" name="csrf-token" />
    <meta content="{{ url('/') }}" name="app-base-url" />
    <script>
        window.APP_BASE_URL = window.APP_BASE_URL || '{{ url('/') }}';
    </script>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta content="{{ URL::to('/') }}" name="author">
    <link href="{{ asset('assets/images/favicon.ico') }}" rel="shortcut icon" />
    <meta content="" name="description">
    <!-- Main CSS-->
    <link href="{{ asset('assets/@site_control/vali-theme/css/main.css') }}" rel="stylesheet" type="text/css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Font-icon css-->
    <link href="{{ asset('assets/plugins/node_modules/@fortawesome/fontawesome-free/css/all.min.css') }}"
        rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/plugins/jquery-ui/ui-themeredmond/jquery-ui-1.10.4.custom.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/bootstrap-fileinput/css/fileinput.min.css') }}" rel="stylesheet"
        type="text/css">
    @if (isset($stylesheets))
        @foreach ($stylesheets as $stylesheet)
            <link href="{{ $stylesheet }}" rel="stylesheet">
        @endforeach
    @endif
    <link href="{{ asset('assets/@site_control/css/bundle.min.css') }}" rel="stylesheet" type="text/css">
    @yield('stylesheet-content')
</head>

<body class="app sidebar-mini">

    @include('admin::layouts.header')

    <main class="app-content">
        <div class="app-title">
            <div>
                <h1>{!! $body['app_title']['h1'] !!}</h1>
                <p>{!! $body['app_title']['p'] !!}</p>
            </div>

            @if (isset($breadcrumb))
                {!! breadcrumb($breadcrumb) !!}
            @endif
        </div>
        @yield('app-content')
    </main>

    {{-- Essential javascripts for application to work --}}
    <script src="{{ asset('assets/plugins/node_modules/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/@site_control/vali-theme/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/@site_control/vali-theme/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/@site_control/vali-theme/js/main.min.js') }}"></script>
    {{-- The javascript plugin to display page loading on top --}}
    <script src="{{ asset('assets/@site_control/vali-theme/js/plugins/pace.min.js') }}"></script>
    {{-- jquery ui --}}
    <script src="{{ asset('assets/plugins/jquery-ui/jquery-ui-1.10.4.custom.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/node_modules/angular/angular.min.js') }}"></script>
    <script src="{{ asset('assets/@site_control/js/app/app.js') }}"></script>
    <script src="{{ asset('assets/@site_control/js/jquery.print.js') }}"></script>

    {{-- krejee bootstrap-fileinput --}}
    <script src="{{ asset('assets/plugins/bootstrap-fileinput/js/plugins/purify.min.js') }}" type="text/javascript">
    </script>
    <script src="{{ asset('assets/plugins/bootstrap-fileinput/js/plugins/sortable.min.js') }}" type="text/javascript">
    </script>
    <script src="{{ asset('assets/plugins/bootstrap-fileinput/js/plugins/piexif.min.js') }}" type="text/javascript">
    </script>
    <script src="{{ asset('assets/plugins/bootstrap-fileinput/js/fileinput.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-fileinput/js/locales/th.js') }}"></script>

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
    <script src="{{ asset('assets/@site_control/js/script.min.js') }}"></script>
</body>

</html>
