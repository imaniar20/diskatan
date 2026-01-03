@extends('admin.layouts.app')
@section('Content')
    <div class="pagetitle">
        <h1>{{ $title }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin-user.index') }}">{{ $title }}</a></li>
                <li class="breadcrumb-item active">{{ $menu }}</li>
            </ol>
        </nav>
    </div>
    <div class="section">
        <div class="row">
            <div class="col-md-12" style="border-bottom: 0px">
                <div class="card mb-4">
                    <div class="card-body demo-vertical-spacing demo-only-element">
                        <form class="mb-4" id="addUser" action="{{ route('admin-user.store') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="col-2 mb-3">
                                        <label for="bidang" class="form-label">Bidang <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" name="bidang" id="bidang">
                                            <option value="" selected disabled>- Pilih Bidang -</option>
                                            @foreach ($bidang as $item)
                                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                            @endforeach
                                            
                                        </select>
                                        <small class="text-danger" id="errBidang"></small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="name" class="form-label">Nama <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Nama" aria-label="Nama"
                                        id="name" name="name" aria-describedby="basic-addon11" />
                                    <small class="text-danger" id="errName"></small>
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="email" class="form-label">Email <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="......@google.com"
                                            aria-label="Recipient's username" aria-describedby="basic-addon13"
                                            id="email" name="email" />
                                    </div>
                                    <small class="text-danger" id="errEmail"></small>
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="username" class="form-label">Username <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon11">@</span>
                                        <input type="text" class="form-control" placeholder="Username"
                                            aria-label="Username" id="username" name="username"
                                            aria-describedby="basic-addon11" />
                                    </div>
                                    <small class="text-danger" id="errUsername"></small>
                                </div>

                                <div class="col-6 mb-3">
                                    <div class="form-password-toggle">
                                        <label for="password" class="form-label">Password <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" class="form-control"
                                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                aria-describedby="basic-default-password2" id="password" name="password" />
                                            <span id="basic-default-password2" class="input-group-text cursor-pointer"><i
                                                    class="bx bx-hide"></i></span>
                                        </div>
                                        <small class="text-danger" id="errPass"></small>
                                    </div>
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
        </div>
    </div>

    <script>
        function isEmail(email) {
            var regexEmailValidator = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            if (email.match(regexEmailValidator)) {
                return true;
            } else {
                return false;
            }
        }

        const checkUsername = async (username) => {
            const response = await fetch('/cek-username', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    username
                })
            });

            return response.json();
        };

        const handlerBidang = (e) => {
            let bidang = $('#bidang').val();

            if (!bidang) {
                $('#errBidang').html('Bidang Wajib Dipilih');
                e.preventDefault();
                return false;
            } else {
                $('#errBidang').html('');
                return true;
            }
        };

        const handlerName = (e) => {
            let title = $('#name').val().trim();

            if (!title) {
                $('#errName').html('Nama Tidak Boleh Kosong');
                e.preventDefault();
                return false;
            } else if (title.length < 3) {
                $('#errName').html('Setidaknya Nama Minimal 3 Huruf');
                e.preventDefault();
                return false;
            } else {
                $('#errName').html('');
                return true;
            }
        };

        const handlerEmail = (e) => {
            let email = $('#email').val().trim();

            if (!email) {
                $("#errEmail").html("E-Mail tidak boleh kosong");
                e.preventDefault();
            } else {
                if (isEmail(email)) {
                    $("#errEmail").html("");
                } else {
                    $("#errEmail").html("Format E-Mail tidak valid");
                    e.preventDefault();
                }
            }
        };

        const handlerUsername = async (e) => {
            let title = $('#username').val().trim();
            const result = await checkUsername(title);

            if (!title) {
                $('#errUsername').html('Username Tidak Boleh Kosong');
                e.preventDefault();
                return false;
            } else if (title.length < 3) {
                $('#errUsername').html('Setidaknya Username Minimal 3 Huruf');
                e.preventDefault();
                return false;
            } else if (result.exists) {
                $('#errUsername').html('Username sudah digunakan');
                e.preventDefault();
                return false;
            } else {
                $('#errUsername').html('');
                return true;
            }
        };

        const handlerPass = e => {
            let password = $('#password').val();

            if (!password) {
                $("#errPass").html("Password tidak boleh kosong");
                e.preventDefault();
            } else if (password.length < 8) {
                $('#errPass').html("Password Terdiri dari 8 Kata atau Huruf");
                e.preventDefault();
            } else {
                $('#errPass').html("");
            }
        }

        $('#addUser').on('submit', async function(e) {
            handlerBidang(e) 
            handlerName(e) 
            handlerEmail(e)
            await handlerUsername(e)
            handlerPass(e)
        });
    </script>
@endsection
