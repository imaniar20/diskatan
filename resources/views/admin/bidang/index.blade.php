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
                    <form class="mb-4" id="addBidang" action="{{ route('admin-bidang.store') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <label for="name" class="form-label">Nama Bidang<span class="text-danger">*</span></label>
                        <input type="text" class="form-control mb-3" placeholder="Nama" aria-label="Nama" id="name"
                            name="name" aria-describedby="basic-addon11" />
                        <small class="text-danger" id="errName"></small>

                        <div class="mb-3">
                            <div class="text-center">
                                <button type="submit" class="btn btn-success">Tambah Bidang</button>
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
                                    <th style="width: 20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($bidang as $data)
                                    <tr>
                                        <td class="text-center">{{ $i++ }}</td>
                                        <td>{{ $data->nama }}</td>
                                        <td>
                                            <a class="btn btn-sm btn-warning text-white open-Edit" data-bs-toggle="modal"
                                                data-bs-target="#edit" data-id="{{ $data->id }}"
                                                data-nama="{{ $data->nama }}"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="deleteUser('{{ route('admin-bidang.destroy', $data->id) }}')">
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
            <form class="modal-content" method="POST" action="{{ route('admin-bidang.update', 1) }}" id="edit"
                enctype="multipart/form-data">
                <form class="modal-content" id="editForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="backDropModalTitle">Data Bidang</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <input type="hidden" name="id" id="id">
                    </div>
                    <div class="modal-body">
                        <div class="col-lg-12 col-sm-12 mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input id="nama_edit" name="nama_edit" class="form-control">
                            <small class="text-danger" id="errNameEdit"></small>
                        </div>
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
        const handlerName = (e) => {
            let name = $('#name').val().trim();

            if (!name) {
                $('#errName').html('Nama Bidang Tidak Boleh Kosong');
                e.preventDefault();
                return false;
            } else if (name.length < 3) {
                $('#errName').html('Setidaknya Nama Bidang Minimal 3 Huruf');
                e.preventDefault();
                return false;
            } else {
                $('#errName').html('');
                return true;
            }
        };

        $('#addBidang').on('submit', function(e) {
            handlerName(e)
        });

        const handlerNameEdit = (e) => {
            let name = $('#nama_edit').val();

            if (!name) {
                $('#errNameEdit').html('Nama Bidang Tidak Boleh Kosong');
                e.preventDefault();
                return false;
            } else if (name.length < 3) {
                $('#errNameEdit').html('Setidaknya Nama Bidang Minimal 3 Huruf');
                e.preventDefault();
                return false;
            } else {
                $('#errNameEdit').html('');
                return true;
            }
        };

        $('#edit').on('submit', function(e) {
            handlerNameEdit(e)
        });


        $(document).on("click", ".open-Edit", function() {
            var id = $(this).data('id');
            var nama = $(this).data('nama');
            
            $(".modal-header #id").val(id);
            $(".modal-body #nama_edit").val(nama);
        });

        function deleteUser(url) {
            Swal.fire({
                title: 'Yakin mau hapus?',
                text: 'Data Bidang akan dihapus permanen!',
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
                                data.message ?? 'User berhasil dihapus.',
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
