@extends('admin.layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold py-1 mb-1">
                <span class="text-muted fw-light">Survei Kepuasan /</span> Master Jenis Layanan
            </h4>
            <p class="text-muted mb-0">Kelola daftar layanan publik DISKATAN yang dinilai oleh masyarakat</p>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahLayanan">
            <i class="bx bx-plus me-1"></i> Tambah Layanan
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
            <span>Daftar Layanan Publik</span>
            <span class="badge bg-label-info">{{ $layanan->count() }} Jenis Layanan</span>
        </h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>Nama Layanan</th>
                        <th>Deskripsi</th>
                        <th class="text-center">Respon Diterima</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($layanan as $idx => $item)
                        <tr>
                            <td class="text-center">{{ $idx + 1 }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $item->nama_layanan }}</div>
                            </td>
                            <td style="white-space: normal; max-width: 350px;">
                                <small class="text-muted">{{ $item->deskripsi ?? '-' }}</small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-label-secondary">
                                    {{ $item->survei_respons_count }} Respon
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
                                <button type="button" class="btn btn-sm btn-icon btn-outline-warning btn-edit-layanan" 
                                    data-id="{{ $item->id }}"
                                    data-nama="{{ $item->nama_layanan }}"
                                    data-deskripsi="{{ $item->deskripsi }}"
                                    data-status="{{ $item->status }}"
                                    title="Edit Layanan">
                                    <i class="bx bx-edit-alt"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-outline-danger btn-delete-layanan" 
                                    data-id="{{ $item->id }}"
                                    title="Hapus / Nonaktifkan">
                                    <i class="bx bx-trash"></i>
                                </button>
                                <form id="formDeleteL_{{ $item->id }}" action="{{ route('admin.survei.layanan.destroy', $item->id) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">Belum ada master layanan terdaftar.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH LAYANAN -->
<div class="modal fade" id="modalTambahLayanan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" action="{{ route('admin.survei.layanan.store') }}" method="POST">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tambah Jenis Layanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama Layanan <span class="text-danger">*</span></label>
                    <input type="text" name="nama_layanan" class="form-control" placeholder="Contoh: Surat Rekomendasi Pupuk Bersubsidi" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi Layanan</label>
                    <textarea name="deskripsi" rows="3" class="form-control" placeholder="Penjelasan singkat mengenai jenis layanan..."></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status Layanan <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="aktif">Aktif (Dapat Dinilai di Survei)</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Layanan</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT LAYANAN -->
<div class="modal fade" id="modalEditLayanan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" id="formEditLayanan" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Jenis Layanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama Layanan <span class="text-danger">*</span></label>
                    <input type="text" name="nama_layanan" id="editNamaLayanan" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi Layanan</label>
                    <textarea name="deskripsi" id="editDeskripsiLayanan" rows="3" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status Layanan <span class="text-danger">*</span></label>
                    <select name="status" id="editStatusLayanan" class="form-select" required>
                        <option value="aktif">Aktif (Dapat Dinilai di Survei)</option>
                        <option value="nonaktif">Nonaktif</option>
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

@push('after-script')
<script>
    $(document).ready(function() {
        $(document).on('click', '.btn-edit-layanan', function() {
            const id = $(this).data('id');
            const nama = $(this).data('nama');
            const deskripsi = $(this).data('deskripsi');
            const status = $(this).data('status');

            $('#formEditLayanan').attr('action', `/admin-survei-kepuasan/layanan/${id}`);
            $('#editNamaLayanan').val(nama);
            $('#editDeskripsiLayanan').val(deskripsi || '');
            $('#editStatusLayanan').val(status);

            const modal = new bootstrap.Modal(document.getElementById('modalEditLayanan'));
            modal.show();
        });

        $(document).on('click', '.btn-delete-layanan', function() {
            const id = $(this).data('id');
            Swal.fire({
                title: 'Hapus / Nonaktifkan?',
                text: 'Jika layanan telah memiliki riwayat pengisian survei, sistem otomatis mengubah statusnya menjadi nonaktif.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Proses!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formDeleteL_' + id).submit();
                }
            });
        });
    });
</script>
@endpush
