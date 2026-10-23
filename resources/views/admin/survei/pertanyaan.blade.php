@extends('admin.layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold py-1 mb-1">
                <span class="text-muted fw-light">Survei Kepuasan /</span> Master Pertanyaan
            </h4>
            <p class="text-muted mb-0">Kelola butir kuesioner instrumen survei kepuasan masyarakat DISKATAN</p>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahPertanyaan">
            <i class="bx bx-plus me-1"></i> Tambah Pertanyaan
        </button>
    </div>

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
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <h5 class="card-header d-flex justify-content-between align-items-center">
            <span>Daftar Butir Pertanyaan Kuesioner</span>
            <span class="badge bg-label-info">{{ $pertanyaan->count() }} Pertanyaan</span>
        </h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px;">Urutan</th>
                        <th>Teks Pertanyaan</th>
                        <th>Kunci Indikator</th>
                        <th class="text-center">Riwayat Suara</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($pertanyaan as $item)
                        <tr>
                            <td class="text-center">
                                <span class="badge bg-label-primary rounded-pill fw-bold fs-6">
                                    {{ $item->urutan }}
                                </span>
                            </td>
                            <td style="white-space: normal; min-width: 300px;">
                                <div class="fw-semibold text-dark">{{ $item->teks_pertanyaan }}</div>
                            </td>
                            <td>
                                <code>{{ $item->kunci_pertanyaan }}</code>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-label-secondary">
                                    {{ $item->detail_rating_count }} Penilaian
                                </span>
                            </td>
                            <td class="text-center">
                                @if($item->status == 'aktif')
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-icon btn-outline-warning" onclick='editPertanyaan(@json($item))' title="Edit Pertanyaan">
                                    <i class="bx bx-edit-alt"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-outline-danger" onclick="confirmDeletePertanyaan({{ $item->id }})" title="Hapus / Nonaktifkan">
                                    <i class="bx bx-trash"></i>
                                </button>
                                <form id="formDeleteP_{{ $item->id }}" action="{{ route('admin.survei.pertanyaan.destroy', $item->id) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">Belum ada data pertanyaan survei.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH PERTANYAAN -->
<div class="modal fade" id="modalTambahPertanyaan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" action="{{ route('admin.survei.pertanyaan.store') }}" method="POST">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tambah Pertanyaan Kuesioner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Teks Pertanyaan <span class="text-danger">*</span></label>
                    <textarea name="teks_pertanyaan" rows="3" class="form-control" placeholder="Contoh: Bagaimana pendapat Anda tentang kemudahan prosedur pelayanan..." required></textarea>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-sm-6">
                        <label class="form-label">Kunci Indikator <span class="text-danger">*</span></label>
                        <input type="text" name="kunci_pertanyaan" class="form-control" placeholder="contoh: prosedur, keramahan" required>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Nomor Urut <span class="text-danger">*</span></label>
                        <input type="number" name="urutan" class="form-control" value="{{ $pertanyaan->count() + 1 }}" min="1" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status Pertanyaan <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="aktif">Aktif (Tampil di Survei)</option>
                        <option value="nonaktif">Nonaktif (Disembunyikan)</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Pertanyaan</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT PERTANYAAN -->
<div class="modal fade" id="modalEditPertanyaan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" id="formEditPertanyaan" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Pertanyaan Kuesioner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Teks Pertanyaan <span class="text-danger">*</span></label>
                    <textarea name="teks_pertanyaan" id="editTeksPertanyaan" rows="3" class="form-control" required></textarea>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-sm-6">
                        <label class="form-label">Kunci Indikator <span class="text-danger">*</span></label>
                        <input type="text" name="kunci_pertanyaan" id="editKunciPertanyaan" class="form-control" required>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Nomor Urut <span class="text-danger">*</span></label>
                        <input type="number" name="urutan" id="editUrutan" class="form-control" min="1" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status Pertanyaan <span class="text-danger">*</span></label>
                    <select name="status" id="editStatus" class="form-select" required>
                        <option value="aktif">Aktif (Tampil di Survei)</option>
                        <option value="nonaktif">Nonaktif (Disembunyikan)</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function editPertanyaan(item) {
        document.getElementById('formEditPertanyaan').action = `/admin-survei-kepuasan/pertanyaan/${item.id}`;
        document.getElementById('editTeksPertanyaan').value = item.teks_pertanyaan;
        document.getElementById('editKunciPertanyaan').value = item.kunci_pertanyaan;
        document.getElementById('editUrutan').value = item.urutan;
        document.getElementById('editStatus').value = item.status;

        const modal = new bootstrap.Modal(document.getElementById('modalEditPertanyaan'));
        modal.show();
    }

    function confirmDeletePertanyaan(id) {
        Swal.fire({
            title: 'Hapus / Nonaktifkan?',
            text: 'Jika pertanyaan sudah memiliki data responden, sistem otomatis mengubah statusnya menjadi nonaktif agar tidak merusak rekapitulasi data.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Proses!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('formDeleteP_' + id).submit();
            }
        });
    }
</script>
@endpush
