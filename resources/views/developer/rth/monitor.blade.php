{{-- resources/views/developer/rth/monitor.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Monitoring RTH — SIPERKIM</title>

  <link rel="stylesheet" href="{{ asset('css/dev-dashboard.css') }}">
  @vite('resources/js/dashboard.js')
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">

  <style>
    :root{
      --card:#ffffff;
      --border:#e5e7eb;
      --muted:#6b7280;
      --text:#111827;
      --bg:#f6f7fb;

      --ok-bg:#ecfdf3; --ok-bd:#bbf7d0; --ok-tx:#166534;
      --wrn-bg:#fef9c3; --wrn-bd:#fde68a; --wrn-tx:#854d0e;
      --err-bg:#fef2f2; --err-bd:#fecaca; --err-tx:#b91c1c;

      --brand:#5B7042;
      --shadow:0 10px 30px rgba(16,24,40,.06);
    }

    body{background:var(--bg);}
    .content{padding-bottom:32px;}

    .page-head{
      background: radial-gradient(1200px 400px at 10% 0%, rgba(138,166,106,.22), transparent 60%),
                  radial-gradient(900px 350px at 90% 10%, rgba(91,112,66,.18), transparent 55%),
                  linear-gradient(180deg,#ffffff 0%, #ffffff 55%, rgba(255,255,255,0) 100%);
      padding-bottom:10px;
    }

    .page-title{font-size:22px;font-weight:900;color:var(--text);letter-spacing:-.2px;}
    .page-sub{font-size:13px;color:var(--muted);margin-top:4px;max-width:820px;line-height:1.45;}

    .card{
      background:var(--card);
      border:1px solid var(--border);
      border-radius:16px;
      padding:14px 16px;
      box-shadow:var(--shadow);
    }

    .grid{display:grid;gap:14px;}
    .grid-2{grid-template-columns:repeat(2,minmax(0,1fr));}
    .grid-4{grid-template-columns:repeat(4,minmax(0,1fr));}
    @media(max-width:1100px){.grid-4{grid-template-columns:repeat(2,minmax(0,1fr));}}
    @media(max-width:640px){.grid-2,.grid-4{grid-template-columns:1fr;}}

    .chips{display:flex;gap:10px;flex-wrap:wrap;margin-top:10px;}
    .chip{
      display:inline-flex;align-items:center;gap:8px;
      background:#f3f4f6;border:1px solid var(--border);
      padding:7px 10px;border-radius:999px;font-size:12px;font-weight:900;color:#374151;
    }
    .chip b{color:#0f172a;}
    .dot{width:8px;height:8px;border-radius:999px;background:#9ca3af;}
    .dot.ok{background:#16a34a;}
    .dot.warn{background:#d97706;}
    .dot.err{background:#dc2626;}

    .statusbar{
      display:flex;align-items:center;justify-content:space-between;gap:12px;
      padding:12px 14px;border-radius:14px;margin-top:12px;
      border:1px solid var(--border); background:#fff;
      box-shadow:var(--shadow);
    }
    .status-left{display:flex;align-items:center;gap:10px;}
    .status-title{font-size:13px;font-weight:900;color:var(--text);}
    .status-msg{font-size:12px;color:var(--muted);margin-top:2px;}

    .btn{
      display:inline-flex;align-items:center;gap:8px;
      border:1px solid var(--border);background:#fff;color:var(--text);
      padding:10px 12px;border-radius:12px;font-weight:900;
      cursor:pointer;text-decoration:none;font-size:13px;
      transition:transform .06s ease, box-shadow .12s ease;
    }
    .btn:hover{box-shadow:0 10px 20px rgba(16,24,40,.08);}
    .btn:active{transform:translateY(1px);}
    .btn-primary{border-color:rgba(91,112,66,.25);background:rgba(91,112,66,.10);color:#1f2937;}

    .stat-label{font-size:12px;color:var(--muted);font-weight:900;}
    .stat-value{font-size:28px;font-weight:900;color:var(--text);margin-top:8px;letter-spacing:-.5px;}
    .stat-sub{font-size:12px;color:var(--muted);margin-top:4px;}

    .badge{
      display:inline-flex;align-items:center;gap:6px;
      padding:6px 10px;border-radius:999px;font-size:12px;font-weight:900;
      border:1px solid var(--border); background:#fff;color:#374151;
      white-space:nowrap;
    }
    .badge.ok{background:var(--ok-bg);border-color:var(--ok-bd);color:var(--ok-tx);}
    .badge.warn{background:var(--wrn-bg);border-color:var(--wrn-bd);color:var(--wrn-tx);}
    .badge.err{background:var(--err-bg);border-color:var(--err-bd);color:var(--err-tx);}

    .panel-title{font-size:13px;font-weight:900;color:var(--text);}
    .panel-sub{font-size:12px;color:var(--muted);margin-top:2px;line-height:1.45;}

    .kv{display:flex;align-items:center;justify-content:space-between;gap:10px;padding:10px 0;border-top:1px dashed #e5e7eb;}
    .kv:first-of-type{border-top:none;padding-top:0;}
    .kv-key{font-size:12px;color:var(--muted);font-weight:900;}
    .kv-val{font-size:12px;color:#111827;font-weight:900;}

    .alert{padding:12px 14px;border-radius:14px;margin-top:12px;font-size:13px;font-weight:800; border:1px solid;}
    .alert.ok{background:var(--ok-bg);border-color:var(--ok-bd);color:var(--ok-tx);}
    .alert.warn{background:var(--wrn-bg);border-color:var(--wrn-bd);color:var(--wrn-tx);}
    .alert.err{background:var(--err-bg);border-color:var(--err-bd);color:var(--err-tx);}

    .pump-row{display:flex;align-items:baseline;gap:10px;flex-wrap:wrap;}
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

  {{-- CONTENT --}}
  <main id="content" class="content">
    <button id="sidebar-toggle" class="sidebar-toggle" type="button" aria-label="Toggle Sidebar">☰</button>

    <div class="page-head">
      <div class="topbar">
        <div>
          <div class="page-title">Monitoring RTH</div>
          <div class="page-sub">
            Monitoring realtime (DHT22, Soil Moisture, Water Flow) + status penyiraman.
            
          </div>

          <div class="chips">
            <div class="chip">
              <span class="dot" id="dotConn"></span>
              <span>Koneksi</span>
              <b id="chipConn">-</b>
            </div>

            <div class="chip">
              Perumahan:
              <b id="chipPerumahan">
                {{ $perumahan->nama ?? '-' }}
              </b>
            </div>

            <div class="chip">
              Device ID:
              <b id="chipDeviceId">{{ $deviceId ?? '-' }}</b>
            </div>

            <div class="chip">
              DHT:
              <b id="chipDht">-</b>
            </div>

            <div class="chip">
              Updated:
              <b id="chipUpdated">-</b>
            </div>

            <div class="chip">
              Pompa:
              <b id="chipPump">-</b>
            </div>

            <div class="chip">
              Aliran:
              <b id="chipFlowState">-</b>
            </div>
          </div>

          <div class="statusbar">
            <div class="status-left">
              <span class="dot" id="dotState"></span>
              <div>
                <div class="status-title" id="connTitle">Menghubungkan…</div>
                <div class="status-msg" id="connMsg">Polling ke server untuk mengambil data terbaru.</div>
              </div>
            </div>
            <div style="display:flex;gap:10px;flex-wrap:wrap;">
              <a class="btn" href="{{ route('developer.rth.index') }}">Ganti Perumahan/Device</a>
              <button class="btn btn-primary" type="button" id="btnRefresh">Refresh</button>
            </div>
          </div>

          @if(session('status'))
            <div class="alert ok">{{ session('status') }}</div>
          @endif

          @if(!empty($error))
            <div class="alert err">{{ $error }}</div>
          @endif
        </div>
      </div>
    </div>

    {{-- RINGKAS STATUS --}}
    <div class="grid grid-2" style="margin-top:14px;">
      <section class="card">
        <div class="stat-label">Status Penyiraman</div>

        <div class="pump-row">
          <div class="stat-value" id="sprayText">-</div>
          <span id="sprayBadge" class="badge warn">-</span>
        </div>

        <div class="stat-sub">
          Pompa: <b id="pumpText">-</b> • Flow: <b id="flowText">-</b> L/min • Terakhir update: <b id="lastUpdate">-</b>
        </div>

        <div id="sprayHint" class="alert warn" style="display:none;margin-top:12px;">
          -
        </div>
      </section>

      <section class="card">
        <div class="panel-title">Health Check</div>
        <div class="panel-sub">Cek cepat apakah sensor & data utama sedang aktif.</div>

        <div style="margin-top:10px;">
          <div class="kv">
            <div class="kv-key">DHT22</div>
            <div class="kv-val"><span id="hcDht" class="badge warn">-</span></div>
          </div>
          <div class="kv">
            <div class="kv-key">Soil Sensor</div>
            <div class="kv-val"><span id="hcSoil" class="badge warn">-</span></div>
          </div>
          <div class="kv">
            <div class="kv-key">Flow Sensor</div>
            <div class="kv-val"><span id="hcFlow" class="badge warn">-</span></div>
          </div>
        </div>
      </section>
    </div>

    {{-- METRICS --}}
    <section class="grid grid-4" style="margin-top:14px;">
      <div class="card">
        <div class="stat-label">Suhu (°C)</div>
        <div class="stat-value" id="temp">-</div>
        <div class="stat-sub">Sumber: DHT22</div>
      </div>

      <div class="card">
        <div class="stat-label">Kelembapan (%)</div>
        <div class="stat-value" id="hum">-</div>
        <div class="stat-sub">Sumber: DHT22</div>
      </div>

      <div class="card">
        <div class="stat-label">Soil Moisture (%)</div>
        <div class="stat-value" id="soilPct">-</div>
        <div class="stat-sub">Raw: <b id="soilRaw">-</b></div>
      </div>

      <div class="card">
        <div class="stat-label">Flow Rate (L/min)</div>
        <div class="stat-value" id="flowRate">-</div>
        <div class="stat-sub">Total: <b id="totalMl">-</b> mL</div>
      </div>
    </section>

    {{-- DETAIL --}}
    <section class="card" style="margin-top:14px;">
      <div class="panel-title">Detail Data</div>
      <div class="panel-sub">Untuk memastikan field yang terbaca sesuai struktur RTDB kamu.</div>

      <div style="margin-top:10px;">
        <div class="kv"><div class="kv-key">soilRaw</div><div class="kv-val" id="kvSoilRaw">-</div></div>
        <div class="kv"><div class="kv-key">soilPct</div><div class="kv-val" id="kvSoilPct">-</div></div>
        <div class="kv"><div class="kv-key">pump</div><div class="kv-val" id="kvPump">-</div></div>
        <div class="kv"><div class="kv-key">flowRate</div><div class="kv-val" id="kvFlowRate">-</div></div>
        <div class="kv"><div class="kv-key">totalMl</div><div class="kv-val" id="kvTotalMl">-</div></div>
        <div class="kv"><div class="kv-key">temp</div><div class="kv-val" id="kvTemp">-</div></div>
        <div class="kv"><div class="kv-key">hum</div><div class="kv-val" id="kvHum">-</div></div>
        <div class="kv"><div class="kv-key">dhtOK</div><div class="kv-val" id="kvDhtOk">-</div></div>
        <div class="kv"><div class="kv-key">updatedAtMs</div><div class="kv-val" id="kvUpdated">-</div></div>
      </div>
    </section>

  </main>
</div>

<script>
  const el = (id) => document.getElementById(id);

  const dotConn = el("dotConn");
  const chipConn = el("chipConn");
  const dotState = el("dotState");
  const connTitle = el("connTitle");
  const connMsg = el("connMsg");

  // tuning threshold (boleh kamu ubah)
  const FLOW_ON_THRESHOLD = 0.02; // L/min. Di bawah ini dianggap tidak mengalir.

  const setState = (type, title, msg) => {
    dotConn.className = "dot " + type;
    dotState.className = "dot " + type;

    chipConn.textContent = type.toUpperCase();
    connTitle.textContent = title;
    connMsg.textContent = msg;
  };

  const formatTime = (ms) => {
    if (ms === null || ms === undefined || ms === "") return "-";
    const d = new Date(Number(ms));
    return isNaN(d.getTime()) ? "-" : d.toLocaleString("id-ID");
  };

  const setBadge = (node, type, text) => {
    node.className = "badge " + type;
    node.textContent = text;
  };

  function renderData(v) {
    if (!v) return;

    // sensor values
    el("temp").textContent     = (v.temp ?? "-");
    el("hum").textContent      = (v.hum ?? "-");
    el("soilPct").textContent  = (v.soilPct ?? "-");
    el("soilRaw").textContent  = (v.soilRaw ?? "-");
    el("flowRate").textContent = (v.flowRate ?? "-");
    el("totalMl").textContent  = (v.totalMl ?? "-");

    // chips
    const dhtOk = String(v.dhtOK ?? 0) === "1";
    el("chipDht").textContent = dhtOk ? "OK" : "ERROR";
    el("chipUpdated").textContent = formatTime(v.updatedAtMs);

    // pump basic
    const pumpOn = String(v.pump ?? 0) === "1" || v.pump === true;
    el("chipPump").textContent = pumpOn ? "ON" : "OFF";
    el("pumpText").textContent = pumpOn ? "ON" : "OFF";

    // flow based "spraying"
    const flow = Number(v.flowRate ?? 0);
    const flowValid = !isNaN(flow);
    const flowOn = flowValid && flow > FLOW_ON_THRESHOLD;

    el("flowText").textContent = flowValid ? flow.toFixed(3) : "-";

    // status: menyiram = flowOn (utama), tapi tetap tampilkan kondisi pump
    const sprayBadge = el("sprayBadge");
    const sprayText = el("sprayText");
    const chipFlowState = el("chipFlowState");

    const hint = el("sprayHint");
    hint.style.display = "none";
    hint.textContent = "-";
    hint.className = "alert warn";

    if (flowOn) {
      sprayText.textContent = "MENYIRAM";
      setBadge(sprayBadge, "ok", "Aliran terdeteksi");
      chipFlowState.textContent = "ADA";
    } else {
      sprayText.textContent = "TIDAK MENYIRAM";
      setBadge(sprayBadge, "err", "Tidak ada aliran");
      chipFlowState.textContent = "TIDAK ADA";

      // Kasus penting: pump ON tapi flow 0 → problem lapangan
      if (pumpOn) {
        hint.style.display = "block";
        hint.className = "alert warn";
        hint.textContent = "Pompa terdeteksi ON, tapi Flow Rate = 0. Kemungkinan: pompa tidak benar-benar aktif (relay/wiring), selang tersumbat, tidak ada air, atau flow sensor belum terbaca.";
      }
    }

    el("lastUpdate").textContent = formatTime(v.updatedAtMs);

    // health check
    setBadge(el("hcDht"), dhtOk ? "ok" : "err", dhtOk ? "Aktif" : "Error");

    const soilRaw = Number(v.soilRaw ?? -1);
    const soilOk = !isNaN(soilRaw) && soilRaw > 10 && soilRaw < 4090;
    setBadge(el("hcSoil"), soilOk ? "ok" : "warn", soilOk ? "Terbaca" : "Tidak Valid");

    // flow sensor health: valid value
    setBadge(el("hcFlow"), flowValid ? "ok" : "warn", flowValid ? "Terbaca" : "Tidak Valid");

    // detail kv
    el("kvSoilRaw").textContent = (v.soilRaw ?? "-");
    el("kvSoilPct").textContent = (v.soilPct ?? "-");
    el("kvPump").textContent    = (v.pump ?? "-");
    el("kvFlowRate").textContent= (v.flowRate ?? "-");
    el("kvTotalMl").textContent = (v.totalMl ?? "-");
    el("kvTemp").textContent    = (v.temp ?? "-");
    el("kvHum").textContent     = (v.hum ?? "-");
    el("kvDhtOk").textContent   = (v.dhtOK ?? "-");
    el("kvUpdated").textContent = (v.updatedAtMs ?? "-");
  }

  async function fetchData() {
    try {
      const res = await fetch("{{ route('developer.rth.fetch') }}", {
        headers: { "Accept": "application/json" }
      });

      const json = await res.json();

      if (!res.ok || !json.ok) {
        setState("err", "Gagal mengambil data", json.message || "Periksa route/controller & koneksi server.");
        return;
      }

      if (!json.data) {
        setState("warn", "Data belum ada", "ESP32 belum mengirim data ke device ini.");
        return;
      }

      setState("ok", "Terhubung", "Data realtime aktif (polling).");
      renderData(json.data);

    } catch (e) {
      console.error(e);
      setState("err", "Tidak bisa konek server", "Cek jaringan / route / controller.");
    }
  }

  el("btnRefresh").addEventListener("click", fetchData);

  fetchData();
  setInterval(fetchData, 1500);
</script>

</body>
</html>