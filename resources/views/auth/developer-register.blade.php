<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register Developer — SIPERKIM</title>
  <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="font-[Inter] antialiased text-[#2f2f2f]">

  <main class="auth-wrap">
    <!-- Hero Section -->
    <section class="auth-hero">
      <div class="auth-hero__overlay"></div>
      <img class="auth-hero__bg" src="{{ asset('images/hero.jpg') }}" alt="Background Image">
      <img class="auth-hero__logo" src="{{ asset('images/auth-logo.png') }}" alt="SIPERKIM">
    </section>

    <!-- Auth Panel -->
    <section class="auth-panel">
      <div class="auth-card">
        <h1 class="auth-title">Register Developer</h1>

        <!-- Success or Error Messages -->
        @if (session('status'))
          <div class="alert-success">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
          <div class="alert-error">
            @foreach ($errors->all() as $error)
              <div>• {{ $error }}</div>
            @endforeach
          </div>
        @endif

        <!-- Registration Form -->
        <form method="POST" action="{{ route('developer.register.attempt') }}" class="space-y-6">
          @csrf
          <div class="form-group">
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama Pengembang" required class="form-input">
          </div>
          <div class="form-group">
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" required class="form-input">
          </div>
          <div class="form-group">
            <input type="password" name="password" placeholder="Password" required class="form-input">
          </div>
          <div class="form-group">
            <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required class="form-input">
          </div>
          <button type="submit" class="btn-primary">Buat Akun</button>
        </form>

        <div class="below-form text-center">
          <p class="muted small">Sudah punya akun? <a href="{{ route('developer.login') }}" class="link">Login</a></p>
        </div>
      </div>
    </section>
  </main>

</body>
</html>