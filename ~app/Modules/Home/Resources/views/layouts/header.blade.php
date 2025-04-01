<section class="navbar-top px-2 py-2">
  <div class="d-flex justify-content-between container">
    <div>&nbsp;</div>
    <div id="google_translate_element"></div>
    <script type="text/javascript">
      function googleTranslateElementInit() {
        new google.translate.TranslateElement({
          pageLanguage: 'th',
          includedLanguages: 'en,ar,it,fr,ru,tr',
          layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
        }, 'google_translate_element');
      }
    </script>

    <script type="text/javascript"
            src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
  </div>
</section>

<nav class="navbar navbar-expand-lg navbar-light">
  <div class="container">
    <a class="navbar-brand"
       href="{{ URL::to('/') }}">
      <img src="{{ asset('assets/images/peo-logo.png') }}"
           width="360">
    </a>
    <button class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent"
            aria-expanded="false"
            aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-collapse collapse"
         id="navbarSupportedContent">
      <ul class="navbar-nav mb-lg-0 mb-2 ms-auto">
        <li class="nav-item">
          <a class="nav-link"
             href="{{ URL::to('/') }}">
            <i class="feather feather-home hor-icon"></i> หน้าแรก <span class="sr-only">(current)</span>
          </a>
        </li>

        @foreach ($menu_top as $level1)
          <li
            class="nav-item @if (isset($level1->children) && count($level1->children) > 0) dropdown @endif @if ($level1->is_level3) xdropdown-fluid @endif">
            @if (count($level1->children) == 0)
              <a class="nav-link"
                 href="{{ $level1->url }}"
                 target="{{ $level1->target }}">{{ $level1->name }}</a>
            @else
              <a class="nav-link dropdown-toggle"
                 href="#"
                 role="button"
                 data-bs-toggle="dropdown"
                 aria-haspopup="true"
                 aria-expanded="false">
                {{ $level1->name }}
              </a>

              @if ($level1->is_level3)
                <div class="dropdown-menu xdropdown-menu-fluid dropdown-menu-right">
                  <div class="row justify-content-center">
                    <div class="col-md-9">
                      <div class="row">
                        @foreach ($level1->children as $level2)
                          <div class="col-md-3">
                            <h5>{{ $level2->name }}</h5>
                            <ul>
                              @foreach ($level2->children as $level3)
                                <li>
                                  <a class="dropdown-item"
                                     href="{{ $level3->url }}"
                                     target="{{ $level3->target }}">{{ $level3->name }}</a>
                                </li>
                              @endforeach
                            </ul>
                          </div>
                        @endforeach
                      </div>
                    </div>
                  </div>
                </div>
              @else
                <ul class="dropdown-menu">
                  @foreach ($level1->children as $level2)
                    <li>
                      <a class="dropdown-item"
                         href="{{ $level2->url }}"
                         target="{{ $level2->target }}">
                        {{ $level2->name }}
                      </a>
                    </li>
                  @endforeach
                </ul>
              @endif
            @endif
          </li>
        @endforeach
      </ul>
    </div>
  </div>
</nav>
