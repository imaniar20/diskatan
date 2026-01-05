@extends('admin.layouts.app')
@section('Content')
    <div class="pagetitle">
        <h1>{{ $title }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">{{ $title }}</li>
                <li class="breadcrumb-item active">Ubah Data</li>
            </ol>
        </nav>
    </div>
    <form action="{{ route('admin-data.update', $data->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <!-- Table Data -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100">
                    <h5 class="card-header">Table Data</h5>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="hektar_luas_tanam" class="form-label">Hektar Luas Tanam<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="Hektar Luas Tanam"
                                aria-label="hektar_luas_tanam" id="hektar_luas_tanam" name="hektar_luas_tanam"
                                aria-describedby="basic-addon11" value="{{ $data->hektar_luas_tanam }}" required />
                        </div>

                        <div class="mb-3">
                            <label for="ton_produksi" class="form-label">Ton Produksi<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="Ton Produksi" aria-label="ton_produksi"
                                id="ton_produksi" name="ton_produksi" aria-describedby="basic-addon11"
                                value="{{ $data->ton_produksi }}" required />
                        </div>

                        <div class="mb-3">
                            <label for="kelompok_tani" class="form-label">Kelompok Tani<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="Kelompok Tani"
                                aria-label="kelompok_tani" id="kelompok_tani" name="kelompok_tani"
                                aria-describedby="basic-addon11" value="{{ $data->kelompok_tani }}" required />
                        </div>

                        <div class="mb-0">
                            <label for="indeks_ketahanan_pangan" class="form-label">Indeks Ketahanan Pangan<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="Indeks Ketahanan Pangan"
                                aria-label="indeks_ketahanan_pangan" id="indeks_ketahanan_pangan"
                                name="indeks_ketahanan_pangan" aria-describedby="basic-addon11"
                                value="{{ $data->indeks_ketahanan_pangan }}" required />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Lokasi -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100">
                    <h5 class="card-header">Table Lokasi</h5>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat<span class="text-danger">*</span></label>
                            <textarea class="form-control" type="text" id="alamat" name="alamat" rows="5" placeholder="Alamat"
                                required>{!! $data->alamat !!}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="telphone" class="form-label">Telphone<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="Telphone" aria-label="telphone"
                                id="telphone" name="telphone" aria-describedby="basic-addon11"
                                value="{{ $data->telphone }}" required />
                        </div>

                        <div class="mb-0">
                            <label for="jam_operasional" class="form-label">Jam Operasional<span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" type="text" id="jam_operasional" name="jam_operasional" rows="5"
                                placeholder="Jam Operasional" required>{!! $data->jam_operasional !!}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Sosial Media -->
            <div class="col-lg-4 col-md-12">
                <div class="card h-100">
                    <h5 class="card-header">Table Sosial Media</h5>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="youtube" class="form-label">Youtube<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon11"><i class='bx bxl-youtube'></i></span>
                                <input type="text" class="form-control" placeholder="Youtube" aria-label="Youtube"
                                    id="youtube" name="youtube" aria-describedby="basic-addon11"
                                    value="{{ $data->youtube }}" required />
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="instagram" class="form-label">Instagram<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon11"><i class='bx bxl-instagram'></i></span>
                                <input type="text" class="form-control" placeholder="Instagram"
                                    aria-label="Instagram" id="instagram" name="instagram"
                                    aria-describedby="basic-addon11" value="{{ $data->instagram }}" required />
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="tiktok" class="form-label">Tiktok<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon11"><i class='bx bxl-tiktok'></i></span>
                                <input type="text" class="form-control" placeholder="Tiktok" aria-label="Tiktok"
                                    id="tiktok" name="tiktok" aria-describedby="basic-addon11"
                                    value="{{ $data->tiktok }}" required />
                            </div>
                        </div>

                        <div class="mb-0">
                            <label for="facebook" class="form-label">Facebook<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon11"><i class='bx bxl-facebook'></i></span>
                                <input type="text" class="form-control" placeholder="Facebook" aria-label="Facebook"
                                    id="facebook" name="facebook" aria-describedby="basic-addon11"
                                    value="{{ $data->facebook }}" required />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ucapan Kepala Dinas -->
            <div class="col-12">
                <div class="card">
                    <h5 class="card-header">Ucapan Kepala Dinas</h5>
                    <div class="card-body">
                        <div class="mb-0">
                            <label for="ucapan" class="form-label">Ucapan<span class="text-danger">*</span></label>
                            <textarea class="form-control" type="text" id="ucapan" name="ucapan" rows="10"
                                placeholder="Ucapan Kepala Dinas" required>{!! $data->ucapan !!}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Button Submit -->
            <div class="col-12">
                <div class="text-center">
                    <button type="submit" class="btn btn-success">Simpan Data</button>
                </div>
            </div>
        </div>
    </form>
@endsection
