{{-- resources/views/developer/rth/index.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>RTH - Penyiraman Otomatis (Developer) — SIPERKIM</title>

  <link rel="stylesheet" href="{{ asset('css/dev-dashboard.css') }}">
  @vite('resources/js/dashboard.js')
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">

  <style>
    :root{
      --brand:#5B7042; --brand2:#8AA66A; --ink:#0f172a; --muted:#6b7280;
      --line:#e5e7eb; --card:#ffffff; --shadow:0 12px 40px rgba(15,23,42,.08);
    }
    .page-wrap{max-width:1040px;margin:0 auto;padding-bottom:10px;}
    .hero{
      background: radial-gradient(1200px 400px at 10% 0%, rgba(138,166,106,.35), transparent 60%),
                  radial-gradient(900px 350px at 90% 10%, rgba(91,112,66,.25), transparent 55%),
                  linear-gradient(180deg, #ffffff, #ffffff);
      border:1px solid var(--line); border-radius:18px; padding:18px; box-shadow: var(--shadow);
    }
    .hero-top{display:flex;gap:14px;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;}
    .hero-title{margin:0;font-size:26px;font-weight:900;color:var(--ink);letter-spacing:-.2px;}
    .hero-sub{margin:6px 0 0;color:var(--muted);font-size:13px;line-height:1.55;max-width:72ch;}
    .kpis{display:flex;gap:10px;flex-wrap:wrap;align-items:center;margin-top:14px;}
    .chip{
      display:inline-flex;align-items:center;gap:8px;padding:7px 10px;border-radius:999px;
      border:1px solid var(--line);background:#fff;font-size:12px;font-weight:800;color:#334155;
    }
    .chip b{font-weight:900;color:var(--ink);}
    .chip.ok{background:#ecfdf3;border-color:#bbf7d0;color:#166534;}
    .chip.warn{background:#fef9c3;border-color:#fde68a;color:#854d0e;}

    .grid{display:grid;grid-template-columns:1.25fr .75fr;gap:14px;margin-top:14px;}
    @media(max-width:960px){.grid{grid-template-columns:1fr;}}
    .card{background:var(--card);border:1px solid var(--line);border-radius:18px;padding:16px;box-shadow:0 10px 26px rgba(15,23,42,.06);}
    .card h2{margin:0;font-size:16px;font-weight:900;color:var(--ink);}
    .muted{color:var(--muted);font-size:13px;line-height:1.55;margin-top:8px;}
    .hr{border:0;border-top:1px solid var(--line);margin:14px 0;}
    .row{display:flex;gap:10px;flex-wrap:wrap;align-items:center;}

    .btn{
      display:inline-flex;align-items:center;justify-content:center;gap:8px;border-radius:999px;padding:10px 14px;
      font-weight:900;font-size:13px;border:1px solid transparent;cursor:pointer;text-decoration:none;transition:.15s ease;
      user-select:none;
    }
    .btn:active{transform:translateY(1px);}
    .btn-primary{background:var(--brand);color:#fff;}
    .btn-secondary{background:#fff;color:#111827;border-color:var(--line);}
    .btn-wa{background:#16a34a;color:#fff;}
    .btn:disabled{opacity:.6;cursor:not-allowed;}

    .field{margin-top:12px;}
    .label{display:block;font-size:13px;font-weight:900;color:#0f172a;margin-bottom:6px;}
    .input{
      width:100%;border:1px solid #d1d5db;border-radius:14px;padding:11px 12px;font-size:14px;
      outline:none;background:#fff;transition:.15s ease;
    }
    .input:focus{border-color:var(--brand);box-shadow:0 0 0 3px rgba(91,112,66,.18);}
    .help{font-size:12px;color:var(--muted);margin-top:6px;line-height:1.45}

    .msg{border-radius:14px;padding:10px 12px;font-size:13px;margin-top:12px;border:1px solid transparent;}
    .msg.ok{background:#ecfdf3;border-color:#bbf7d0;color:#166534;}
    .msg.err{background:#fef2f2;border-color:#fecaca;color:#b91c1c;}

    .mini{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:12px;}
    @media(max-width:600px){.mini{grid-template-columns:1fr;}}
    .mini .box{border:1px solid var(--line);border-radius:16px;padding:12px;background:#fff;}
    .mini .k{font-size:12px;color:var(--muted);font-weight:800;}
    .mini .v{margin-top:6px;font-size:14px;font-weight:900;color:#0f172a;word-break:break-word;}
    .hint{background:#f8fafc;border:1px solid var(--line);border-radius:16px;padding:12px;color:#334155;font-size:12px;line-height:1.5;}
    .hint b{color:#0f172a;}
    code{background:#0b1220;color:#e2e8f0;padding:2px 6px;border-radius:8px;font-size:12px;}
  </style>
</head>

<body class="font-[Inter] antialiased text-[#2f2f2f]">
@php
  $deviceId = $deviceId ?? (auth()->user()->rth_device_id ?? null);
  $selectedPerumahanId = $selectedPerumahanId ?? (auth()->user()->rth_perumahan_id ?? null);
@endphp

<div id="wrapper" class="flex">
  {{-- SIDEBAR --}}
  <aside id="sidebar" class="sidebar">
    <div class="sidebar-header">
      <img src="{{ asset('images/logo-siperkim.png') }}" class="sidebar-logo" alt="SIPERKIM">
      <h3 class="sidebar-title">SIPERKIM<br><small>Developer</small></h3>
    </div>

    <ul class="sidebar-menu">
      <li class="{{ request()->routeIs('developer.dashboard') ? 'active' : '' }}">
        <a href="{{ route('developer.dashboard') }}">Dashboard</a>
      </li>
      <li class="{{ request()->routeIs('developer.perumahan.*') ? 'active' : '' }}">
        <a href="{{ route('developer.perumahan.index') }}">Data Perumahan saya</a>
      </li>
      <li class="{{ request()->routeIs('developer.permohonan.*') ? 'active' : '' }}">
        <a href="{{ route('developer.permohonan.index') }}">Permohonan Ke Dinas</a>
      </li>
      <li class="{{ request()->routeIs('developer.notifikasi.*') ? 'active' : '' }}">
        <a href="{{ route('developer.notifikasi.index') }}">Notifikasi & Revisi</a>
      </li>
      <li class="{{ request()->routeIs('developer.rth.*') ? 'active' : '' }}">
        <a href="{{ route('developer.rth.index') }}">RTH - Penyiraman Otomatis</a>
      </li>
      <li class="{{ request()->routeIs('developer.settings.*') ? 'active' : '' }}">
        <a href="{{ route('developer.settings.index') }}">Pengaturan</a>
      </li>
    </ul>
  </aside>

  <main id="content" class="content">
    <button id="sidebar-toggle" class="sidebar-toggle" type="button" aria-label="Toggle Sidebar">☰</button>

    <div class="topbar">
      <div class="page-wrap">

        {{-- HERO --}}
        <section class="hero">
          <div class="hero-top">
            <div>
              <h1 class="hero-title">RTH — Penyiraman Otomatis</h1>
              <p class="hero-sub">
                Pilih <b>Perumahan</b> terlebih dahulu, lalu isi <b>Device ID</b> (jika sudah punya alat).
                Setelah itu baru masuk ke <b>Monitoring</b>.
              </p>
              <div class="kpis">
                @if($selectedPerumahanId)
                  <span class="chip ok">🏘️ Perumahan dipilih</span>
                @else
                  <span class="chip warn">🏘️ Pilih Perumahan dulu</span>
                @endif

                @if($deviceId)
                  <span class="chip ok">✅ Device <b>{{ $deviceId }}</b></span>
                @else
                  <span class="chip warn">⚠️ Device ID belum diisi</span>
                @endif

                <span class="chip">Akun: <b>{{ auth()->user()->name ?? '-' }}</b></span>
              </div>
            </div>

            <div class="row">
              @if($selectedPerumahanId && $deviceId)
                <a class="btn btn-primary" href="{{ route('developer.rth.monitor') }}">📡 Buka Monitoring</a>
              @endif
              <a class="btn btn-secondary" href="{{ route('developer.settings.index') }}">⚙️ Pengaturan</a>
            </div>
          </div>

          @if (session('status'))
            <div class="msg ok">{{ session('status') }}</div>
          @endif

          @if ($errors->any())
            <div class="msg err">
              @foreach ($errors->all() as $e)
                <div>• {{ $e }}</div>
              @endforeach
            </div>
          @endif
        </section>

        {{-- GRID --}}
        <section class="grid">

          {{-- LEFT: SETUP --}}
          <section class="card">
            <h2>Setup Monitoring RTH</h2>
            <p class="muted">
              1) Pilih perumahan yang ingin dimonitor.<br>
              2) Jika sudah ada alat, masukkan Device ID.<br>
              3) Masuk monitoring.
            </p>

            <div class="mini">
              <div class="box">
                <div class="k">Perumahan terpilih</div>
                <div class="v">
                  @php
                    $selectedName = null;
                    if(!empty($perumahans)){
                      foreach($perumahans as $p){
                        if((int)$p->id === (int)$selectedPerumahanId){ $selectedName = $p->nama; break; }
                      }
                    }
                  @endphp
                  {{ $selectedName ?? '-' }}
                </div>
              </div>

              <div class="box">
                <div class="k">Device ID</div>
                <div class="v">{{ $deviceId ?? '-' }}</div>
              </div>
            </div>

            <div class="hr"></div>

            {{-- FORM BIND --}}
            <form action="{{ route('developer.rth.bind') }}" method="POST">
              @csrf

              <div class="field">
                <label class="label">Pilih Perumahan</label>
                <select class="input" name="perumahan_id" required>
                  <option value="">— pilih perumahan —</option>
                  @foreach(($perumahans ?? []) as $p)
                    <option value="{{ $p->id }}" @selected((int)$p->id === (int)$selectedPerumahanId)>
                      {{ $p->nama }} ({{ $p->status ?? 'pending' }})
                    </option>
                  @endforeach
                </select>
                <div class="help">
                  Hanya perumahan milik akun ini yang tampil. (Opsional: filter yang sudah disetujui dinas).
                </div>
              </div>

              <div class="field">
                <label class="label">Device ID (kalau sudah punya alat)</label>
                <input class="input" type="text" name="rth_device_id" value="{{ old('rth_device_id', $deviceId) }}"
                       placeholder="Contoh: dev_001">
                <div class="help">
                  Device ID harus sama dengan node Firebase: <b>/realtime/devices/dev_001</b>
                </div>
              </div>

              <div class="row" style="margin-top:12px;">
                <button class="btn btn-primary" type="submit">✅ Simpan</button>

                @if($selectedPerumahanId && $deviceId)
                  <a class="btn btn-secondary" href="{{ route('developer.rth.monitor') }}">📡 Buka Monitoring</a>
                @endif
              </div>
            </form>

            <div class="hr"></div>

            <div class="hint">
              <b>Catatan:</b><br>
              - Kalau device belum ada, kamu tetap bisa simpan perumahan dulu. Nanti isi Device ID belakangan.<br>
              - Di halaman monitoring nanti kita tampilkan nama perumahan terpilih + status alat.
            </div>
          </section>

          {{-- RIGHT: CTA --}}
          <aside class="card">
            <h2>Daftarkan Perumahan</h2>
            <p class="muted">
              Belum ada perumahan di daftar? Silakan daftarkan perumahan terlebih dahulu, lalu tunggu verifikasi dinas.
            </p>

            <div class="row" style="margin-top:12px;">
              <a class="btn btn-primary" href="{{ route('developer.perumahan.create') }}">➕ Daftarkan Perumahan</a>
              <a class="btn btn-secondary" href="{{ route('developer.perumahan.index') }}">📄 Lihat Perumahan</a>
            </div>

            <div class="hr"></div>

            <h2 style="margin-top:0;">Belum punya alat?</h2>
            <p class="muted">
              Hubungi admin untuk pemasangan alat RTH (sensor + pompa + internet) agar bisa dimonitoring.
            </p>

            @php
              $waNumber = "6281234567890"; // ganti nomor WA kamu
              $waText   = rawurlencode(
                "Halo admin SIPERKIM, saya ingin pemasangan alat RTH (penyiraman otomatis). ".
                "Nama Developer: ".(auth()->user()->name ?? "-").", Email: ".(auth()->user()->email ?? "-")
              );
              $waLink   = "https://wa.me/{$waNumber}?text={$waText}";
            @endphp

            <div class="row" style="margin-top:12px;">
              <a class="btn btn-wa" href="{{ $waLink }}" target="_blank" rel="noopener">💬 Chat WhatsApp</a>
            </div>

            <div class="hr"></div>

            <div class="hint">
              <b>Monitoring RTH Anda:</b><br>
              <code>Pantau Penyiraman Tanaman anda pada ruang terbuka hijau di pada perumahan, Monitoring Penyiraman Secara online dan realtime</code>
          </aside>

        </section>
      </div>
    </div>
  </main>
</div>

</body>
</html>