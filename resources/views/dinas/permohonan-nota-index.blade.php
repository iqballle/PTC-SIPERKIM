@extends('layouts.dinas')

@section('title', 'Permohonan Nota Dinas — Dinas | SIPERKIM')

@push('styles')
  {{-- Sub-tab styling untuk Permohonan / Inventaris --}}
  <style>
    .nota-subtabs {
      display: flex;
      gap: 8px;
      align-items: center;
      border-bottom: 1px solid #e5e7eb;
    }
    .nota-subtab {
      display: inline-flex;
      align-items: center;
      padding: 6px 14px;
      border-radius: 999px;
      font-size: 13px;
      font-weight: 600;
      color: #4b5563;
      text-decoration: none;
      background: #f9fafb;
      transition: background-color 0.15s ease, color 0.15s ease;
    }
    .nota-subtab:hover {
      background: #e5e7eb;
    }
    .nota-subtab.active {
      background: #111827;
      color: #ffffff;
    }

    .btn-link{
      display:inline-flex;
      align-items:center;
      justify-content:center;
      padding:5px 10px;
      border-radius:999px;
      font-size:11px;
      font-weight:600;
      text-decoration:none;
      border:1px solid #d1d5db;
      background:#ffffff;
      color:#1d4ed8;
      transition:background .15s, color .15s, border-color .15s;
    }
  </style>
@endpush

@section('content')

  <div class="topbar">
    <h1>Permohonan Nota Dinas Pembangunan Perumahan</h1>
    <div class="topbar-right"></div>
  </div>

  {{-- SUB-TAB: Permohonan Masuk / Inventaris --}}
  <div class="card" style="margin-top:12px;padding:8px 14px;">
    <div class="nota-subtabs">
      <a href="{{ route('dinas.permohonan.nota.index') }}"
         class="nota-subtab {{ request()->routeIs('dinas.permohonan.nota.index') ? 'active' : '' }}">
        Permohonan Masuk
      </a>

      <a href="{{ route('dinas.permohonan.nota.inventaris') }}"
         class="nota-subtab {{ request()->routeIs('dinas.permohonan.nota.inventaris') ? 'active' : '' }}">
        Inventaris Nota Dinas
      </a>
    </div>
  </div>

  @if(session('status'))
    <div class="card" style="padding:10px 14px;margin-top:12px;background:#ecfdf3;border-left:4px solid #16a34a;font-size:13px;">
      {{ session('status') }}
    </div>
  @endif

  {{-- FILTER STATUS (hanya untuk tab Permohonan Masuk) --}}
  <section class="card" style="margin-top:12px;padding:12px 16px;">
    <form method="GET" action="{{ route('dinas.permohonan.nota.index') }}"
          style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
      <div>
        <label style="font-size:12px;color:#6b7280;">Filter status:</label>
        <select name="status" class="input" style="min-width:160px;">
          <option value="">Semua</option>
          <option value="pending"   {{ $status=='pending'   ? 'selected' : '' }}>Pending</option>
          <option value="disetujui" {{ $status=='disetujui' ? 'selected' : '' }}>Disetujui</option>
          <option value="revisi"    {{ $status=='revisi'    ? 'selected' : '' }}>Perlu Revisi</option>
          <option value="ditolak"   {{ $status=='ditolak'   ? 'selected' : '' }}>Ditolak</option>
        </select>
      </div>
      <button class="btn-plain" type="submit">Terapkan</button>
    </form>
  </section>

  {{-- TABEL PERMOHONAN --}}
  <section class="card" style="margin-top:12px;">
    <h2>Daftar Permohonan</h2>
    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th>No</th>
            <th>Perumahan</th>
            <th>Pengembang</th>
            <th>Status</th>
            <th>Tgl Pengajuan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($permohonans as $i => $p)
            @php
              $st = $p->status ?? 'pending';
              $badgeClass = 'badge-warn';
              $label = ucfirst($st);
              if ($st === 'disetujui') {
                  $badgeClass = 'badge-ok';  $label = 'Disetujui';
              } elseif ($st === 'revisi') {
                  $badgeClass = 'badge-bad'; $label = 'Perlu Revisi';
              } elseif ($st === 'ditolak') {
                  $badgeClass = 'badge-bad'; $label = 'Ditolak';
              } elseif ($st === 'pending') {
                  $badgeClass = 'badge-warn'; $label = 'Pending';
              }
            @endphp
            <tr>
              <td>{{ $permohonans->firstItem() + $i }}</td>
              <td>{{ $p->nama_perumahan }}</td>
              <td>{{ $p->nama_pengembang }}</td>
              <td><span class="badge {{ $badgeClass }}">{{ $label }}</span></td>
              <td>{{ $p->created_at?->format('d M Y H:i') }}</td>
              <td>
                <a href="{{ route('dinas.permohonan.nota.show', $p->id) }}" class="btn-link">
                  Detail
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" style="text-align:center;font-size:13px;color:#6b7280;">
                Belum ada permohonan Nota Dinas.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- PAGINATION --}}
    <div style="margin-top:10px;">
      {{ $permohonans->links() }}
    </div>
  </section>

@endsection