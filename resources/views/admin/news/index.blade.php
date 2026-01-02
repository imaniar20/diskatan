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
            <a class="btn btn-success text-white" href="{{ route('admin-berita.create') }}"><i
                    class='bx bx-plus-circle me-2'></i>Tambah Berita</a>
            <div class="table-responsive p-2">
                <table class="table table-striped datatable display nowrap">
                    <thead>
                        <tr>
                            <th style="width: 2%"></th>
                            <th class="text-center" style="width: 5%">No</th>
                            <th>Foto</th>
                            <th>Judul</th>
                            {{-- <th>Konten</th> --}}
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @php
                            $i = 1;
                        @endphp
                        @foreach ($news as $data)
                            <tr>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('admin-berita.edit', $data->slug) }}">
                                                <i class="bx bx-edit-alt me-1"></i> Edit</a>
                                            <button type="button" class="dropdown-item text-danger"
                                                onclick="deleteNews('{{ route('admin-berita.destroy', $data->slug) }}')">
                                                <i class="bx bx-trash-alt me-1"></i> Hapus
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">{{ $i++ }}</td>
                                <td><img src="{{ asset('storage/' . $data['thumbnail']) }}" alt="gambar" width="100">
                                </td>
                                <td>{{ Str::limit($data['title'], 50) }}</td>
                                {{-- <td>{!! Str::limit(preg_replace('/<img[^>]*>/i', '', $data['content']), 40) !!} </td> --}}

                                <td>
                                    @if ($data['status'] == 'published')
                                        <span class="badge bg-label-info me-1">Publish</span>
                                    @else
                                        <span class="badge bg-label-warning me-1">Draft</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($data['published_at'])->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        function deleteNews(url) {
            Swal.fire({
                title: 'Yakin mau hapus?',
                text: 'Data berita dan semua gambarnya akan dihapus permanen!',
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
                                data.message ?? 'Berita berhasil dihapus.',
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
