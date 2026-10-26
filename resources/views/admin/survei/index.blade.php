@extends('admin.layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold py-1 mb-1">
                <span class="text-muted fw-light">Survei Kepuasan /</span> Rekapitulasi Responden
            </h4>
            <p class="text-muted mb-0">Pantau dan kelola hasil survei kepuasan masyarakat terhadap pelayanan DISKATAN</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.survei.export') }}" class="btn btn-outline-success">
                <i class="bx bx-download me-1"></i> Export Data CSV
            </a>
            <a href="{{ route('survei.index') }}" target="_blank" class="btn btn-primary">
                <i class="bx bx-link-external me-1"></i> Lihat Portal Publik
            </a>
        </div>
    </div>

    <!-- NOTIFIKASI -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="bx bx-error me-1"></i> {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- STATS CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-muted">Total Responden</span>
                            <div class="d-flex align-items-end mt-2">
                                <h4 class="mb-0 me-2">{{ number_format($totalResponden) }}</h4>
                                <small class="text-success">(Orang)</small>
                            </div>
                            <small class="text-muted">Warga Terlayani</small>
                        </div>
                        <span class="badge bg-label-primary rounded p-2">
                            <i class="bx bx-user bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-muted">Skor IKM Pelayanan</span>
                            <div class="d-flex align-items-end mt-2">
                                <h4 class="mb-0 me-2 text-primary">{{ number_format($skorIkm, 2) }}</h4>
                                <small class="text-muted">/ 100</small>
                            </div>
                            <small class="text-muted">Standar PermenPAN-RB</small>
                        </div>
                        <span class="badge bg-label-info rounded p-2">
                            <i class="bx bx-line-chart bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-muted">Mutu Pelayanan</span>
                            <div class="mt-2">
                                <span class="badge {{ $badgeClass }} fs-6">
                                    {{ $mutuPelayanan }}
                                </span>
                            </div>
                            <small class="text-muted mt-1 d-block">Tingkat Kepuasan</small>
                        </div>
                        <span class="badge bg-label-success rounded p-2">
                            <i class="bx bx-award bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-muted">Layanan Dinilai</span>
                            <div class="d-flex align-items-end mt-2">
                                <h4 class="mb-0 me-2">{{ $layanan->count() }}</h4>
                                <small class="text-muted">Layanan</small>
                            </div>
                            <small class="text-muted">Kategori Pelayanan</small>
                        </div>
                        <span class="badge bg-label-warning rounded p-2">
                            <i class="bx bx-category bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER CARD -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.survei.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Jenis Layanan</label>
                    <select name="jenis_layanan_id" class="form-select">
                        <option value="">-- Semua Layanan --</option>
                        @foreach($layanan as $l)
                            <option value="{{ $l->id }}" {{ request('jenis_layanan_id') == $l->id ? 'selected' : '' }}>
                                {{ $l->nama_layanan }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control" value="{{ request('tanggal_mulai') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" class="form-control" value="{{ request('tanggal_selesai') }}">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bx bx-filter-alt me-1"></i> Filter
                    </button>
                    @if(request()->hasAny(['jenis_layanan_id', 'tanggal_mulai', 'tanggal_selesai']))
                        <a href="{{ route('admin.survei.index') }}" class="btn btn-outline-secondary">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- RESPONDEN TABLE CARD -->
    <div class="card">
        <h5 class="card-header d-flex justify-content-between align-items-center">
            <span>Daftar Respon Responden</span>
            <span class="badge bg-label-primary">{{ $responses->total() }} Data Ditemukan</span>
        </h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Tanggal</th>
                        <th>Responden</th>
                        <th>Usia & JK</th>
                        <th>Layanan yang Dinilai</th>
                        <th class="text-center">Rata-rata Rating</th>
                        <th class="text-center" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($responses as $index => $row)
                        @php
                            $avg = $row->detailRating->avg('rating');
                        @endphp
                        <tr>
                            <td>{{ $responses->firstItem() + $index }}</td>
                            <td>
                                <i class="bx bx-calendar me-1 text-muted"></i>
                                {{ \Carbon\Carbon::parse($row->tanggal_survei)->translatedFormat('d M Y') }}
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $row->responden->nama_lengkap ?? '-' }}</div>
                                <small class="text-muted">{{ $row->responden->no_telepon ?? '-' }}</small>
                            </td>
                            <td>
                                <div><span class="badge bg-label-info">{{ $row->responden->jenis_kelamin ?? '-' }}</span></div>
                                <small class="text-muted">{{ $row->responden->usia ?? '-' }} Th</small>
                            </td>
                            <td>
                                <span class="fw-semibold text-primary">
                                    {{ $row->jenisLayanan->nama_layanan ?? 'Lainnya' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-label-warning fw-bold fs-6">
                                    ★ {{ number_format($avg, 2) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-icon btn-outline-primary btn-detail-survei" data-id="{{ $row->id }}" title="Lihat Detail Penilaian">
                                    <i class="bx bx-show"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-outline-danger btn-delete-survei" data-id="{{ $row->id }}" title="Hapus Data">
                                    <i class="bx bx-trash"></i>
                                </button>
                                <form id="formDelete_{{ $row->id }}" action="{{ route('admin.survei.destroy', $row->id) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bx bx-folder-open bx-lg mb-2"></i>
                                    <p class="mb-0">Belum ada respon survei kepuasan yang tercatat.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer border-top d-flex justify-content-end">
            {{ $responses->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- MODAL DETAIL SURVEI -->
<div class="modal fade" id="modalDetailSurvei" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">
                    <i class="bx bx-detail text-primary me-2"></i>
                    Rincian Penilaian Responden
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalDetailBody">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-script')
<script>
    $(document).ready(function() {
        $(document).on('click', '.btn-detail-survei', function() {
            const id = $(this).data('id');
            const modal = new bootstrap.Modal(document.getElementById('modalDetailSurvei'));
            const body = document.getElementById('modalDetailBody');
            body.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `;
            modal.show();

            fetch(`/admin-survei-kepuasan/detail/${id}`)
                .then(res => res.json())
                .then(res => {
                    if (res.status === 'success') {
                        const data = res.data;
                        const resp = data.responden || {};
                        const layanan = data.jenis_layanan || {};
                        const details = data.detail_rating || [];

                        let detailRows = '';
                        let totalRating = 0;

                        details.forEach((item, idx) => {
                            const pert = item.pertanyaan || {};
                            totalRating += item.rating;
                            let stars = '';
                            for (let s = 1; s <= 5; s++) {
                                stars += `<span style="color: ${s <= item.rating ? '#f59e0b' : '#cbd5e1'}; font-size: 1.2rem;">★</span>`;
                            }

                            detailRows += `
                                <tr>
                                    <td style="width: 35px;" class="fw-bold">${idx + 1}</td>
                                    <td>${pert.teks_pertanyaan || '-'}</td>
                                    <td class="text-end" style="white-space: nowrap;">
                                        ${stars}
                                        <span class="badge bg-label-warning ms-2 font-bold">${item.rating} / 5</span>
                                    </td>
                                </tr>
                            `;
                        });

                        const avg = details.length > 0 ? (totalRating / details.length).toFixed(2) : '0.00';

                        body.innerHTML = `
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <div class="text-muted small">Nama Responden</div>
                                        <div class="fw-bold fs-5 text-dark">${resp.nama_lengkap || '-'}</div>
                                        <div class="text-muted small mt-1">
                                            <i class="bx bx-user me-1"></i>${resp.jenis_kelamin || '-'} (${resp.usia || '-'} Th)
                                        </div>
                                        <div class="text-muted small">
                                            <i class="bx bx-phone me-1"></i>${resp.no_telepon || '-'} | Pekerjaan: ${resp.pekerjaan || '-'}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <div class="text-muted small">Layanan yang Dinilai</div>
                                        <div class="fw-bold fs-5 text-primary">${layanan.nama_layanan || 'Lainnya'}</div>
                                        <div class="text-muted small mt-1">
                                            <i class="bx bx-calendar me-1"></i>Tanggal Survei: ${data.tanggal_survei || '-'}
                                        </div>
                                        <div class="text-muted small">
                                            <i class="bx bx-star me-1 text-warning"></i>Rata-Rata Penilaian: <strong class="text-dark">${avg} / 5.0</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h6 class="fw-bold text-dark mb-3">Rincian Nilai per Indikator Pertanyaan:</h6>
                            <div class="table-responsive mb-4">
                                <table class="table table-bordered table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Indikator Pertanyaan</th>
                                            <th class="text-end">Nilai Bintang</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${detailRows}
                                    </tbody>
                                </table>
                            </div>

                            <h6 class="fw-bold text-dark mb-2">Kritik, Saran & Masukan:</h6>
                            <div class="p-3 bg-light rounded border">
                                <p class="mb-0 text-dark fst-italic">"${data.komentar ? data.komentar : 'Tidak ada catatan saran tambahan yang dituliskan.'}"</p>
                            </div>
                        `;
                    } else {
                        body.innerHTML = `<div class="alert alert-danger mb-0">Gagal mengambil data detail survei.</div>`;
                    }
                })
                .catch(() => {
                    body.innerHTML = `<div class="alert alert-danger mb-0">Terjadi kesalahan koneksi server.</div>`;
                });
        });

        $(document).on('click', '.btn-delete-survei', function() {
            const id = $(this).data('id');
            Swal.fire({
                title: 'Hapus Data Respon Ini?',
                text: 'Data penilaian dan identitas responden ini akan dihapus permanen dari sistem.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formDelete_' + id).submit();
                }
            });
        });
    });
</script>
@endpush
