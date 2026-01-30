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
        <form id="editBerita" action="{{ route('admin-berita.update', $news->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-12" style="border-bottom: 0px">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="col-2 mb-3">
                                        <label for="program" class="form-label">Program
                                            {{-- <span class="text-danger">*</span> --}}
                                        </label>
                                        <select class="form-control" name="program" id="program">
                                            <option value="" selected disabled>- Pilih Program -</option>
                                            @foreach ($program as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $news->program_id == $item->id ? 'selected' : '' }}>
                                                    {{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        <small class="text-danger" id="errProgram"></small>
                                    </div>
                                </div>
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
                                    <label for="status" class="form-label">Status <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="published" {{ $news->status == 'published' ? 'selected' : '' }}>
                                            Published</option>
                                        <option value="draft" {{ $news->status == 'draft' ? 'selected' : '' }}>Draf
                                        </option>
                                    </select>
                                    <small class="text-danger" id="errStatus"></small>
                                </div>
                                <div class="col-6">
                                    <label for="published_at" class="form-label">Tanggal Publikasi <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" type="datetime-local" id="published_at"
                                        value="{{ $news->published_at }}" name="published_at" />
                                    <small class="text-danger" id="errPublished"></small>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="newsContent" class="form-label">Konten Berita</label>
                                <textarea class="form-control tinymce" type="text" id="newsContent" name="newsContent" placeholder="Konten Berita">{!! $news->content !!}</textarea>
                                <small>Batas 2mb. Jenis yang diizinkan : .png, .jpg, .jpeg</small>
                                <small class="text-danger" id="errContent"></small>
                            </div>

                            <div class="row">
                                @foreach ($news->contents as $i => $konten)
                                    <div class="col-3 mb-3">
                                        <label class="form-label">Foto Konten {{ $i + 1 }}</label>

                                        <input type="file" class="dropify"
                                            data-default-file="{{ asset('storage/' . $konten->file) }}" disabled>

                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" name="hapus_konten[]"
                                                value="{{ $konten->id }}">
                                            <label class="form-check-label">Hapus</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div id="konten" class="row">
                                <div class="col-3 mb-3 konten-item">
                                    <label class="form-label">Foto Konten Baru</label>

                                    <input type="file" class="form-control dropify" name="foto_konten[]"
                                        accept="image/*">

                                    <button type="button" class="btn btn-sm btn-danger mt-2 btn-hapus d-none">
                                        Hapus
                                    </button>
                                </div>
                            </div>

                            <button type="button" id="tambahKonten" class="btn btn-warning">
                                <i class='bx bx-plus-circle'></i> Tambah Konten
                            </button>
                            <hr>

                            <div class="row mb-3">
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
        let index = 1;

        $('.dropify').dropify();

        $('#tambahKonten').on('click', function() {
            index++;

            let item = $('.konten-item:first').clone();

            // Destroy dropify pada clone
            // item.find('.dropify').dropify('destroy');

            // Hapus wrapper dropify yang ikut ke-clone
            item.find('.dropify-wrapper').replaceWith(function() {
                return $(this).find('input');
            });

            // Reset value
            item.find('input').val('');

            // Update label
            item.find('label').html(`Foto Konten ${index} <span class="text-danger">*</span>`);

            // Tampilkan tombol hapus
            item.find('.btn-hapus').removeClass('d-none');

            // Append ke container
            $('#konten').append(item);

            // HANYA init dropify pada item yang baru ditambahkan
            item.find('.dropify').dropify();
        });

        $('#konten').on('click', '.btn-hapus', function() {
            let itemToRemove = $(this).closest('.konten-item');

            itemToRemove.find('.dropify').dropify('destroy');

            itemToRemove.remove();

            index--;
        });
        
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

        $('#editBerita').on('submit', function(e) {

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
