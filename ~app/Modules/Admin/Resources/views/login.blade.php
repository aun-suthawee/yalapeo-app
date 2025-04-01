<!DOCTYPE html>
<html lang="en"
      ng-app="myApp">

<head>
  <meta charset="utf-8">
  <title>{{ isset($body['title']) ? $body['title'] . ' ::' . env('APP_NAME') . '::' : 'Admin Panel' }}</title>
  <meta content="IE=edge"
        http-equiv="X-UA-Compatible">
  <meta content="{{ csrf_token() }}"
        name="csrf-token"/>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"
        name="viewport">
  <meta content="{{ URL::to('/') }}"
        name="author">
  <meta content=""
        name="description">
  <!-- Main CSS-->
  <link href="{{ asset('assets/@site_control/vali-theme/css/main.min.css') }}"
        rel="stylesheet"
        type="text/css">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Font-icon css-->
  <link href="{{ asset('assets/plugins/node_modules/@fortawesome/fontawesome-free/css/all.min.css') }}"
        rel="stylesheet"
        type="text/css">
  <link href="{{ asset('assets/@site_control/css/bundle.min.css') }}"
        rel="stylesheet"
        type="text/css">
</head>

<body class="app sidebar-mini">

<section class="material-half-bg">
  <div class="cover"></div>
</section>
<section class="login-content"
         title="YalaPeo Website">
  <div class="logo text-center">
    <h1 class="text-light">YalaPeo</h1>
    <p class="text-uppercase mb-0 mt-2 text-sm"><span class="bg-dark text-white-50 px-2 py-1">by Nakomah Studio</span>
    </p>
  </div>
  <div class="login-box">
    <form action="{{ route('admin.login') }}"
          class="login-form"
          method="POST">
      @csrf
      <h3 class="login-head"><i aria-hidden="true"
                                class="fa fa-user-circle fa-fw fa-lg"></i> เข้าสู่ระบบ</h3>
      <div class="form-group">
        <label class="control-label">ชื่อผู้ใช้งาน</label>
        <input autofocus
               class="form-control @error('username') is-invalid @enderror"
               name="username"
               placeholder="ระบุชื่อผู้ใช้งาน"
               required
               type="text"
               value="{{ old('username') }}">
        <x-error-message title="username"/>
      </div>
      <div class="form-group">
        <label class="control-label">รหัสผ่าน</label>
        <input class="form-control @error('password') is-invalid @enderror"
               name="password"
               placeholder="ระบุรหัสผ่าน"
               type="password">
        <x-error-message title="password"/>
      </div>
      <div class="form-group">
        <div class="utility">
          <div class="animated-checkbox">
            <label>
              <input {{ old('remember') ? 'checked' : '' }}
                     id="remember"
                     name="remember"
                     type="checkbox"><span class="label-text">จำฉันในระบบ</span>
            </label>
          </div>
        </div>
      </div>
      <div class="form-group btn-container">
        <button class="btn btn-primary btn-block"
                type="submit"><i class="fa fa-sign-in fa-lg fa-fw"></i>เข้าสู่ระบบ
        </button>
      </div>
    </form>
  </div>
</section>

<!-- Essential javascripts for application to work-->
<script src="{{ asset('assets/plugins/node_modules/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('assets/@site_control/vali-theme/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/@site_control/vali-theme/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/@site_control/vali-theme/js/main.min.js') }}"></script>
<!-- The javascript plugin to display page loading on top-->
<script src="{{ asset('assets/@site_control/vali-theme/js/plugins/pace.min.js') }}"></script>
</body>

</html>
