@extends('admin.layouts.app')
@section('Content')
    <div class="pagetitle">
        <h1>{{ $title }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin-program.index') }}">{{ $title }}</a></li>
                <li class="breadcrumb-item active">{{ $menu }}</li>
            </ol>
        </nav>
    </div>
    <div class="section">
        <div class="row">
            <div class="col-3">

            </div>

            <div class="col-md-6" style="border-bottom: 0px">
                <div class="card mb-4">
                    <div class="card-body demo-vertical-spacing demo-only-element">
                        <form class="mb-4" id="addKategori" action="{{ route('admin-program.store') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">

                                <div class="col-12 mb-3">
                                    <label for="kategori" class="form-label">Kategori <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control" name="kategori" id="kategori">
                                        <option value="" selected disabled>- Pilih Kategori -</option>
                                        @foreach ($kategori as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                        @endforeach

                                    </select>
                                    <small class="text-danger" id="errKategori"></small>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="name" class="form-label">Nama Program<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Nama" aria-label="Nama"
                                        id="name" name="name" aria-describedby="basic-addon11" />
                                    <small class="text-danger" id="errName"></small>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="status" class="form-label">Status <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control" name="status" id="status">
                                        <option value="aktif">Aktif</option>
                                        <option value="nonaktif">Nonaktif</option>
                                    </select>
                                    <small class="text-danger" id="errStatus"></small>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="name" class="form-label">Tahun<span class="text-danger">*</span></label>
                                    <input type="number" min="1945" max="{{ date('Y') + 5 }}"
                                        value="{{ date('Y') }}" class="form-control" placeholder="Tahun"
                                        aria-label="Tahun" id="tahun" name="tahun" aria-describedby="basic-addon11" />
                                    <small class="text-danger" id="errTahun"></small>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="justify-content-center">
                                    <button type="submit" class="btn btn-success">Tambah</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-3">

            </div>
        </div>
    </div>

    <script>
        const handlerKategori = (e) => {
            let kategori = $('#kategori').val();

            if (!kategori) {
                $('#errKategori').html('Kategori Wajib Dipilih');
                e.preventDefault();
                return false;
            } else {
                $('#errKategori').html('');
                return true;
            }
        };

        const handlerName = (e) => {
            let name = $('#name').val().trim();

            if (!name) {
                $('#errName').html('Nama Tidak Boleh Kosong');
                e.preventDefault();
                return false;
            } else if (name.length < 3) {
                $('#errName').html('Setidaknya Nama Minimal 3 Huruf');
                e.preventDefault();
                return false;
            } else {
                $('#errName').html('');
                return true;
            }
        };

        const handlerTahun = (e) => {
            let tahun = $('#tahun').val().trim();
            let currentYear = new Date().getFullYear();

            if (!tahun) {
                $('#errTahun').html('Tahun tidak boleh kosong');
                e.preventDefault();
                return false;
            }

            tahun = parseInt(tahun);

            if (isNaN(tahun) || tahun < 1945 || tahun > currentYear + 5) {
                $('#errTahun').html(`Tahun harus antara 1945 - ${currentYear + 5}`);
                e.preventDefault();
                return false;
            }

            $('#errTahun').html('');
            return true;
        };

        $('#addKategori').on('submit', function(e) {
            handlerKategori(e)
            handlerName(e)
            handlerTahun(e)
        });
    </script>
@endsection
