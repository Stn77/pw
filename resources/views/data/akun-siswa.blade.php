<x-layouts.app title="Akun Siswa" pageTitleName="Akun Siswa" sidebarShow=true>
    @push('style')
    <style>


    </style>
    @endpush
    <div class="container-fluid py-3">
        <div class="d-flex flex-column">
            <div class="d-flex w-100 mb-3">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addmodal">
                    <i class="bi bi-plus"></i> Tambah Data
                </button>
            </div>

            <table class="table mt-2 table-striped table-bordered" id="akun-siswa">
                <thead class="text-light table-dark mt-2">
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Jurusan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data akan diisi oleh DataTables -->
                </tbody>
            </table>
        </div>

        {{-- modal --}}
        <div class="modal fade" id="addmodal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Data Siswa Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="siswaForm" novalidate>
                        <div class="modal-body">
                            <div class="form-group mb-3 col">
                                <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Siswa" required>
                                <div class="invalid-feedback">Nama harus diisi</div>
                            </div>
                            <div class="form-group mb-3 col">
                                <label for="nisn" class="form-label">NISN <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nisn" name="nisn" placeholder="NISN Siswa" required>
                                <div class="invalid-feedback">NISN harus diisi</div>
                            </div>
                            <div class="form-group mb-3 col">
                                <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Username Siswa" required>
                                <div class="invalid-feedback">Username harus diisi</div>
                            </div>
                            <div class="d-flex flex-column flex-md-row">
                                <div class="form-group mb-3 col me-md-2">
                                    <label for="kelas" class="form-label">Kelas <span class="text-danger">*</span></label>
                                    <select class="form-select" name="kelas" id="kelas" required>
                                        <option value="" selected disabled>Pilih Kelas</option>
                                        <option value="1">X</option>
                                        <option value="2">XI</option>
                                        <option value="3">XII</option>
                                    </select>
                                    <div class="invalid-feedback">Kelas harus dipilih</div>
                                </div>
                                <div class="form-group mb-3 col ms-md-2">
                                    <label for="jurusan" class="form-label">Jurusan <span class="text-danger">*</span></label>
                                    <select class="form-select" name="jurusan" id="jurusan" required>
                                        <option value="" selected disabled>Pilih Jurusan</option>
                                        <option value="1">MP</option>
                                        <option value="2">AK</option>
                                        <option value="3">BD</option>
                                        <option value="4">TSM</option>
                                        <option value="5">DKV</option>
                                        <option value="6">PPLG</option>
                                        <option value="7">TKKR</option>
                                    </select>
                                    <div class="invalid-feedback">Jurusan harus dipilih</div>
                                </div>
                            </div>
                            <div class="form-group mb-3 col">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password Siswa" required>
                                <div class="invalid-feedback">Password harus diisi</div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Tambahkan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('script')
    <script>
        $(document).ready(() => {
            // Inisialisasi DataTables
            let akunSiswaTable = $('#akun-siswa').DataTable({
                processing: true,
                pageLength: 50,
                serverSide: true,
                autoFill: false,
                ajax: {
                    url: '{{route('akun.siswa.get')}}',
                },
                columns: [
                    {
                        data: null,
                        title: 'No',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {data: 'username', title: 'Username'},
                    {data: 'siswa.name', title: 'Nama Siswa'},
                    {data: 'siswa.kelas.name', title: 'Kelas'},
                    {data: 'siswa.jurusan.name', title: 'Jurusan'},
                    {
                        data: 'action',
                        title: 'Aksi',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-warning edit-btn" data-id="${row.id}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            `;
                        }
                    }
                ]
            });

            // Reset form saat modal ditutup
            $('#addmodal').on('hidden.bs.modal', function () {
                $('#siswaForm')[0].reset();
                $('#siswaForm').removeClass('was-validated');
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').hide();
            });

            // Handle submit form
            $('#siswaForm').on('submit', function(e) {
                e.preventDefault();

                // Validasi form
                if (this.checkValidity() === false) {
                    e.stopPropagation();
                    $(this).addClass('was-validated');
                    return;
                }

                // Mengumpulkan data dari form
                const formData = {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    nama: $('#nama').val(),
                    username: $('#username').val(),
                    kelas: $('#kelas').val(),
                    jurusan: $('#jurusan').val(),
                    password: $('#password').val(),
                    nisn: $('#nisn').val(),
                };

                // Kirim data via AJAX
                $.ajax({
                    url: '{{route('akun.siswa.store')}}',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.status === 200) {
                            // Tutup modal

                            // Tampilkan pesan sukses (bisa menggunakan toast/alert)
                            alert('Data berhasil ditambahkan!');

                            $('#addmodal').modal('hide');
                            // Refresh tabel
                            akunSiswaTable.ajax.reload();
                        } else {
                            alert('Terjadi kesalahan: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            // Validasi server gagal
                            const errors = xhr.responseJSON.errors;
                            let errorMessage = '';

                            for (const field in errors) {
                                errorMessage += errors[field][0] + '\n';
                            }

                            alert('Validasi gagal:\n' + errorMessage);
                        } else {
                            alert('Terjadi kesalahan server. Silakan coba lagi.');
                        }
                    }
                });
            });
        });
    </script>
    @endpush
</x-layouts.app>
