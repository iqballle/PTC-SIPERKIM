@extends('layouts.dinas')

@section('title', 'Verifikasi Data Perumahan — Dinas | SIPERKIM')

@section('content')
  @php
    // status default
    $status = $status ?? request('status', 'pending');

    // untuk dropdown tahun
    $yearSelected = request('year');
    $yearNow = now()->year;

    // range tahun (misal 5 tahun terakhir)
    $years = range($yearNow, max($yearNow - 5, 2000));
  @endphp

  <div class="topbar">
    <div>
      <h1 class="page-title">
        @if($status === 'inventaris')
          Inventaris Perumahan Disetujui
        @else
          Verifikasi Data Perumahan
        @endif
      </h1>

      <p style="font-size:13px;color:#6b7280;margin-top:4px;">
        @if($status === 'inventaris')
          Daftar perumahan yang sudah disetujui (arsip/inventaris).
        @else
          Kelola status verifikasi data perumahan yang diajukan oleh developer.
        @endif
      </p>
    </div>
  </div>

  @if (session('status'))
    <div class="card" style="border-color:#bbf7d0;background:#ecfdf5;margin-bottom:12px;font-size:13px;">
      {{ session('status') }}
    </div>
  @endif

  {{-- Tabs status --}}
  <div class="card" style="margin-bottom:16px;">
    <div class="tabs">
      <a class="tab {{ $status == 'pending' ? 'active' : '' }}"
         href="{{ route('dinas.perumahan.verify.index', ['status' => 'pending']) }}">
        Pending ({{ $counts['pending'] ?? 0 }})
      </a>

      <a class="tab {{ $status == 'disetujui' ? 'active' : '' }}"
         href="{{ route('dinas.perumahan.verify.index', ['status' => 'disetujui']) }}">
        Disetujui ({{ $counts['disetujui'] ?? 0 }})
      </a>

      <a class="tab {{ $status == 'revisi' ? 'active' : '' }}"
         href="{{ route('dinas.perumahan.verify.index', ['status' => 'revisi']) }}">
        Perlu Revisi ({{ $counts['revisi'] ?? 0 }})
      </a>

      {{-- ✅ TAB INVENTARIS --}}
      <a class="tab {{ $status == 'inventaris' ? 'active' : '' }}"
         href="{{ route('dinas.perumahan.verify.index', ['status' => 'inventaris']) }}">
        Inventaris ({{ $counts['inventaris'] ?? ($counts['disetujui'] ?? 0) }})
      </a>
    </div>
  </div>

  {{-- ✅ Filter khusus inventaris (tahun + pencarian) --}}
  @if($status === 'inventaris')
    <div class="card" style="margin-bottom:12px;">
      <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
        <input type="hidden" name="status" value="inventaris">

        <input
          class="input"
          type="text"
          name="q"
          value="{{ request('q') }}"
          placeholder="Cari nama/lokasi/perusahaan/developer"
          style="max-width:320px;"
        >

        {{-- ✅ Filter tahun saja --}}
        <select class="input" name="year" style="max-width:180px;">
          <option value="">Semua Tahun</option>
          @foreach($years as $y)
            <option value="{{ $y }}" {{ (string)$yearSelected === (string)$y ? 'selected' : '' }}>
              Tahun {{ $y }}
            </option>
          @endforeach
        </select>

        <button class="btn-plain" type="submit" style="font-size:12px;">Filter</button>

        <a class="btn-plain"
           href="{{ route('dinas.perumahan.verify.index', ['status' => 'inventaris']) }}"
           style="font-size:12px;border-color:#e5e7eb;color:#374151;background:#fff;">
          Reset
        </a>
      </form>

      
    </div>
  @endif

  {{-- TABEL PERUMAHAN --}}
  <div class="card">
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th style="width:72px">Cover</th>
            <th>Perumahan</th>

            {{-- ✅ kolom tambahan untuk inventaris --}}
            @if($status === 'inventaris')
              <th>Perusahaan</th>
            @endif

            <th>Developer</th>
            <th>Lokasi</th>

            @if($status !== 'inventaris')
              <th>Status</th>
            @else
              <th>Disetujui</th>
            @endif

            <th style="width:220px">Aksi</th>
          </tr>
        </thead>

        <tbody>
          @forelse ($items as $p)
            @php
              $cover = $p->cover ?: $p->image;

              // biar aman dipakai di inventaris juga
              $approvedAt = null;
              if ($p->approved_at) {
                $approvedAt = $p->approved_at instanceof \Illuminate\Support\Carbon
                  ? $p->approved_at
                  : \Carbon\Carbon::parse($p->approved_at);
              }
            @endphp

            <tr>
              {{-- COVER --}}
              <td>
                <img
                  src="{{ $cover
                        ? (str_starts_with($cover, 'perumahan/')
                            ? asset('storage/'.$cover)
                            : asset($cover))
                        : asset('images/placeholder-house.jpg') }}"
                  alt=""
                  style="width:60px;height:42px;object-fit:cover;border-radius:6px;">
              </td>

              {{-- NAMA --}}
              <td>
                <div style="font-weight:600;font-size:14px;">{{ $p->nama }}</div>

                @if($approvedAt)
                  <div class="muted small">
                    Disetujui: {{ $approvedAt->format('d M Y H:i') }}
                  </div>
                @endif
              </td>

              {{-- ✅ Perusahaan (inventaris) --}}
              @if($status === 'inventaris')
                <td>{{ $p->nama_perusahaan ?? '-' }}</td>
              @endif

              {{-- DEVELOPER --}}
              <td>{{ optional($p->developer)->name ?? '-' }}</td>

              {{-- LOKASI --}}
              <td>{{ $p->lokasi ?? '-' }}</td>

              {{-- STATUS / APPROVED --}}
              @if($status !== 'inventaris')
                <td>
                  @php
                    $statusVal = $p->status ?? 'pending';
                    $adaRevisi = !empty($p->catatan_revisi);

                    if ($statusVal === 'disetujui') {
                        $label = 'Disetujui';
                        $class = 'badge-ok';
                    } elseif ($statusVal === 'pending' && $adaRevisi) {
                        $label = 'Perlu Revisi';
                        $class = 'badge-danger';
                    } else {
                        $label = 'Pending';
                        $class = 'badge-warn';
                    }
                  @endphp

                  <span class="badge {{ $class }}">{{ $label }}</span>
                </td>
              @else
                <td class="muted small">
                  {{ $approvedAt ? $approvedAt->format('d M Y') : '-' }}
                </td>
              @endif

              {{-- AKSI --}}
              <td>
                <a href="{{ route('dinas.perumahan.verify.show', $p->id) }}"
                   class="btn-plain"
                   style="font-size:12px;">
                  Detail &amp; Verifikasi
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="{{ $status === 'inventaris' ? 7 : 6 }}"
                  class="muted" style="text-align:center;font-size:13px;">
                Belum ada data perumahan untuk status ini.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- PAGINATION --}}
    <div style="margin-top:10px;">
      {{ $items->appends(request()->query())->links() }}
    </div>
  </div>
@endsection