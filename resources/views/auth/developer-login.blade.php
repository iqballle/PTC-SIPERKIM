<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Developer — SIPERKIM</title>

  <!-- (Opsional) Tailwind CDN untuk utilitas kecil -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- CSS kustom dari folder public -->
  <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="font-[Inter] antialiased text-[#2f2f2f]">

  <main class="auth-wrap">
    <!-- KIRI: Hero + Logo -->
    <section class="auth-hero">
      <div class="auth-hero__overlay"></div>
      <img class="auth-hero__bg" src="{{ asset('images/hero.jpg') }}" alt="Latar perumahan">
      <img class="auth-hero__logo" src="{{ asset('images/auth-logo.png') }}" alt="SIPERKIM">
    </section>

    <!-- KANAN: Panel Form -->
    <section class="auth-panel">
      <div class="auth-card">
        <h1 class="auth-title">Login</h1>

        @if ($errors->any())
          <div class="alert-error">
            @foreach ($errors->all() as $error)
              <div>• {{ $error }}</div>
            @endforeach
          </div>
        @endif

        <form method="POST" action="{{ route('developer.login.attempt') }}" class="space-y-4">
          @csrf

          <div class="form-group">
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
          </div>

          <div class="form-group">
            <input type="password" name="password" placeholder="Password" required>
          </div>

          <button type="submit" class="btn-primary">Masuk</button>
        </form>

        <div class="below-form">
          <p class="muted">
            Belum Punya Akun?
            @if (Route::has('developer.register'))
              <a href="{{ route('developer.register') }}" class="link">Registrasi</a>
            @else
              <a href="#" class="link">Registrasi</a>
            @endif
          </p>



          <p class="muted small">
            <a href="{{ route('developer.login') }}" class="link">Login</a> Sebagai Developer
          </p>
        </div>
      </div>
    </section>
  </main>

</body>
</html>
