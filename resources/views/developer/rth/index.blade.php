{{-- resources/views/developer/rth/index.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>RTH - Penyiraman Otomatis (Developer) — SIPERKIM</title>

  <link rel="stylesheet" href="{{ asset('css/dev-dashboard.css') }}">
  @vite('resources/js/dashboard.js')

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

  <style>
    .rth-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px;margin-top:16px;}
    @media(max-width:980px){.rth-grid{grid-template-columns:repeat(2,minmax(0,1fr));}}
    @media(max-width:560px){.rth-grid{grid-template-columns:1fr;}}
    .rth-card{background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:14px 16px;}
    .rth-label{font-size:12px;color:#6b7280;font-weight:600;}
    .rth-value{font-size:26px;font-weight:800;margin-top:6px;}
    .rth-sub{font-size:12px;color:#6b7280;margin-top:2px;}
    .pump-box{display:flex;align-items:center;justify-content:space-between;gap:12px;}
    .pill{display:inline-flex;align-items:center;gap:6px;padding:5px 10px;border-radius:999px;font-size:12px;font-weight:700;}
    .pill-on{background:#ecfdf3;color:#15803d;}
    .pill-off{background:#fef2f2;color:#b91c1c;}
    .btn-row{display:flex;gap:10px;justify-content:flex-end;margin-top:10px;flex-wrap:wrap;}
    .btn-on{border:0;background:#16a34a;color:#fff;padding:10px 14px;border-radius:999px;font-weight:800;cursor:pointer;}
    .btn-off{border:0;background:#dc2626;color:#fff;padding:10px 14px;border-radius:999px;font-weight:800;cursor:pointer;}
    .btn-on:disabled,.btn-off:disabled{opacity:.6;cursor:not-allowed;}
    .small{font-size:12px;color:#6b7280;}
    .warn{background:#fef9c3;border:1px solid #fde68a;color:#854d0e;padding:10px 12px;border-radius:12px;margin-top:12px;font-size:13px;}
    .ok{background:#ecfdf3;border:1px solid #bbf7d0;color:#166534;padding:10px 12px;border-radius:12px;margin-top:12px;font-size:13px;}
  </style>
</head>

<body class="font-[Inter] antialiased text-[#2f2f2f]">
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
        <a href="{{ route('developer.notifikasi.index') }}">
          Notifikasi & Revisi
  
          {{-- 🔴 TITIK MERAH / BADGE --}}
          @if(!empty($devRevisiCount) && $devRevisiCount > 0)
            <span class="notif-dot">{{ $devRevisiCount }}</span>
          @endif
        </a>
      </li>

      {{-- ✅ RTH --}}
      <li class="{{ request()->routeIs('developer.rth.*') ? 'active' : '' }}">
        <a href="{{ route('developer.rth.index') }}">RTH - Penyiraman Otomatis</a>
      </li>

      <li class="{{ request()->routeIs('developer.settings.*') ? 'active' : '' }}">
        <a href="{{ route('developer.settings.index') }}">Pengaturan</a>
      </li>
    </ul>
  </aside>

  {{-- CONTENT --}}
  <main id="content" class="content">
    <button id="sidebar-toggle" class="sidebar-toggle" type="button" aria-label="Toggle Sidebar">☰</button>

    <div class="topbar">
      <div>
        <h1>RTH - Penyiraman Otomatis</h1>
        <p class="small" style="margin-top:4px;">
          Monitoring realtime DHT22, Soil Moisture, dan Water Flow + kontrol pompa.
        </p>
      </div>
    </div>

    {{-- INFO CONNECT --}}
    <div id="connState" class="warn">
      Menghubungkan ke Firebase Realtime Database...
    </div>

    {{-- CONTROL PUMP --}}
    <section class="rth-card" style="margin-top:14px;">
      <div class="pump-box">
        <div>
          <div class="rth-label">Status Pompa</div>
          <div style="margin-top:8px;">
            <span id="pumpPill" class="pill pill-off">● OFF</span>
          </div>
          <div class="rth-sub" style="margin-top:8px;">
            Terakhir update: <span id="lastUpdate">-</span>
          </div>
        </div>

        <div class="btn-row">
          <button id="btnPumpOn" class="btn-on" type="button">ON</button>
          <button id="btnPumpOff" class="btn-off" type="button">OFF</button>
        </div>
      </div>
    </section>

    {{-- METRICS --}}
    <section class="rth-grid">
      <div class="rth-card">
        <div class="rth-label">Suhu (°C)</div>
        <div class="rth-value" id="temp">-</div>
        <div class="rth-sub">DHT22</div>
      </div>

      <div class="rth-card">
        <div class="rth-label">Kelembapan (%)</div>
        <div class="rth-value" id="hum">-</div>
        <div class="rth-sub">DHT22</div>
      </div>

      <div class="rth-card">
        <div class="rth-label">Soil Moisture (%)</div>
        <div class="rth-value" id="soilPct">-</div>
        <div class="rth-sub">Raw: <span id="soilRaw">-</span></div>
      </div>

      <div class="rth-card">
        <div class="rth-label">Flow Rate (L/min)</div>
        <div class="rth-value" id="flowRate">-</div>
        <div class="rth-sub">Total: <span id="totalML">-</span> mL</div>
      </div>
    </section>

  </main>
</div>

{{-- =========================================================
  Firebase Web SDK (Realtime Database)
  ========================================================= --}}
<script type="module">
  // 🔴 WAJIB: isi config dari Firebase Console > Project settings > Web app
  // (bukan service account)
  const firebaseConfig = {
    apiKey: "ISI_API_KEY",
    authDomain: "ptc-siperkim.firebaseapp.com",
    databaseURL: "https://ptc-siperkim-default-rtdb.asia-southeast1.firebasedatabase.app",
    projectId: "ptc-siperkim",
    storageBucket: "ptc-siperkim.appspot.com",
    messagingSenderId: "ISI_SENDER_ID",
    appId: "ISI_APP_ID"
  };

  import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.5/firebase-app.js";
  import { getDatabase, ref, onValue, update } from "https://www.gstatic.com/firebasejs/10.12.5/firebase-database.js";

  const app = initializeApp(firebaseConfig);
  const db  = getDatabase(app);

  // Path sesuai struktur kamu:
  // realtime/garden_center/...
  const rootPath = "realtime/garden_center";
  const dataRef  = ref(db, rootPath);

  // Elements
  const el = (id) => document.getElementById(id);
  const connState = el("connState");

  const setConn = (ok, msg) => {
    connState.className = ok ? "ok" : "warn";
    connState.textContent = msg;
  };

  const setPumpUI = (pumpVal) => {
    const pill = el("pumpPill");
    const isOn = String(pumpVal) === "1" || pumpVal === true;

    pill.className = "pill " + (isOn ? "pill-on" : "pill-off");
    pill.textContent = isOn ? "● ON" : "● OFF";

    el("btnPumpOn").disabled  = isOn;     // ON disable kalau sudah ON
    el("btnPumpOff").disabled = !isOn;    // OFF disable kalau sudah OFF
  };

  const formatWita = (msOrNumber) => {
    if (!msOrNumber) return "-";
    const d = new Date(Number(msOrNumber));
    // tampilkan waktu lokal browser kamu (kalau mau WITA, PC kamu set WITA)
    return d.toLocaleString("id-ID");
  };

  // Listen realtime
  onValue(dataRef, (snap) => {
    const v = snap.val();
    if (!v) {
      setConn(false, "Data belum ada pada path: " + rootPath);
      return;
    }

    setConn(true, "Terhubung. Data realtime aktif.");

    el("temp").textContent     = v.temp ?? "-";
    el("hum").textContent      = v.hum ?? "-";
    el("soilPct").textContent  = v.soilPct ?? "-";
    el("soilRaw").textContent  = v.soilRaw ?? "-";
    el("flowRate").textContent = v.flowRate ?? "-";
    el("totalML").textContent  = v.totalML ?? "-";

    setPumpUI(v.pump ?? 0);
    el("lastUpdate").textContent = formatWita(v.updatedAtMs);
  }, (err) => {
    console.error(err);
    setConn(false, "Gagal konek Firebase. Cek rules & config.");
  });

  // Control pump
  async function setPump(val) {
    try {
      // update hanya field pump (dan optional timestamp)
      await update(ref(db, rootPath), {
        pump: val,
        // optional: biar tahu command dari web
        cmdAtMs: Date.now()
      });
    } catch (e) {
      console.error(e);
      alert("Gagal kirim perintah ke Firebase. Cek rules Realtime DB.");
    }
  }

  el("btnPumpOn").addEventListener("click", () => setPump(1));
  el("btnPumpOff").addEventListener("click", () => setPump(0));
</script>

</body>
</html>