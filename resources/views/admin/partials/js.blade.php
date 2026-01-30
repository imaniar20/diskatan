    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
    <!-- endbuild -->

    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Dropify JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>

    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>

    <!-- JS Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="{{ asset('tinymce/tinymce.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>

@push('after-script')

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const selects = document.querySelectorAll('.select2');
            selects.forEach(select => $(select).select2());
        });

        $(document).ready(function() {
            $('.datatable').each(function() {
                if (!$.fn.DataTable.isDataTable(this)) {
                    new DataTable(this, {
                        // ordering: false
                        // pageLength: 300
                    });
                }
            });
        });

        $(document).ready(function() {
            $('.dropify').dropify();
        });

        function confirmDelete(event) {
            event.preventDefault(); // Mencegah form mengirimkan data sebelum konfirmasi

            // Menampilkan SweetAlert konfirmasi
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dipulihkan!",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonColor: "#3085d6",
                confirmButtonColor: "#d33",
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika konfirmasi diterima, kirimkan form untuk menghapus
                    event.target.closest('form').submit();
                }
            });
        }
        document.addEventListener('DOMContentLoaded', function () {
            tinymce.init({
                selector: '.tinymce',
                license_key: 'gpl',
                base_url: '/tinymce',
                suffix: '.min',

                height: 450,
                plugins: 'image link lists table code',
                toolbar: `
                    undo redo |
                    bold italic |
                    alignleft aligncenter alignright |
                    bullist numlist |
                    image link table |
                    code
                `,

                images_upload_url: "{{ route('admin.upload-image') }}",
                images_upload_credentials: true,

                // Use images_upload_handler for more control
                images_upload_handler: function(blobInfo, progress) {
                    return new Promise(function(resolve, reject) {
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', "{{ route('admin.upload-image') }}", true);
                        xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

                        xhr.upload.onprogress = function(e) {
                            progress(e.loaded / e.total * 100);
                        };

                        xhr.onload = function() {
                            if (xhr.status === 403) {
                                reject('HTTP Error: ' + xhr.status, {
                                    remove: true
                                });
                                return;
                            }

                            if (xhr.status < 200 || xhr.status >= 300) {
                                reject('HTTP Error: ' + xhr.status);
                                return;
                            }

                            var json = JSON.parse(xhr.responseText);

                            if (!json || typeof json.location != 'string') {
                                reject('Invalid JSON: ' + xhr.responseText);
                                return;
                            }

                            resolve(json.location);
                        };

                        xhr.onerror = function() {
                            reject('Image upload failed due to a XHR Transport error. Code: ' + xhr
                                .status);
                        };

                        var formData = new FormData();
                        formData.append('file', blobInfo.blob(), blobInfo.filename());
                        formData.append('_token', '{{ csrf_token() }}'); // Add token to form data

                        xhr.send(formData);
                    });
                }
            });
        });
    </script>

    </script>

    @if (session('success'))
        <script>
            Swal.fire({
                title: 'Success!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                title: 'Error!',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        </script>
    @endif
@endpush