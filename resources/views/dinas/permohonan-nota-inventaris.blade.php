@extends('layouts.dinas')

@section('title', 'Inventaris Nota Dinas — Dinas | SIPERKIM')

@push('styles')
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

    /* efek baris klikable */
    .row-clickable {
      cursor: pointer;
    }
    .row-clickable:hover {
      background-color: #f9fafb;
    }
  </style>
@endpush

@section('content')

  <div class="topbar">
    <div>
      <h1>Inventaris Nota Dinas</h1>
      <p style="font-size:12px;color:#6b7280;margin-top:4px;">
        Rekap permohonan Nota Dinas per perumahan untuk keperluan inventaris dan monitoring.
      </p>
    </div>
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

  {{-- FILTER PENCARIAN --}}
  <section class="card" style="margin-top:12px;padding:12px 16px;">
    <form method="GET" action="{{ route('dinas.permohonan.nota.inventaris') }}"
          style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
      <div>
        <label style="font-size:12px;color:#6b7280;">Cari Perumahan / Pengembang</label>
        <input type="text" name="q" class="input" placeholder="Nama perumahan atau pengembang"
               value="{{ request('q') }}" style="min-width:220px;">
      </div>
      <div>
        <label style="font-size:12px;color:#6b7280;">Tahun Pengajuan</label>
        <input type="number" name="tahun" class="input" placeholder="2025"
               value="{{ request('tahun') }}" style="width:120px;">
      </div>
      <button class="btn-plain" type="submit">Terapkan</button>
    </form>
  </section>

  {{-- TABEL INVENTARIS --}}
  <section class="card" style="margin-top:12px;">
    <h2>Rekap Per Perumahan</h2>

    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th>No</th>
            <th>Perumahan</th>
            <th>Pengembang</th>
            <th>Total Permohonan</th>
            <th>Pending</th>
            <th>Disetujui</th>
            <th>Perlu Revisi</th>
            <th>Ditolak</th>
            <th>Terakhir Diajukan</th>
          </tr>
        </thead>
        <tbody>
          @forelse($items as $index => $row)
            @php
              // klik baris → kembali ke daftar permohonan, terfilter per perumahan
              $urlDetail = route('dinas.permohonan.nota.index', [
                'perumahan_id' => $row->perumahan_id
              ]);
            @endphp
            <tr class="row-clickable" onclick="window.location='{{ $urlDetail }}'">
              <td>{{ $index + 1 }}</td>
              <td>
                <strong>{{ $row->nama_perumahan }}</strong>
              </td>
              <td>{{ $row->nama_pengembang }}</td>
              <td>{{ $row->total_permohonan }}</td>
              <td>{{ $row->jml_pending }}</td>
              <td>{{ $row->jml_disetujui }}</td>
              <td>{{ $row->jml_revisi }}</td>
              <td>{{ $row->jml_ditolak }}</td>
              <td>
                @if($row->terakhir_diajukan)
                  {{ \Carbon\Carbon::parse($row->terakhir_diajukan)->format('d M Y H:i') }}
                @else
                  -
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" style="text-align:center;font-size:13px;color:#6b7280;">
                Belum ada data inventaris Nota Dinas.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </section>

@endsection