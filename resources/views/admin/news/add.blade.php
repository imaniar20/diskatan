@extends('admin.layouts.app')
@section('Content')
    <div class="pagetitle">
        <h1>{{ $title }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin-berita.index') }}">{{ $title }}</a></li>
                <li class="breadcrumb-item active">{{ $menu }}</li>
            </ol>
        </nav>
    </div>
    <div class="section">
        <form class="mb-4" id="addBerita" action="{{ route('admin-berita.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-12" style="border-bottom: 0px">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="col-2 mb-3">
                                        <label for="bidang" class="form-label">Bidang <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" name="bidang" id="bidang">
                                            <option value="" selected disabled>- Pilih Bidang -</option>
                                            <option value="Ketahanan Pangan">Ketahanan Pangan</option>
                                            <option value="Tanaman Pangan">Tanaman Pangan</option>
                                            <option value="Hortikultura">Hortikultura</option>
                                            <option value="Peternakan">Peternakan</option>
                                            <option value="Penyuluhan">Penyuluhan</option>
                                        </select>
                                        <small class="text-danger" id="errBidang"></small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label for="title" class="form-label">Judul <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="title" name="title"
                                        placeholder="" />
                                    <small class="text-danger" id="errTitle"></small>
                                </div>
                                <div class="col-6">
                                    <label for="thumbnail" class="form-label">Thumbnail <span
                                            class="text-danger">*</span></label>
                                    <input type="file" class="form-control dropify" name="thumbnail" id="thumbnail" accept="image/*">
                                    <small class="text-danger" id="errThumbnail"></small>
                                    <small>Batas 2mb. Jenis yang diizinkan : .png, .jpg, .jpeg</small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <label for="status" class="form-label">Status <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="published">Published</option>
                                        <option value="draft">Draf</option>
                                    </select>
                                    <small class="text-danger" id="errStatus"></small>
                                </div>
                                <div class="col-6">
                                    <label for="published_at" class="form-label">Tanggal Publikasi <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" type="datetime-local" id="published_at"
                                        name="published_at" />
                                    <small class="text-danger" id="errPublished"></small>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="newsContent" class="form-label">Konten Berita</label>
                                <textarea class="form-control tinymce" type="text" id="newsContent" name="newsContent" placeholder="Konten Berita"></textarea>
                                <small class="text-danger" id="errContent"></small>
                            </div>

                            <div class="mb-3">
                                <div class="justify-content-center">
                                    <button type="submit" class="btn btn-success">Tambah</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <script>
        const handlerTitle = (e) => {
            let title = $('#title').val().trim();

            if (!title) {
                $('#errTitle').html('Judul Berita Tidak Boleh Kosong');
                e.preventDefault();
                return false;
            } else if (title.length < 3) {
                $('#errTitle').html('Setidaknya Judul Berita Minimal 3 Huruf');
                e.preventDefault();
                return false;
            } else {
                $('#errTitle').html('');
                return true;
            }
        };

        const handlerThumbnail = (e) => {
            let input = document.getElementById('thumbnail');
            let file = input.files[0];

            // wajib
            if (!file) {
                $('#errThumbnail').html('Thumbnail Wajib Diisi');
                e.preventDefault();
                return false;
            }

            // ukuran maksimal 2MB
            const maxSize = 2 * 1024 * 1024; // 2MB
            if (file.size > maxSize) {
                $('#errThumbnail').html('Ukuran thumbnail maksimal 2MB');
                input.value = '';
                e.preventDefault();
                return false;
            }

            // tipe file
            const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
            if (!allowedTypes.includes(file.type)) {
                $('#errThumbnail').html('Thumbnail harus berformat PNG, JPG, atau JPEG');
                input.value = '';
                e.preventDefault();
                return false;
            }

            // valid
            $('#errThumbnail').html('');
            return true;
        };

        const handlerStatus = (e) => {
            let status = $('#status').val();

            if (!status) {
                $('#errStatus').html('Status Wajib Dipilih');
                e.preventDefault();
                return false;
            } else {
                $('#errStatus').html('');
                return true;
            }
        };

        const handlerPublished = (e) => {
            let published = $('#published_at').val();

            if (!published) {
                $('#errPublished').html('Tanggal Publikasi Wajib Diisi');
                e.preventDefault();
                return false;
            } else {
                $('#errPublished').html('');
                return true;
            }
        };

        const handlerContent = (e) => {
            let content = tinymce.get('newsContent').getContent({
                format: 'text'
            }).trim();

            if (!content) {
                $('#errContent').html('Konten Berita Tidak Boleh Kosong');
                e.preventDefault();
                return false;
            } else {
                $('#errContent').html('');
                return true;
            }
        };

        $('#addBerita').on('submit', function(e) {

            let validTitle = handlerTitle(e);
            let validThumb = handlerThumbnail(e);
            let validStatus = handlerStatus(e);
            let validPublished = handlerPublished(e);
            let validContent = handlerContent(e);

            if (
                !validTitle ||
                !validThumb ||
                !validStatus ||
                !validPublished ||
                !validContent
            ) {
                return false;
            }
        });
    </script>
@endsection
