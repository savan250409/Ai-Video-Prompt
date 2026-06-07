<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>@yield('title', config('app.name')) — NGD Admin</title>
  <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
  <link rel="icon" href="{{ asset('kaiadmin/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <script src="{{ asset('kaiadmin/js/plugin/webfont/webfont.min.js') }}"></script>
  <script>
    WebFont.load({
      google: { families: ["Public Sans:300,400,500,600,700"] },
      custom: {
        families: ["Font Awesome 5 Solid","Font Awesome 5 Regular","Font Awesome 5 Brands","simple-line-icons"],
        urls: ["{{ asset('kaiadmin/css/fonts.min.css') }}"],
      },
      active: function () { sessionStorage.fonts = true; },
    });
  </script>

  {{-- Vendor CSS --}}
  <link rel="stylesheet" href="{{ asset('kaiadmin/css/bootstrap.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('kaiadmin/css/plugins.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('kaiadmin/css/kaiadmin.min.css') }}" />
  {{-- NGD Admin custom CSS (single file for entire panel) --}}
  <link rel="stylesheet" href="{{ asset('css/ngd-admin.css') }}" />
</head>
<body>
  <div class="wrapper">
    <!-- Sidebar -->
    <div class="sidebar" data-background-color="dark">
      <div class="sidebar-logo">
        <div class="logo-header" data-background-color="dark">
          <a href="{{ route('dashboard') }}" class="logo">
            <span style="color:#fff;font-weight:700;font-size:18px;letter-spacing:.5px;">
              <i class="fas fa-film me-2"></i>{{ config('app.name') }}
            </span>
          </a>
          <div class="nav-toggle">
            <button class="btn btn-toggle toggle-sidebar"><i class="gg-menu-right"></i></button>
            <button class="btn btn-toggle sidenav-toggler"><i class="gg-menu-left"></i></button>
          </div>
          <button class="topbar-toggler more"><i class="gg-more-vertical-alt"></i></button>
        </div>
      </div>

      <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
          <ul class="nav nav-secondary">
            <li class="nav-section">
              <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
              <h4 class="text-section">Main</h4>
            </li>

            <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
              <a href="{{ route('dashboard') }}">
                <i class="fas fa-home"></i>
                <p>Dashboard</p>
              </a>
            </li>

            <li class="nav-section">
              <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
              <h4 class="text-section">Ngendev</h4>
            </li>

            <li class="nav-item {{ request()->routeIs('ngendev.categories.*') ? 'active' : '' }}">
              <a href="{{ route('ngendev.categories.index') }}">
                <i class="fas fa-tags"></i>
                <p>Video Categories</p>
              </a>
            </li>

            <li class="nav-item {{ request()->routeIs('ngendev.videos.*') ? 'active' : '' }}">
              <a href="{{ route('ngendev.videos.index') }}">
                <i class="fas fa-video"></i>
                <p>Videos</p>
              </a>
            </li>

            <li class="nav-item {{ request()->routeIs('ngendev.api.list') ? 'active' : '' }}">
              <a href="{{ route('ngendev.api.list') }}">
                <i class="fas fa-code"></i>
                <p>API List</p>
              </a>
            </li>

            <li class="nav-section">
              <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
              <h4 class="text-section">Account</h4>
            </li>

            <li class="nav-item">
              <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i>
                <p>Logout</p>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <!-- End Sidebar -->

    <div class="main-panel">
      <!-- Navbar -->
      <div class="main-header">
        <div class="main-header-logo">
          <div class="logo-header" data-background-color="dark">
            <a href="{{ route('dashboard') }}" class="logo">
              <span style="color:#fff;font-weight:700;font-size:16px;">
                <i class="fas fa-film me-2"></i>{{ config('app.name') }}
              </span>
            </a>
            <div class="nav-toggle">
              <button class="btn btn-toggle toggle-sidebar"><i class="gg-menu-right"></i></button>
              <button class="btn btn-toggle sidenav-toggler"><i class="gg-menu-left"></i></button>
            </div>
            <button class="topbar-toggler more"><i class="gg-more-vertical-alt"></i></button>
          </div>
        </div>

        <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
          <div class="container-fluid">
            <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
              <li class="nav-item topbar-user dropdown hidden-caret">
                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                  <div class="avatar-sm">
                    <img src="{{ asset('kaiadmin/img/profile.jpg') }}"
                         alt="NGD Admin Avatar"
                         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(auth('admin')->user()->name) }}&background=1572e8&color=fff'"
                         class="avatar-img rounded-circle" />
                  </div>
                  <span class="profile-username">
                    <span class="op-7">Hi,</span>
                    <span class="fw-bold">{{ auth('admin')->user()->name }}</span>
                  </span>
                </a>
                <ul class="dropdown-menu dropdown-user dropdown-menu-right">
                  <div class="scroll-wrapper dropdown-user-scroll scrollbar-outer" style="position:relative;">
                    <div class="scroll-content">
                      <div class="user-box">
                        <div class="avatar-lg">
                          <img src="{{ asset('kaiadmin/img/profile.jpg') }}"
                               alt="NGD Admin Avatar"
                               onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(auth('admin')->user()->name) }}&background=1572e8&color=fff&size=80'"
                               class="avatar-img rounded" />
                        </div>
                        <div class="u-text">
                          <h4>{{ auth('admin')->user()->name }}</h4>
                          <p class="text-muted">{{ auth('admin')->user()->email }}</p>
                        </div>
                      </div>
                      <div class="dropdown-divider"></div>
                      <li>
                        <a class="dropdown-item" href="#"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                          <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a>
                      </li>
                    </div>
                  </div>
                </ul>
              </li>
            </ul>
          </div>
        </nav>
      </div>
      <!-- End Navbar -->

      <div class="container-fluid" style="padding-top:0;">
        @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        @endif
        @if(session('error'))
          <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        @endif

        @yield('content')
      </div>

      <footer class="footer">
        <div class="container-fluid d-flex justify-content-between">
          <nav class="pull-left">
            <ul class="nav">
              <li class="nav-item">
                <a class="nav-link" href="#">{{ config('app.name') }}</a>
              </li>
            </ul>
          </nav>
          <div class="copyright">
            {{ date('Y') }}, made with <i class="fas fa-heart heart text-danger"></i> by
            <a href="#">NGD Admin</a>
          </div>
        </div>
      </footer>
    </div>
  </div>

  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
  </form>

  {{-- Vendor JS --}}
  <script src="{{ asset('kaiadmin/js/core/jquery-3.7.1.min.js') }}"></script>
  <script src="{{ asset('kaiadmin/js/core/popper.min.js') }}"></script>
  <script src="{{ asset('kaiadmin/js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset('kaiadmin/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
  <script src="{{ asset('kaiadmin/js/kaiadmin.js') }}"></script>

  {{-- NGD Admin: inject route URLs so the external JS file can use them --}}
  <script>
    window.NgdRoutes = {
      categoryUpdateType:   "{{ route('ngendev.categories.updateType') }}",
      categoryUpdateStatus: "{{ route('ngendev.categories.updateStatus') }}"
    };
  </script>
  {{-- NGD Admin custom JS (single file for entire panel) --}}
  <script src="{{ asset('js/ngd-admin.js') }}"></script>
</body>
</html>
