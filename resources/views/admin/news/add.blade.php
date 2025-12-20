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
        <form class="mb-4" id="formPeraturan" action="{{ route('admin-berita.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-12" style="border-bottom: 0px">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-6">
                                    <label for="name" class="form-label">Judul <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="" />
                                    <small class="text-danger error-message" id="error-name"></small>
                                </div>
                                <div class="col-6">
                                    <label for="thumbnail" class="form-label">Thumbnail <span
                                            class="text-danger">*</span></label>
                                    <input type="file" class="form-control dropify" name="thumbnail" id="thumbnail">
                                    <small class="text-danger error-message" id="error-thumbnail"></small>
                                    <small>Batas 10mb. Jenis yang diizinkan : .png, .jpg, .jpeg</small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <label for="status" class="form-label">Status <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="aktif">Aktif</option>
                                        <option value="non_aktif">Tidak Aktif</option>
                                    </select>
                                    <small class="text-danger error-message" id="error-status"></small>
                                </div>
                                <div class="col-6">
                                    <label for="publication_date" class="form-label">Tanggal Publikasi <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" type="datetime-local" id="publication_date"
                                        name="publication_date" />
                                    <small class="text-danger error-message" id="error-publication_date"></small>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="content" class="form-label">Konten Berita</label>
                                <textarea class="form-control tinymce" type="text" id="content" name="content" placeholder="Konten Berita"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
@endsection
