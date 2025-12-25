{{-- resources/views/dinas/rth/index.blade.php --}}
@extends('layouts.dinas')

@section('title', 'RTH — Penyiraman Otomatis (Dinas) — SIPERKIM')

@section('content')

  {{-- TOPBAR --}}
  <div class="topbar">
    <div>
      <h1>RTH — Penyiraman Otomatis</h1>
      <p style="font-size:13px;color:#6b7280;margin-top:4px;max-width:820px;line-height:1.5;">
        Daftar perumahan yang <b>sudah terpasang alat</b>. 
        
      </p>
    </div>
  </div>

  {{-- CARD LIST --}}
  <section class="card" style="margin-top:16px;">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:8px;">
      <h2 style="margin:0;">List Perumahan Terpasang Alat</h2>
      <div style="font-size:12px;color:#6b7280;">
        Total: <b>{{ ($developers ?? collect())->count() }}</b>
      </div>
    </div>

    @if(($developers ?? collect())->isEmpty())
      <p style="font-size:13px;color:#6b7280;margin:0;">
        Belum ada perumahan yang terhubung alat RTH.
      </p>
    @else

      <style>
        .rth-badge{
          display:inline-flex;align-items:center;gap:8px;
          padding:6px 10px;border-radius:999px;
          font-size:12px;font-weight:800;border:1px solid #e5e7eb;
          background:#f9fafb;color:#334155;
          white-space:nowrap;
        }
        .rth-dot{width:8px;height:8px;border-radius:999px;background:#9ca3af;}
        .rth-ok{background:#ecfdf3;border-color:#bbf7d0;color:#166534;}
        .rth-warn{background:#fef9c3;border-color:#fde68a;color:#854d0e;}
        .rth-err{background:#fef2f2;border-color:#fecaca;color:#b91c1c;}
        .mono{font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;}
        .rth-mini{font-size:11px;color:#6b7280;margin-top:4px;line-height:1.4;}
      </style>

      <div class="table-wrap">
        <table class="table">
          <thead>
            <tr>
              <th>No</th>
              <th>Perumahan</th>
              <th>Developer</th>
              <th>Device</th>
              <th>Status Pompa</th>
            </tr>
          </thead>

          <tbody>
            @foreach($developers as $i => $dev)
              @php
                $p = $dev->perumahanRth;        // relasi dari User model
                $deviceId = $dev->rth_device_id;

                // ✅ status perumahan (prioritas: status; fallback: status_verifikasi)
                $rawStatus = $p->status ?? null;
                if (!$rawStatus) $rawStatus = $p->status_verifikasi ?? null;

                $rawStatus = strtolower((string) $rawStatus);

                $statusLabel = 'Pending';
                $statusClass = 'rth-warn';

                // mapping fleksibel
                if (in_array($rawStatus, ['disetujui', 'approved', 'approve', 'setuju'])) {
                  $statusLabel = 'Disetujui';
                  $statusClass = 'rth-ok';
                } elseif (in_array($rawStatus, ['ditolak', 'rejected', 'reject', 'tolak'])) {
                  $statusLabel = 'Ditolak';
                  $statusClass = 'rth-err';
                } elseif (in_array($rawStatus, ['revisi', 'revision', 'needs_revision'])) {
                  $statusLabel = 'Perlu Revisi';
                  $statusClass = 'rth-warn';
                }
              @endphp

              <tr>
                <td>{{ $i + 1 }}</td>

                <td>
                  <div style="font-weight:800;color:#0f172a;">
                    {{ $p->nama ?? '-' }}
                  </div>
                  <div class="rth-mini">
                    {{ $p->lokasi ?? '-' }}
                  </div>

                  {{-- ✅ tampilkan status perumahan versi dinas --}}
                  <div style="margin-top:6px;">
                    <span class="rth-badge {{ $statusClass }}">
                      <span class="rth-dot"></span>
                      <span>Status: {{ $statusLabel }}</span>
                    </span>
                  </div>
                </td>

                <td>
                  <div style="font-weight:800;color:#0f172a;">
                    {{ $dev->name ?? '-' }}
                  </div>
                  <div class="rth-mini">
                    {{ $dev->email ?? '-' }}
                  </div>
                </td>

                <td>
                  <span class="rth-badge mono">
                    <span class="rth-dot" style="background:#64748b;"></span>
                    <span>{{ $deviceId ?? '-' }}</span>
                  </span>
                  <div class="rth-mini">Device terhubung via akun developer</div>
                </td>

                <td>
                  {{-- ✅ hanya status pompa --}}
                  <div class="rth-badge rth-warn js-pump" data-device="{{ $deviceId }}">
                    <span class="rth-dot"></span>
                    <span>Pompa: Checking…</span>
                  </div>

                  <div class="rth-mini js-updated" data-device="{{ $deviceId }}" style="margin-top:6px;">
                    Updated: -
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </section>

  <script>
    const routeStatus = "{{ route('dinas.rth.status') }}";

    const fmtTime = (ms) => {
      if (ms === null || ms === undefined || ms === "") return "-";
      const d = new Date(Number(ms));
      if (isNaN(d.getTime())) return "-";
      return d.toLocaleString("id-ID");
    };

    function setBadge(el, type, text) {
      if (!el) return;
      el.className = "rth-badge " + type;
      el.innerHTML = `<span class="rth-dot"></span><span>${text}</span>`;
    }

    async function refreshAll() {
      const devices = [...new Set(
        Array.from(document.querySelectorAll(".js-pump"))
          .map(x => x.getAttribute("data-device"))
          .filter(Boolean)
      )];

      if (devices.length === 0) return;

      const url = routeStatus + "?ids=" + encodeURIComponent(devices.join(","));

      try {
        const res = await fetch(url, { headers: { "Accept": "application/json" }});
        const json = await res.json();

        if (!res.ok || !json.ok) {
          devices.forEach(id => {
            setBadge(document.querySelector(`.js-pump[data-device="${id}"]`), "rth-warn", "Pompa: -");
            const up = document.querySelector(`.js-updated[data-device="${id}"]`);
            if (up) up.textContent = "Updated: -";
          });
          return;
        }

        const data = json.data || {};

        devices.forEach(id => {
          const d = data[id];

          const pumpEl    = document.querySelector(`.js-pump[data-device="${id}"]`);
          const updatedEl = document.querySelector(`.js-updated[data-device="${id}"]`);

          if (!d || d.ok === false) {
            setBadge(pumpEl, "rth-warn", "Pompa: -");
            if (updatedEl) updatedEl.textContent = "Updated: -";
            return;
          }

          const pumpOn = String(d.pump) === "1" || d.pump === 1 || d.pump === true;

          setBadge(pumpEl, pumpOn ? "rth-ok" : "rth-err", pumpOn ? "Pompa: ON" : "Pompa: OFF");
          if (updatedEl) updatedEl.textContent = "Updated: " + fmtTime(d.updatedAtMs);
        });

      } catch (e) {
        console.error(e);
      }
    }

    refreshAll();
    setInterval(refreshAll, 5000);
  </script>

@endsection