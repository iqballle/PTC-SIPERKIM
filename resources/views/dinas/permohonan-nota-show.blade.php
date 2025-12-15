@extends('layouts.dinas')

@section('title', 'Detail Permohonan Nota Dinas — SIPERKIM')

@push('styles')
  <style>
    /* ====== STYLE KHUSUS HALAMAN DETAIL NOTA DINAS DINAS ====== */

    .decision-actions {
      display: flex;
      gap: 10px;
      justify-content: flex-end;
      margin-top: 12px;
    }

    .btn-approve {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 8px 16px;
      border-radius: 999px;
      border: none;
      cursor: pointer;
      font-size: 13px;
      font-weight: 600;
      background: #16a34a;      /* hijau */
      color: #ffffff;
      transition: filter .15s ease;
    }
    .btn-approve:hover {
      filter: brightness(1.05);
    }

    .btn-revisi {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 8px 14px;
      border-radius: 999px;
      border: 1px solid #b91c1c;
      background: #ffffff;
      color: #b91c1c;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      transition: background .15s ease, color .15s ease, filter .15s ease;
    }
    .btn-revisi:hover {
      background: #fef2f2;
      filter: brightness(1.02);
    }

    .textarea-dinas {
      width: 100%;
      border-radius: 8px;
      border: 1px solid #d1d5db;
      padding: 9px 11px;
      font-size: 13px;
      outline: none;
      min-height: 70px;
      resize: vertical;
      background: #ffffff;
      transition: border-color .15s, box-shadow .15s;
    }
    .textarea-dinas:focus {
      border-color: #546A2C;
      box-shadow: 0 0 0 1px rgba(84,106,44,0.25);
    }
  </style>
@endpush

@section('content')
  <div class="topbar">
    <div>
      <h1>Detail Permohonan Nota Dinas</h1>
      <p style="font-size:13px;color:#6b7280;margin-top:4px;">
        Periksa identitas pengembang, perumahan, dan kelengkapan dokumen sebelum memutuskan.
      </p>
    </div>
    <div class="topbar-right">
      <a href="{{ route('dinas.permohonan.nota.index') }}" class="btn-plain">← Kembali</a>
    </div>
  </div>

  {{-- STATUS --}}
  <section class="card" style="padding:14px 18px;margin-bottom:14px;">
    @php
      $status = $permohonan->status ?? 'pending';
      $badgeClass = 'badge-pending';
      $label = 'Pending';

      if ($status === 'disetujui') {
        $badgeClass = 'badge-ok';
        $label = 'Disetujui';
      } elseif ($status === 'revisi') {
        $badgeClass = 'badge-revisi';
        $label = 'Perlu Revisi';
      } elseif ($status === 'ditolak') {
        $badgeClass = 'badge-bad';
        $label = 'Ditolak';
      }
    @endphp

    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;">
      <div>
        <div class="{{ $badgeClass }} badge-status">
          Status: {{ $label }}
        </div>

        @if($permohonan->verified_at)
          @php
            $verifiedAt = $permohonan->verified_at instanceof \Illuminate\Support\Carbon
                ? $permohonan->verified_at
                : \Carbon\Carbon::parse($permohonan->verified_at);
          @endphp

          <div style="font-size:11px;color:#6b7280;margin-top:4px;">
            Diproses pada {{ $verifiedAt->format('d M Y H:i') }}
            @if($permohonan->verified_by)
              oleh {{ $permohonan->verified_by }}
            @endif
          </div>
        @endif
      </div>

      <div style="text-align:right;font-size:11px;color:#6b7280;">
        Diajukan pada {{ $permohonan->created_at->format('d M Y H:i') }}<br>
        Oleh: {{ $permohonan->user->name ?? 'Developer' }}
      </div>
    </div>
  </section>

  {{-- IDENTITAS + DOKUMEN --}}
  <section class="card" style="padding:20px 22px;">
    <div class="detail-layout">
      {{-- KIRI: IDENTITAS --}}
      <div>
        <h2 class="detail-section-title">Identitas Pengembang & Perumahan</h2>
        <table class="info-table">
          <tr>
            <th>Nama Pengembang</th>
            <td>{{ $permohonan->nama_pengembang }}</td>
          </tr>
          <tr>
            <th>Nama Perumahan</th>
            <td>{{ $permohonan->nama_perumahan }}</td>
          </tr>
          <tr>
            <th>Perumahan (SIPERKIM)</th>
            <td>
              @if($permohonan->perumahan)
                {{ $permohonan->perumahan->nama }} — {{ $permohonan->perumahan->lokasi }}
              @else
                -
              @endif
            </td>
          </tr>
          <tr>
            <th>Telepon</th>
            <td>{{ $permohonan->telepon ?? '-' }}</td>
          </tr>
          <tr>
            <th>Alamat Perumahan</th>
            <td>{{ $permohonan->alamat_perumahan }}</td>
          </tr>
          <tr>
            <th>Kelurahan</th>
            <td>{{ $permohonan->kelurahan }}</td>
          </tr>
          <tr>
            <th>Kecamatan</th>
            <td>{{ $permohonan->kecamatan }}</td>
          </tr>
        </table>

        @if($permohonan->keterangan_tambahan)
          <div style="margin-top:14px;">
            <div class="font-semibold text-sm mb-1">Keterangan Tambahan</div>
            <div style="font-size:13px;color:#374151;white-space:pre-line;">
              {{ $permohonan->keterangan_tambahan }}
            </div>
          </div>
        @endif
      </div>

      {{-- KANAN: DOKUMEN --}}
      <div>
        <h2 class="detail-section-title">Kelengkapan Dokumen</h2>
        <table class="doc-table">
          <thead>
            <tr>
              <th style="width:60%;">Uraian</th>
              <th>File</th>
            </tr>
          </thead>
          <tbody>
            @php
              $docs = [
                'surat_permohonan'        => 'Surat permohonan Nota Dinas / Pengesahan Site Plan',
                'profil_perusahaan'       => 'Profil perusahaan',
                'ktp_direktur'            => 'KTP Direktur/Direktris',
                'npwp_perusahaan'         => 'NPWP Perusahaan',
                'akte_pendirian'          => 'Akte pendirian perusahaan',
                'surat_kesiapan_psu'      => 'Surat pernyataan kesiapan penyerahan PSU',
                'surat_tidak_sengketa'    => 'Surat keterangan lahan tidak dalam sengketa',
                'pkkpr'                   => 'PKKPR',
                'nib_kbli'                => 'NIB dan KBLI',
                'peil_banjir'             => 'Peil Banjir',
                'alas_hak'                => 'Fotocopy alas hak kepemilikan lahan',
                'bast_tahap_pengembangan' => 'BAST tahap pengembangan',
                'siteplan_a3'             => 'Site plan / rencana tapak (A3)',
                'peta_lokasi'             => 'Peta lokasi',
                'site_plan'               => 'Site plan',
                'kontur_tanah'            => 'Site plan penempatan kontur tanah',
                'rencana_jalan'           => 'Rencana jalan & detail',
                'rencana_drainase'        => 'Rencana drainase & detail',
                'rencana_rth'             => 'Rencana RTH & detail',
                'rencana_air_bersih'      => 'Rencana jaringan air bersih & detail',
                'rencana_sanitasi'        => 'Rencana sanitasi & detail',
                'rencana_fasum_fasos'     => 'Rencana fasum/fasos lainnya & detail',
              ];
            @endphp

            @foreach($docs as $field => $label)
              @php
                $path = $permohonan->{$field};
              @endphp
              <tr>
                <td>{{ $label }}</td>
                <td>
                  @if($path)
                    <a href="{{ asset('storage/' . $path) }}" target="_blank" class="doc-link">
                      Lihat
                    </a>
                  @else
                    <span class="doc-missing">Belum diunggah</span>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </section>

  {{-- FORM KEPUTUSAN DINAS --}}
  <section class="card" style="padding:18px 20px;margin-top:16px;">
    <h2 class="text-base font-semibold mb-2">Keputusan Dinas</h2>
    <p style="font-size:12px;color:#6b7280;margin-bottom:10px;">
      Berikan catatan bila perlu. Catatan akan terlihat oleh pihak developer pada dashboard mereka.
    </p>

    @if($errors->any())
      <div style="padding:10px 12px;margin-bottom:10px;background:#fef2f2;border-left:4px solid #b91c1c;font-size:13px;">
        @foreach($errors->all() as $e)
          <div>• {{ $e }}</div>
        @endforeach
      </div>
    @endif

    @if(in_array($permohonan->status, ['pending', 'revisi']))
      {{-- FORM REVISI --}}
      <form method="POST" action="{{ route('dinas.permohonan.nota.revisi', $permohonan->id) }}" style="margin-bottom:10px;">
        @csrf
        <div>
          <label class="block text-sm font-semibold mb-1">Catatan Dinas</label>
          <textarea
            name="catatan_dinas"
            rows="3"
            class="textarea-dinas"
            placeholder="Catatan / alasan revisi atau persetujuan..."
          >{{ old('catatan_dinas', $permohonan->catatan_dinas) }}</textarea>
        </div>

        <div class="decision-actions">
          <button type="submit" class="btn-revisi">
            Tandai Perlu Revisi
          </button>
        </div>
      </form>

      {{-- FORM SETUJUI (catatan opsional) --}}
      <form method="POST" action="{{ route('dinas.permohonan.nota.approve', $permohonan->id) }}">
        @csrf
        <input type="hidden" name="catatan_dinas" value="{{ old('catatan_dinas', $permohonan->catatan_dinas) }}">
        <div class="decision-actions">
          <button type="submit" class="btn-approve">
            Setujui Permohonan
          </button>
        </div>
      </form>
    @else
      {{-- Jika SUDAH disetujui / ditolak: tidak ada tombol lagi --}}
      <div style="font-size:13px;color:#6b7280;margin-top:6px;">
        Permohonan ini sudah <strong>{{ $label }}</strong>.  
        Tidak ada tindakan lebih lanjut yang dapat dilakukan.
      </div>
    @endif
  </section>
@endsection