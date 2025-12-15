<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Dinas — SIPERKIM</title>

  <!-- Tailwind untuk utilitas dasar (opsional) -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- CSS kustom -->
  <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>

<body class="font-[Inter] antialiased text-[#2f2f2f] bg-white">
  <main class="auth-wrap">
    
    {{-- Bagian Kiri: Hero Gambar --}}
    <section class="auth-hero">
      <div class="auth-hero__overlay"></div>
      <img class="auth-hero__bg" src="{{ asset('images/hero.jpg') }}" alt="Latar Perumahan">
      <img class="auth-hero__logo" src="{{ asset('images/auth-logo.png') }}" alt="Logo SIPERKIM">
    </section>

    {{-- Bagian Kanan: Form Login --}}
    <section class="auth-panel">
      <div class="auth-card">
        <h1 class="auth-title">Login Dinas</h1>

        {{-- Pesan sukses atau error --}}
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

        {{-- Form Login --}}
        <form method="POST" action="{{ route('dinas.login.attempt') }}" class="space-y-4">
          @csrf

          <div class="form-group">
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Email Dinas" required autofocus>
          </div>

          <div class="form-group">
            <input type="password" name="password" placeholder="Password" required>
          </div>

          <button type="submit" class="btn-primary w-full">Masuk</button>
        </form>

        {{-- Tautan tambahan --}}
        <div class="below-form mt-6 text-center">
          <p class="muted small">
            Belum punya akun? 
            <a href="{{ route('dinas.register') }}" class="link">Daftar Dinas</a>
          </p>
          <p class="muted small mt-2">
            Login sebagai 
            <a href="{{ route('developer.login') }}" class="link">Developer</a>
          </p>
        </div>
      </div>
    </section>
  </main>
</body>
</html>