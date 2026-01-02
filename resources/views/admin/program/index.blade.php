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
            <a class="btn btn-success text-white" href="{{ route('admin-program.create') }}"><i
                    class='bx bx-plus-circle me-2'></i>Tambah Data</a>
            <div class="table-responsive p-2">
                <table class="table table-striped datatable display nowrap">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5%">No</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th class="text-start">Tahun</th>
                            <th class="text-center" style="width: 20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @php
                            $i = 1;
                        @endphp
                        @foreach ($program as $data)
                            <tr>
                                <td class="text-center">{{ $i++ }}</td>
                                <td>{{ $data->name }}</td>
                                <td>{{ $data->kategori->nama }}</td>
                                <td><span class="badge {{ $data->status === 'aktif' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ucfirst($data->status) }}
                                    </span>
                                </td>
                                <td class="text-start">{{ $data->tahun }}</td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-warning text-white"
                                        href="{{ route('admin-program.show', $data->id) }}">
                                        <i class="bx bx-edit-alt me-1"></i> Edit</a>
                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick="deleteUser('{{ route('admin-program.destroy', $data->id) }}')">
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
    <script>
        function deleteUser(url) {
            Swal.fire({
                title: 'Yakin mau hapus?',
                text: 'Data user akan dihapus permanen!',
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
