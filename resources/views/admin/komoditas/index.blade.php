@extends('admin.layouts.app')

@section('Content')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h4 class="fw-bold py-1 mb-1">
                    <span class="text-muted fw-light">Pengaturan /</span> Master Komoditas
                </h4>
                <p class="text-muted mb-0">Kelola daftar komoditas yang tampil pada pill filter data publik dan formulir
                    admin.</p>
            </div>
            <div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahKomoditas">
                    <i class="bx bx-plus me-1"></i> Tambah Komoditas
                </button>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible" role="alert">
                <i class="bx bx-error-circle me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Card Filter Sektor & Pencarian -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <ul class="nav nav-pills gap-1">
                        <li class="nav-item">
                            <a class="nav-link {{ $selectedSektor === 'all' ? 'active' : '' }}"
                                href="{{ route('admin.komoditas.index', ['sektor' => 'all', 'search' => $search]) }}">
                                Semua <span class="badge rounded-pill bg-light text-dark ms-1">{{ $counts['all'] }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $selectedSektor === 'tanaman_pangan' ? 'active' : '' }}"
                                href="{{ route('admin.komoditas.index', ['sektor' => 'tanaman_pangan', 'search' => $search]) }}">
                                <i class="bx bx-sun me-1"></i> Tanaman Pangan
                                <span
                                    class="badge rounded-pill bg-light text-dark ms-1">{{ $counts['tanaman_pangan'] }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $selectedSektor === 'hortikultura' ? 'active' : '' }}"
                                href="{{ route('admin.komoditas.index', ['sektor' => 'hortikultura', 'search' => $search]) }}">
                                <i class="bx bx-leaf me-1"></i> Hortikultura
                                <span
                                    class="badge rounded-pill bg-light text-dark ms-1">{{ $counts['hortikultura'] }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $selectedSektor === 'perkebunan' ? 'active' : '' }}"
                                href="{{ route('admin.komoditas.index', ['sektor' => 'perkebunan', 'search' => $search]) }}">
                                <i class="bx bx-spa me-1"></i> Perkebunan
                                <span class="badge rounded-pill bg-light text-dark ms-1">{{ $counts['perkebunan'] }}</span>
                            </a>
                        </li>
                    </ul>

                    <form method="GET" action="{{ route('admin.komoditas.index') }}"
                        class="d-flex align-items-center gap-2">
                        <input type="hidden" name="sektor" value="{{ $selectedSektor }}">
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="bx bx-search"></i></span>
                            <input type="text" name="search" class="form-control form-control-sm"
                                placeholder="Cari komoditas..." value="{{ $search }}">
                        </div>
                        @if ($search)
                            <a href="{{ route('admin.komoditas.index', ['sektor' => $selectedSektor]) }}"
                                class="btn btn-sm btn-outline-secondary">Reset</a>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabel Komoditas -->
        <div class="card shadow-sm">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 5%">Urutan</th>
                            <th>Sektor</th>
                            <th>Nama Komoditas</th>
                            <th>Kategori Tambahan</th>
                            <th>Satuan Luas</th>
                            <th>Satuan Produksi</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" style="width: 15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($komoditas as $item)
                            <tr>
                                <td class="text-center fw-bold">{{ $item->urutan }}</td>
                                <td>
                                    @if ($item->sektor === 'tanaman_pangan')
                                        <span class="badge bg-label-warning"><i class="bx bx-sun me-1"></i> Tanaman Pangan</span>
                                    @elseif ($item->sektor === 'hortikultura')
                                        <span class="badge bg-label-success"><i class="bx bx-leaf me-1"></i> Hortikultura</span>
                                    @else
                                        <span class="badge bg-label-primary"><i class="bx bx-spa me-1"></i> Perkebunan</span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $item->nama }}</strong>
                                </td>
                                <td>
                                    @if ($item->kategori_sayuran)
                                        <span class="badge bg-label-info">{{ $item->kategori_sayuran }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $item->satuan_luas ?: 'Ha' }}</td>
                                <td>{{ $item->satuan_produksi ?: 'Ton' }}</td>
                                <td class="text-center">
                                    <form action="{{ route('admin.komoditas.toggle', $item->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-icon border-0 bg-transparent"
                                            title="Klik untuk ubah status">
                                            @if ($item->is_active)
                                                <span class="badge bg-success"><i class="bx bx-check me-1"></i> Aktif</span>
                                            @else
                                                <span class="badge bg-secondary"><i class="bx bx-x me-1"></i> Nonaktif</span>
                                            @endif
                                        </button>
                                    </form>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button type="button" class="btn btn-sm btn-icon btn-outline-primary btn-edit-komoditas"
                                            data-id="{{ $item->id }}" data-sektor="{{ $item->sektor }}"
                                            data-nama="{{ $item->nama }}" data-kategori="{{ $item->kategori_sayuran }}"
                                            data-satuan-luas="{{ $item->satuan_luas }}"
                                            data-satuan-produksi="{{ $item->satuan_produksi }}"
                                            data-urutan="{{ $item->urutan }}" data-active="{{ $item->is_active ? 1 : 0 }}"
                                            title="Edit Komoditas">
                                            <i class="bx bx-edit-alt"></i>
                                        </button>
                                        <form action="{{ route('admin.komoditas.destroy', $item->id) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus komoditas {{ $item->nama }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-icon btn-outline-danger"
                                                title="Hapus Komoditas">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    Tidak ada data komoditas ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($komoditas->hasPages())
                <div class="card-footer py-2">
                    {{ $komoditas->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>

    <!-- Modal Tambah Komoditas -->
    <div class="modal fade" id="modalTambahKomoditas" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('admin.komoditas.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="bx bx-plus-circle text-primary me-1"></i> Tambah Komoditas
                        Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Sektor Pertanian <span class="text-danger">*</span></label>
                        <select name="sektor" class="form-select" required id="tambah_sektor"
                            onchange="toggleKategoriSayuran(this.value, 'tambah_kat_wrapper')">
                            <option value="tanaman_pangan" {{ $selectedSektor === 'tanaman_pangan' ? 'selected' : '' }}>
                                Tanaman Pangan</option>
                            <option value="hortikultura" {{ $selectedSektor === 'hortikultura' ? 'selected' : '' }}>
                                Hortikultura</option>
                            <option value="perkebunan" {{ $selectedSektor === 'perkebunan' ? 'selected' : '' }}>Perkebunan
                            </option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Komoditas <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" placeholder="Contoh: Sorgum, Porang, Sawit"
                            required>
                    </div>
                    <div class="mb-3 {{ $selectedSektor === 'hortikultura' ? '' : 'd-none' }}" id="tambah_kat_wrapper">
                        <label class="form-label fw-bold">Kategori Sub-Hortikultura</label>
                        <input type="text" name="kategori_sayuran" class="form-control"
                            placeholder="Contoh: Sayuran, Buah-buahan, Biofarmaka">
                        <small class="text-muted">Opsional (khusus sektor Hortikultura).</small>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold">Satuan Luas</label>
                            <input type="text" name="satuan_luas" class="form-control" value="Ha"
                                placeholder="Ha / Pohon / M2">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold">Satuan Produksi</label>
                            <input type="text" name="satuan_produksi" class="form-control" value="Ton"
                                placeholder="Ton / Kuintal / Kg">
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold">Nomor Urutan Tampil</label>
                            <input type="number" name="urutan" class="form-control" value="99" min="0">
                            <small class="text-muted">Nomor lebih kecil tampil lebih awal.</small>
                        </div>
                        <div class="col-6 d-flex align-items-center mt-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="tambah_active"
                                    value="1" checked>
                                <label class="form-check-label fw-bold" for="tambah_active">Status Aktif</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan Komoditas</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Komoditas -->
    <div class="modal fade" id="modalEditKomoditas" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formEditKomoditas" method="POST" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="bx bx-edit text-primary me-1"></i> Edit Komoditas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Sektor Pertanian <span class="text-danger">*</span></label>
                        <select name="sektor" id="edit_sektor" class="form-select" required
                            onchange="toggleKategoriSayuran(this.value, 'edit_kat_wrapper')">
                            <option value="tanaman_pangan">Tanaman Pangan</option>
                            <option value="hortikultura">Hortikultura</option>
                            <option value="perkebunan">Perkebunan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Komoditas <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="edit_nama" class="form-control" required>
                    </div>
                    <div class="mb-3" id="edit_kat_wrapper">
                        <label class="form-label fw-bold">Kategori Sub-Hortikultura</label>
                        <input type="text" name="kategori_sayuran" id="edit_kategori" class="form-control">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold">Satuan Luas</label>
                            <input type="text" name="satuan_luas" id="edit_satuan_luas" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold">Satuan Produksi</label>
                            <input type="text" name="satuan_produksi" id="edit_satuan_produksi" class="form-control">
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold">Nomor Urutan Tampil</label>
                            <input type="number" name="urutan" id="edit_urutan" class="form-control" min="0">
                        </div>
                        <div class="col-6 d-flex align-items-center mt-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="edit_active" value="1">
                                <label class="form-check-label fw-bold" for="edit_active">Status Aktif</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleKategoriSayuran(sektor, wrapperId) {
            const wrap = document.getElementById(wrapperId);
            if (wrap) {
                if (sektor === 'hortikultura') {
                    wrap.classList.remove('d-none');
                } else {
                    wrap.classList.add('d-none');
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const modalEdit = new bootstrap.Modal(document.getElementById('modalEditKomoditas'));
            const formEdit = document.getElementById('formEditKomoditas');

            document.querySelectorAll('.btn-edit-komoditas').forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const sektor = this.getAttribute('data-sektor');
                    const nama = this.getAttribute('data-nama');
                    const kategori = this.getAttribute('data-kategori');
                    const luas = this.getAttribute('data-satuan-luas');
                    const prod = this.getAttribute('data-satuan-produksi');
                    const urutan = this.getAttribute('data-urutan');
                    const active = this.getAttribute('data-active') === '1';

                    formEdit.action = `/admin-komoditas/${id}`;
                    document.getElementById('edit_sektor').value = sektor;
                    document.getElementById('edit_nama').value = nama;
                    document.getElementById('edit_kategori').value = kategori || '';
                    document.getElementById('edit_satuan_luas').value = luas || 'Ha';
                    document.getElementById('edit_satuan_produksi').value = prod || 'Ton';
                    document.getElementById('edit_urutan').value = urutan;
                    document.getElementById('edit_active').checked = active;

                    toggleKategoriSayuran(sektor, 'edit_kat_wrapper');
                    modalEdit.show();
                });
            });
        });
    </script>
@endsection