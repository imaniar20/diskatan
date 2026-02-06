@extends('admin.layouts.app')
@section('Content')
    <style>
        td img {
            width: 40px;
            /* lebih konsisten di tabel */
            height: 40px;
            object-fit: cover;
            /* biar ga gepeng */
            display: block;
            margin: 0 auto;
            border-radius: 6px;
        }
    </style>
    <div class="card mb-4 order-0">
        <h5 class="card-header">Table {{ $menu }}</h5>
        <div class="card-body">
            <div class="row">
                <div class="col-4">
                    <form class="mb-4" id="addBidang" action="{{ route('admin-produksi.store') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <label for="nama" class="form-label">Nama Produksi<span class="text-danger">*</span></label>
                        <input type="text" class="form-control mb-3" placeholder="Nama" aria-label="Nama" id="nama"
                            name="nama" aria-describedby="basic-addon11" required />

                        <label for="nilai" class="form-label">Nilai<span class="text-danger">*</span></label>
                        <input type="number" min="1" class="form-control mb-3" placeholder="Nilai" aria-label="nilai"
                            id="nilai" name="nilai" aria-describedby="basic-addon11" required />

                        <label for="satuan" class="form-label">Satuan<span class="text-danger">*</span></label>
                        <input type="text" class="form-control mb-3" placeholder="Satuan" aria-label="satuan"
                            id="satuan" name="satuan" value="ton" aria-describedby="basic-addon11" readonly />

                        <label for="tahun" class="form-label">Tahun<span class="text-danger">*</span></label>
                        <input type="number" min="2000" max="{{ date('Y') }}" class="form-control mb-3"
                            placeholder="Tahun" aria-label="Icon" id="tahun" name="tahun"
                            aria-describedby="basic-addon11" required />

                        <div class="mb-3">
                            <div class="text-center">
                                <button type="submit" class="btn btn-success">Tambah Produksi</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-8">
                    <div class="table-responsive p-2">
                        <table class="table table-striped datatable display nowrap">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 5%">No</th>
                                    <th>Nama</th>
                                    <th>Nilai</th>
                                    <th>Satuan</th>
                                    <th>Tahun</th>
                                    <th style="width: 20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($produksi as $data)
                                    <tr>
                                        <td class="text-center">{{ $i++ }}</td>
                                        <td>{{ $data->name }}</td>
                                        <td>{{ $data->value }}</td>
                                        <td>{{ $data->unit }}</td>
                                        <td>{{ $data->year }}</td>
                                        <td>
                                            <a class="btn btn-sm btn-warning text-white open-Edit" data-bs-toggle="modal"
                                                data-bs-target="#edit" data-id="{{ $data->id }}"
                                                data-name="{{ $data->name }}" data-value="{{ $data->value }}" data-year="{{ $data->year }}"><i
                                                    class="bx bx-edit-alt me-1"></i> Edit</a>
                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="deleteUser('{{ route('admin-produksi.destroy', $data->id) }}')">
                                                <i class="bx bx-trash-alt me-1"></i> Hapus
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form class="modal-content" method="POST" action="{{ route('admin-produksi.update', 1) }}" id="edit"
                enctype="multipart/form-data">
                <form class="modal-content" id="editForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="backDropModalTitle">Data Produksi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <input type="hidden" name="id" id="id">
                    </div>
                    <div class="modal-body">
                        <label for="nama_edit" class="form-label">Nama Produksi<span class="text-danger">*</span></label>
                        <input type="text" class="form-control mb-3" placeholder="Nama" aria-label="Nama"
                            id="nama_edit" name="nama_edit" aria-describedby="basic-addon11" required />

                        <label for="nilai_edit" class="form-label">Nilai<span class="text-danger">*</span></label>
                        <input type="number" min="1" class="form-control mb-3" placeholder="Nilai" aria-label="nilai"
                            id="nilai_edit" name="nilai_edit" aria-describedby="basic-addon11" required />

                        <label for="satuan_edit" class="form-label">Satuan<span class="text-danger">*</span></label>
                        <input type="text" class="form-control mb-3" placeholder="Satuan" aria-label="satuan"
                            id="satuan_edit" name="satuan_edit" value="ton"  aria-describedby="basic-addon11" readonly />

                        <label for="tahun_edit" class="form-label">Tahun<span class="text-danger">*</span></label>
                        <input type="number" min="2000" max="{{ date('Y') }}" class="form-control mb-3"
                            placeholder="Tahun" aria-label="Icon" id="tahun_edit" name="tahun_edit"
                            aria-describedby="basic-addon11" required />
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitWithoutNotif">Ubah</button>
                    </div>
                </form>
        </div>
    </div>
    <script>
        $(document).on("click", ".open-Edit", function() {
            var id = $(this).data('id');
            var nama = $(this).data('name');
            var nilai = $(this).data('value');
            var tahun = $(this).data('year');

            $(".modal-header #id").val(id);
            $(".modal-body #nama_edit").val(nama);
            $(".modal-body #nilai_edit").val(nilai);
            $(".modal-body #tahun_edit").val(tahun);
        });

        function deleteUser(url) {
            Swal.fire({
                title: 'Yakin mau hapus?',
                text: 'Data akan dihapus permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {

                    fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(res => {
                            if (!res.ok) throw new Error('Gagal menghapus');
                            return res.json();
                        })
                        .then(data => {
                            Swal.fire(
                                'Terhapus!',
                                data.message ?? 'Data berhasil dihapus.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        })
                        .catch(err => {
                            Swal.fire(
                                'Error',
                                'Terjadi kesalahan saat menghapus data.',
                                'error'
                            );
                        });

                }
            });
        }
    </script>
@endsection
