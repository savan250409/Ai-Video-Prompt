<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>NGD Admin Login — AI Prompt</title>
  <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
  <link rel="icon" href="{{ asset('img/ngd-logo.png') }}" type="image/png" />

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

  <link rel="stylesheet" href="{{ asset('kaiadmin/css/bootstrap.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('kaiadmin/css/plugins.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('kaiadmin/css/kaiadmin.min.css') }}" />
  <style>
    body {
      background: linear-gradient(135deg, #1a2035 0%, #1e3a5f 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Public Sans', sans-serif;
    }
    .login-wrapper { width: 100%; max-width: 420px; padding: 20px; }
    .login-card {
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 25px 70px rgba(0,0,0,.45);
      padding: 44px 40px;
    }
    .login-logo { text-align: center; margin-bottom: 32px; }
    .login-brand-logo {
      height: 64px; width: auto; max-width: 160px;
      object-fit: contain;
      background: #fff;
      border-radius: 14px;
      padding: 6px 12px;
      box-shadow: 0 4px 16px rgba(0,0,0,.12);
      margin-bottom: 14px;
      display: inline-block;
    }
    .brand-icon {
      width: 64px; height: 64px;
      background: linear-gradient(135deg, #1572e8, #6610f2);
      border-radius: 18px;
      display: inline-flex; align-items: center; justify-content: center;
      margin-bottom: 14px;
      box-shadow: 0 8px 24px rgba(21,114,232,.35);
    }
    .brand-icon i { font-size: 28px; color: #fff; }
    .login-logo h4 { color: #1a2035; font-weight: 700; font-size: 22px; margin-bottom: 4px; }
    .login-logo p  { color: #9a9a9a; font-size: 13px; margin-bottom: 0; }
    .form-label { font-weight: 600; font-size: 13px; color: #444; margin-bottom: 6px; }
    .form-control {
      border-radius: 10px; padding: 11px 42px 11px 14px;
      font-size: 14px; border-color: #e0e0e0;
      transition: border-color .2s, box-shadow .2s;
    }
    .form-control:focus { border-color: #1572e8; box-shadow: 0 0 0 3px rgba(21,114,232,.12); }
    .input-icon { position: relative; }
    .input-icon .icon {
      position: absolute; right: 14px; top: 50%;
      transform: translateY(-50%); color: #bbb; font-size: 15px; pointer-events: none;
    }
    .btn-login {
      background: linear-gradient(90deg, #1572e8, #6610f2);
      border: none; width: 100%; padding: 13px;
      font-size: 15px; font-weight: 600; letter-spacing: .4px;
      border-radius: 10px; color: #fff;
      transition: opacity .2s; margin-top: 4px;
    }
    .btn-login:hover { opacity: .88; color: #fff; }
    .admin-badge {
      display: inline-block; background: #f0f4ff;
      color: #1572e8; font-size: 11px; font-weight: 700;
      letter-spacing: .8px; padding: 3px 10px; border-radius: 20px;
      margin-bottom: 10px; text-transform: uppercase;
    }
  </style>
</head>
<body>
<div class="login-wrapper">
  <div class="login-card">

    <div class="login-logo">
      <img src="{{ asset('img/ngd-logo.png') }}" alt="NGD Technolab" class="login-brand-logo"
           onerror="this.style.display='none';this.nextElementSibling.style.display='block';">
      <div class="brand-icon" style="display:none;"><i class="fas fa-robot"></i></div>
      <span class="admin-badge"><i class="fas fa-shield-alt me-1"></i> NGD Admin Panel</span>
      <h4>AI Prompt</h4>
      <p>Sign in with your NGD Admin credentials</p>
    </div>

    @if ($errors->any())
      <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius:10px;font-size:13px;">
        <i class="fas fa-exclamation-triangle me-2"></i>
        @foreach ($errors->all() as $error)
          {{ $error }}<br>
        @endforeach
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
      @csrf

      <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <div class="input-icon">
          <input id="email" type="email"
            class="form-control @error('email') is-invalid @enderror"
            name="email" value="{{ old('email') }}"
            required autocomplete="email" autofocus
            placeholder="admin@gmail.com" />
          <i class="fas fa-envelope icon"></i>
        </div>
      </div>

      <div class="mb-4">
        <label for="password" class="form-label">Password</label>
        <div class="input-icon">
          <input id="password" type="password"
            class="form-control @error('password') is-invalid @enderror"
            name="password" required autocomplete="current-password"
            placeholder="••••••••" />
          <i class="fas fa-lock icon"></i>
        </div>
      </div>

      {{-- Always remember = persistent session --}}
      <input type="hidden" name="remember" value="1">

      <button type="submit" class="btn btn-login">
        <i class="fas fa-sign-in-alt me-2"></i> Sign In
      </button>
    </form>

  </div>
</div>

<script src="{{ asset('kaiadmin/js/core/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('kaiadmin/js/core/bootstrap.min.js') }}"></script>
</body>
</html>
