@extends('admin.layouts.app')
@section('Content')
    <div class="card mb-4 order-0">
        <h5 class="card-header">Table Pengaduan</h5>
        <div class="card-body">
            <a class="btn btn-success text-white" href="{{ route('admin-berita.create') }}"><i
                    class='bx bx-plus-circle me-2'></i>Tambah Berita</a>
            <div class="table-responsive p-2">
                <table class="table table-striped datatable display nowrap">
                    <thead>
                        <tr>
                            <th></th>
                            <th>No</th>
                            <th>Foto</th>
                            <th>Judul</th>
                            <th>Konten</th>
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
                                         
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $i++ }}</td>
                                <td>{{ $data['thumbnail'] }}</td>
                                <td>{{ $data['title'] }}</td>
                                <td>{{ $data['contect'] }}</td>
                                <td>{{ $data['status'] }}</td>
                                <td>
                                    @if ($data['status'] == 'Publish')
                                        <span class="badge bg-label-info me-1">Publish</span>
                                    @else
                                        <span class="badge bg-label-danger me-1">Tidak Publish</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($data['published_at'])->format('d-M-Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
