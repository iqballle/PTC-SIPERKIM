@extends('layouts.dinas')

@section('title', 'Dashboard Dinas — SIPERKIM')

@section('content')

  {{-- TOPBAR --}}
  <div class="topbar">
    <div>
      <h1>Dashboard Dinas</h1>
      <p style="font-size:13px;color:#6b7280;margin-top:4px;">
        Ringkasan status permohonan dan data perumahan dalam sistem SIPERKIM.
      </p>
    </div>
  </div>

  {{-- KPI / STAT CARDS --}}
  <div class="kpis" style="margin-top:16px;">
    {{-- Permohonan Nota Dinas Pending --}}
    <div class="kpi">
      <div class="kpi-title">Permohonan Nota Dinas</div>
      <div class="kpi-sub">Menunggu verifikasi</div>
      <div class="kpi-num">{{ $notaPending ?? 0 }}</div>
    </div>

    {{-- Perumahan Pending --}}
    <div class="kpi">
      <div class="kpi-title">Data Perumahan</div>
      <div class="kpi-sub">Belum diverifikasi</div>
      <div class="kpi-num">{{ $perumahanPending ?? 0 }}</div>
    </div>

    {{-- Perumahan Disetujui --}}
    <div class="kpi">
      <div class="kpi-title">Perumahan Disetujui</div>
      <div class="kpi-sub">Total terdata</div>
      <div class="kpi-num">{{ $perumahanDisetujui ?? 0 }}</div>
    </div>
  </div>

  {{-- BARIS KEDUA KPI --}}
  <div class="kpis" style="margin-top:12px;">
    <div class="kpi">
      <div class="kpi-title">Nota Dinas Disetujui</div>
      <div class="kpi-sub">Sudah terbit rekomendasi</div>
      <div class="kpi-num">{{ $notaDisetujui ?? 0 }}</div>
    </div>

    <div class="kpi">
      <div class="kpi-title">Nota Dinas Perlu Revisi</div>
      <div class="kpi-sub">Menunggu perbaikan developer</div>
      <div class="kpi-num">{{ $notaRevisi ?? 0 }}</div>
    </div>

    <div class="kpi">
      <div class="kpi-title">Total Perumahan</div>
      <div class="kpi-sub">Teregistrasi di SIPERKIM</div>
      <div class="kpi-num">{{ $totalPerumahan ?? 0 }}</div>
    </div>
  </div>

  {{-- TABEL PERMOHONAN NOTA DINAS TERBARU --}}
  <section class="card" style="margin-top:20px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
      <h2>Permohonan Nota Dinas Terbaru</h2>
      <a href="{{ route('dinas.permohonan.nota.index') }}" class="btn-plain" style="font-size:12px;">
        Lihat semua →
      </a>
    </div>

    @if(($recentNota ?? collect())->isEmpty())
      <p style="font-size:13px;color:#6b7280;">Belum ada permohonan yang masuk.</p>
    @else
      <div class="table-wrap">
        <table class="table">
          <thead>
            <tr>
              <th>No</th>
              <th>Perumahan</th>
              <th>Pengembang</th>
              <th>Status</th>
              <th>Diajukan</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($recentNota as $i => $p)
              @php
                $status = $p->status;
                $badgeClass = 'badge-warn';
                $label = 'Pending';

                if ($status === 'disetujui') {
                  $badgeClass = 'badge-ok';
                  $label = 'Disetujui';
                } elseif ($status === 'revisi') {
                  $badgeClass = 'badge-bad';
                  $label = 'Perlu Revisi';
                } elseif ($status === 'ditolak') {
                  $badgeClass = 'badge-bad';
                  $label = 'Ditolak';
                }
              @endphp
              <tr>
                <td>{{ $i + 1 }}</td>
                <td>
                  {{ $p->nama_perumahan }}
                  @if($p->perumahan)
                    <br>
                    <span style="font-size:11px;color:#6b7280;">
                      {{ $p->perumahan->lokasi }}
                    </span>
                  @endif
                </td>
                <td>{{ $p->nama_pengembang }}</td>
                <td>
                  <span class="badge {{ $badgeClass }}">{{ $label }}</span>
                </td>
                <td>{{ $p->created_at?->format('d M Y H:i') }}</td>
                <td>
                  <a href="{{ route('dinas.permohonan.nota.show', $p->id) }}" class="btn-plain" >
                    Detail
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </section>

  {{-- TABEL PERUMAHAN SUDAH DISETUJUI TERBARU --}}
<section class="card" style="margin-top:20px;">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
    <h2>Perumahan Terverifikasi Terbaru</h2>
    <a href="{{ route('dinas.perumahan.verify.index') }}" class="btn-plain" style="font-size:12px;">
      Lihat semua →
    </a>
  </div>

  @if(($recentPerumahanApproved ?? collect())->isEmpty())
    <p style="font-size:13px;color:#6b7280;">Belum ada perumahan yang disetujui.</p>
  @else
    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Perumahan</th>
            <th>Developer</th>
            <th>Lokasi</th>
            <th>Status</th>
            <th>Disetujui</th>
            <th>Aksi</th>
          </tr>
        </thead>

        <tbody>
          @foreach($recentPerumahanApproved as $i => $r)
            @php
              // ambil status dari kolom status / status_verifikasi (karena kamu punya 2)
              $raw = strtolower((string)($r->status ?? $r->status_verifikasi ?? 'pending'));

              $badgeClass = 'badge-warn';
              $label = 'Pending';

              if (in_array($raw, ['disetujui','approved','approve','setuju'])) {
                $badgeClass = 'badge-ok';
                $label = 'Disetujui';
              } elseif (in_array($raw, ['ditolak','rejected','reject','tolak'])) {
                $badgeClass = 'badge-bad';
                $label = 'Ditolak';
              } elseif (in_array($raw, ['revisi','revision','needs_revision'])) {
                $badgeClass = 'badge-warn';
                $label = 'Perlu Revisi';
              }
            @endphp

            <tr>
              <td>{{ $i + 1 }}</td>
              <td style="font-weight:700;color:#0f172a;">
                {{ $r->nama }}
              </td>
              <td>
                {{ $r->developer?->name ?? $r->nama_developer ?? '-' }}
                <br>
                <span style="font-size:11px;color:#6b7280;">
                  {{ $r->developer?->email ?? '-' }}
                </span>
              </td>
              <td>{{ $r->lokasi ?? '-' }}</td>
              <td>
                <span class="badge {{ $badgeClass }}">{{ $label }}</span>
              </td>
              <td>{{ $r->approved_at?->format('d M Y H:i') ?? '-' }}</td>
              <td>
                <a href="{{ route('dinas.perumahan.verify.show', $r->id) }}" class="btn-plain">
                  Detail
                </a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif
</section>

@endsection