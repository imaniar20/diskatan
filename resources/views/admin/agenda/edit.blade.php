@extends('admin.layouts.app')
@section('Content')
    <div class="pagetitle">
        <h1>{{ $title }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin-agenda.index') }}">{{ $title }}</a></li>
                <li class="breadcrumb-item active">{{ $menu }}</li>
            </ol>
        </nav>
    </div>
    <div class="section">
        <form action="{{ route('admin-agenda.update', $news->id) }}" method="POST" id="addAgenda" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-12" style="border-bottom: 0px">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-6">
                                    <label for="title" class="form-label">Judul <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="title" name="title"
                                        value="{{ $news->title }}" placeholder="" />
                                    <small class="text-danger" id="errTitle"></small>
                                </div>
                                <div class="col-6">
                                    <div class="row mb-1">
                                        <div class="col-md-6">
                                            <label for="legalProductFile" class="form-label">Thumbnail <span
                                                    class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            @if ($fileExists)
                                                <a href="{{ route('download.file', ['filename' => $news->thumbnail]) }}"
                                                    class="btn btn-sm btn-success">
                                                    <i class='bx bx-cloud-download'></i> Download Image
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    <input type="file" class="form-control dropify" name="thumbnail" id="thumbnail"
                                        data-default-file="{{ asset('/storage/' . $news->thumbnail) }}" accept="image/*">
                                    <small class="text-danger" id="errThumbnail"></small>
                                    <small>Batas 2mb. Jenis yang diizinkan : .png, .jpg, .jpeg</small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <label for="location" class="form-label">Lokasi <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="location" name="location" class="form-control" value="{{ $news->location }}">
                                    <small class="text-danger" id="errLocation"></small>
                                </div>
                                <div class="col-6">
                                    <label for="published_at" class="form-label">Waktu Kegiatan <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" type="datetime-local" id="published_at"
                                        value="{{ $news->date }}" name="published_at" />
                                    <small class="text-danger" id="errPublished"></small>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="agendaContent" class="form-label">Konten Agenda</label>
                                <textarea class="form-control tinymce" type="text" id="agendaContent" name="agendaContent" placeholder="Konten Berita">{!! $news->content !!}</textarea>
                                <small>Batas 2mb. Jenis yang diizinkan : .png, .jpg, .jpeg</small>
                                <small class="text-danger" id="errContent"></small>
                            </div>

                            <div class="mb-3">
                                <div class="justify-content-center">
                                    <button type="submit" class="btn btn-warning">Ubah</button>
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

            // Cek apakah ini mode edit (ada thumbnail lama)
            const isEditMode = document.getElementById('thumbnail').getAttribute('data-has-existing') === 'true';

            // Jika ada file baru, validasi ukuran dan tipe
            if (file) {
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
            }

            // valid
            $('#errThumbnail').html('');
            return true;
        };

        const handlerLocation = (e) => {
            let title = $('#location').val().trim();

            if (!title) {
                $('#errLocation').html('Lokasi Tidak Boleh Kosong');
                e.preventDefault();
                return false;
            } else if (title.length < 3) {
                $('#errLocation').html('Setidaknya Lokasi Minimal 3 Huruf');
                e.preventDefault();
                return false;
            } else {
                $('#errLocation').html('');
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
            let content = tinymce.get('agendaContent').getContent({
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

        $('#addAgenda').on('submit', function(e) {

            let validTitle = handlerTitle(e);
            let validThumb = handlerThumbnail(e);
            let validLocation = handlerLocation(e);
            let validPublished = handlerPublished(e);
            let validContent = handlerContent(e);

            if (
                !validTitle ||
                !validThumb ||
                !validLocation ||
                !validPublished ||
                !validContent
            ) {
                return false;
            }
        });
    </script>
@endsection
