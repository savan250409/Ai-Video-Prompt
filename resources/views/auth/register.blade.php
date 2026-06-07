<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>Register — {{ config('app.name') }}</title>
  <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
  <link rel="icon" href="{{ asset('kaiadmin/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />

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
    body { background: linear-gradient(135deg, #1a2035 0%, #1e3a5f 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Public Sans', sans-serif; }
    .register-wrapper { width: 100%; max-width: 480px; padding: 20px; }
    .register-card { background: #fff; border-radius: 20px; box-shadow: 0 25px 70px rgba(0,0,0,.45); padding: 44px 40px; }
    .register-logo { text-align: center; margin-bottom: 28px; }
    .register-logo .brand-icon { width: 60px; height: 60px; background: linear-gradient(135deg,#1572e8,#6610f2); border-radius: 16px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 14px; }
    .register-logo .brand-icon i { font-size: 26px; color: #fff; }
    .register-logo h4 { color: #1a2035; font-weight: 700; font-size: 22px; margin-bottom: 4px; }
    .register-logo p { color: #9a9a9a; font-size: 14px; margin-bottom: 0; }
    .form-label { font-weight: 600; font-size: 13px; color: #444; margin-bottom: 6px; }
    .form-control { border-radius: 10px; padding: 11px 14px; font-size: 14px; border-color: #e0e0e0; transition: border-color .2s, box-shadow .2s; }
    .form-control:focus { border-color: #1572e8; box-shadow: 0 0 0 3px rgba(21,114,232,.12); }
    .input-icon { position: relative; }
    .input-icon .form-control { padding-right: 42px; }
    .input-icon .icon { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: #aaa; font-size: 15px; pointer-events: none; }
    .btn-register { background: linear-gradient(90deg,#1572e8,#6610f2); border: none; width: 100%; padding: 13px; font-size: 15px; font-weight: 600; letter-spacing: .4px; border-radius: 10px; color: #fff; transition: opacity .2s; }
    .btn-register:hover { opacity: .88; color: #fff; }
    .login-link { text-align: center; margin-top: 22px; font-size: 14px; color: #888; }
    .login-link a { color: #1572e8; font-weight: 600; text-decoration: none; }
    .login-link a:hover { text-decoration: underline; }
    .strength-bar { height: 4px; border-radius: 4px; transition: width .3s, background .3s; margin-top: 6px; }
    .section-divider { display: flex; align-items: center; gap: 10px; margin: 20px 0 16px; color: #bbb; font-size: 12px; }
    .section-divider::before, .section-divider::after { content: ''; flex: 1; height: 1px; background: #ebebeb; }
  </style>
</head>
<body>
<div class="register-wrapper">
  <div class="register-card">
    <div class="register-logo">
      <div class="brand-icon"><i class="fas fa-user-plus"></i></div>
      <h4>Create Account</h4>
      <p>Join {{ config('app.name') }} — it's free!</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
      @csrf

      <div class="mb-3">
        <label for="name" class="form-label">Full Name</label>
        <div class="input-icon">
          <input id="name" type="text"
            class="form-control @error('name') is-invalid @enderror"
            name="name" value="{{ old('name') }}"
            required autocomplete="name" autofocus
            placeholder="John Doe" />
          <i class="fas fa-user icon"></i>
          @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <div class="input-icon">
          <input id="email" type="email"
            class="form-control @error('email') is-invalid @enderror"
            name="email" value="{{ old('email') }}"
            required autocomplete="email"
            placeholder="you@example.com" />
          <i class="fas fa-envelope icon"></i>
          @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <div class="section-divider">Security</div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <div class="input-icon">
          <input id="password" type="password"
            class="form-control @error('password') is-invalid @enderror"
            name="password" required autocomplete="new-password"
            placeholder="Min. 8 characters"
            oninput="checkStrength(this.value)" />
          <i class="fas fa-lock icon"></i>
          @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div id="strength-bar" class="strength-bar" style="width:0;background:#e74c3c;"></div>
        <div id="strength-text" style="font-size:11px;color:#aaa;margin-top:3px;"></div>
      </div>

      <div class="mb-4">
        <label for="password-confirm" class="form-label">Confirm Password</label>
        <div class="input-icon">
          <input id="password-confirm" type="password"
            class="form-control"
            name="password_confirmation"
            required autocomplete="new-password"
            placeholder="Re-enter password" />
          <i class="fas fa-shield-alt icon"></i>
        </div>
      </div>

      <button type="submit" class="btn btn-register">
        <i class="fas fa-user-check me-2"></i> Create Account
      </button>
    </form>

    <div class="login-link">
      Already have an account? <a href="{{ route('login') }}">Sign in</a>
    </div>
  </div>
</div>

<script src="{{ asset('kaiadmin/js/core/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('kaiadmin/js/core/bootstrap.min.js') }}"></script>
<script>
function checkStrength(val) {
  var bar = document.getElementById('strength-bar');
  var txt = document.getElementById('strength-text');
  var score = 0;
  if (val.length >= 8) score++;
  if (/[A-Z]/.test(val)) score++;
  if (/[0-9]/.test(val)) score++;
  if (/[^A-Za-z0-9]/.test(val)) score++;
  var pct = [0, 25, 50, 75, 100][score];
  var colors = ['#e74c3c','#e74c3c','#f39c12','#2ecc71','#1572e8'];
  var labels = ['','Weak','Fair','Good','Strong'];
  bar.style.width = pct + '%';
  bar.style.background = colors[score];
  txt.innerText = score > 0 ? labels[score] : '';
  txt.style.color = colors[score];
}
</script>
</body>
</html>
