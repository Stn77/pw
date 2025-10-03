<x-layouts.app title="Profile" pageTitleName="Profile" sidebarShow=true>
    @push('style')
    <style>
        .profile-pic{
            width: 200px;;
        }
        .info{
            width: 75%;
        }
        @media (max-width: 1220px){
            .m-c{
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }
            .profile-pic{
                width: 200px;
            }
            .info {
                width: 100%;
            }
        }
        .preview-image {
            max-width: 200px;
            max-height: 200px;
            display: none;
            margin-top: 10px;
        }
        .current-image {
            max-width: 200px;
            max-height: 200px;
            border-radius: 50%;
        }
        .loading {
            display: none;
            color: #007bff;
        }
        .alert {
            display: none;
            margin-top: 10px;
        }

        .class-c{
            display: flex;
            flex-wrap: wrap;
            justify-content: left;
            align-items: center;
        }
    </style>
    @endpush
    <div class="w-100">
        <a href="{{route('data.guru.index')}}" class="btn btn-success"><i class="bi bi-arrow-left mx-2"></i> Kembali</a>
    </div>
    <form id="profileForm" action="{{route('data.guru.update')}}" method="POST" enctype="multipart/form-data">
        @csrf
        {{-- @method('PUT') --}}

        <div class="d-flex m-c">
            <div class=" mx-auto">
                <div class="mx-auto overflow-hidden rounded-circle ratio ratio-1x1 p-2 d-flex justify-content-center mt-5 profile-pic">
                    @if ($guru->user->foto_profile)
                        <img id="currentProfileImage" class="current-image" alt="Foto Profile" src="{{ asset('storage/profile-images/' . $guru->user->foto_profile) }}">
                    @else
                        <img id="currentProfileImage" class="current-image" alt="Foto Profile" src="{{ asset('img/default-profile.png') }}">
                    @endif
                    <img id="imagePreview" class="preview-image rounded-circle" src="#" alt="Preview Gambar">
                    {{-- <img src="{{ asset('storage/profile-images/' . $guru->user->foto_profile) }}" alt="Foto Profil" width="150"> --}}

                </div>
            </div>
            <div class="info">
                <div class="p-2">
                    <div class="form border shadow-sm rounded-2 p-2">
                        <!-- Alert Messages -->
                        <div id="successAlert" class="alert alert-success" role="alert"></div>
                        <div id="errorAlert" class="alert alert-danger" role="alert"></div>
                        <input type="text" hidden id="idGuru" name="idGuru" value="{{$id}}">

                        <div class="form-group mb-3 col">
                            <label for="image" class="form-label">Pilih Gambar Untuk Foto Profile</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <div class="form-text">Format yang didukung: JPG, PNG, GIF, SVG. Maksimal 2MB Rasio 1:1</div>
                            <div id="imageError" class="text-danger"></div>
                        </div>

                        <div class="row">
                            <div class="form-group mb-3 col">
                                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" class="form-control" value="{{ $guru->name }}" readonly>
                            </div>

                            <div class="form-group mb-3 col">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" value="{{ $guru->user->username ?? '' }}" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group mb-3 col">
                                <label for="nip" class="form-label">NIP</label>
                                <input type="text" name="nip" class="form-control" value="{{ $guru->nip ?? ''}}" readonly>
                            </div>

                            <div class="form-group mb-3 col">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ $guru->user->email ?? ''}}" readonly>
                            </div>
                        </div>

                        <div class="row mb-3 p-2" id="kelasContainer-m">
                            <div class="form-group col">
                                <label for="" class="form-label">Kelas Diajar</label>
                                <div class="form-group border m-2 p-2 col rounded class-c" id="classContainer-current">
                                    <!-- Tombol kelas akan ditampilkan di sini -->
                                </div>
                            </div>

                            <div class="form-group col">
                                <label for="" class="form-label">Dihapus</label>
                                <div class="form-group border m-2 p-2 col rounded class-c" id="classContainer-delete">
                                    <!-- Tombol kelas yang dihapus akan dipindahkan ke sini -->
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3 p-2 border rounded">
                            <label for="" class="form-label">Tambah Kelas Diajar Baru</label>
                            <div class="form-group col-md-5">
                                <select id="kelas_select" class="form-control" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    <option value="1">X</option>
                                    <option value="2">XI</option>
                                    <option value="3">XII</option>
                                </select>
                            </div>
                            <div class="form-group col-md-5">
                                <select id="jurusan_select" class="form-control" required>
                                    <option value="">-- Pilih Jurusan --</option>
                                    <option value="" selected disabled>Pilih Jurusan</option>
                                    <option value="1">MP</option>
                                    <option value="2">AK</option>
                                    <option value="3">BD</option>
                                    <option value="4">TSM</option>
                                    <option value="5">DKV</option>
                                    <option value="6">PPLG</option>
                                    <option value="7">TKKR</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <button type="button" class="btn btn-success w-100" id="addClassBtn">Tambah</button>
                            </div>
                        </div>

                        <div class="row mb-3 p-2" id="newClassesContainer">
                            <div class="form-group col">
                                <label for="" class="form-label">Kelas Baru (Belum Tersimpan)</label>
                                <div class="form-group border m-2 p-2 col rounded class-c" id="classContainer-new">
                                    </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <button type="button" class="btn btn-primary" id="submitKelas">Simpan Perubahan Kelas</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @push('script')
    <script>
        $(document).ready(function() {
            // Image Preview
            $('#image').change(function() {
                const file = this.files[0];
                const preview = $('#imagePreview');
                const currentImage = $('#currentProfileImage');
                const errorDiv = $('#imageError');

                // Reset error message
                errorDiv.text('');
                $('#errorAlert').hide();

                if (file) {
                    // Validate file type
                    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml'];
                    if (!allowedTypes.includes(file.type)) {
                        errorDiv.text('Format file tidak didukung. Harap pilih file JPG, PNG, GIF, atau SVG.');
                        this.value = '';
                        return;
                    }

                    // Validate file size (2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        errorDiv.text('Ukuran file terlalu besar. Maksimal 2MB.');
                        this.value = '';
                        return;
                    }

                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const img = new Image();

                        img.onload = function() {
                            // Validasi rasio gambar 1:1
                            const width = this.width;
                            const height = this.height;
                            const ratio = width / height;

                            // Toleransi kecil untuk perbandingan floating point
                            if (Math.abs(ratio - 1) > 0.01) {
                                errorDiv.text('Rasio gambar harus 1:1 (persegi). Harap pilih gambar dengan lebar dan tinggi yang sama.');
                                $('#image').val('');
                                preview.hide();
                                currentImage.show();
                                return;
                            }

                            // Jika validasi berhasil, tampilkan preview
                            currentImage.hide();
                            preview.attr('src', e.target.result);
                            preview.show();
                        }

                        img.onerror = function() {
                            errorDiv.text('Gagal memuat gambar. Harap coba lagi.');
                            $('#image').val('');
                        }

                        img.src = e.target.result;
                    }

                    reader.onerror = function() {
                        errorDiv.text('Gagal membaca file. Harap coba lagi.');
                        this.value = '';
                    }

                    reader.readAsDataURL(file);
                } else {
                    preview.hide();
                    currentImage.show();
                }
            });

            // Reset button functionality
            $('#resetBtn').click(function(e) {
                e.preventDefault();

                // Reset form
                $('#profileForm')[0].reset();

                // Reset image preview
                $('#imagePreview').hide();
                $('#currentProfileImage').show();

                // Clear error messages
                $('#imageError').text('');
                $('#errorAlert').hide();
                $('#successAlert').hide();
            });

            // AJAX Form Submission
            $('#profileForm').submit(function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const submitBtn = $('#submitBtn');
                const submitText = $('#submitText');
                const loadingSpinner = $('#loadingSpinner');
                const successAlert = $('#successAlert');
                const errorAlert = $('#errorAlert');

                // Show loading state
                submitBtn.prop('disabled', true);
                submitText.hide();
                loadingSpinner.show();

                // Hide previous alerts
                successAlert.hide();
                errorAlert.hide();

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            // Show success message
                            successAlert.text(response.message || 'Profile berhasil diperbarui!');
                            successAlert.show();

                            // Update profile image if new image was uploaded
                            if (response.image_url) {
                                $('#currentProfileImage').attr('src', response.image_url);
                            }

                            // Reset form and preview
                            $('#profileForm')[0].reset();
                            $('#imagePreview').hide();
                            $('#currentProfileImage').attr('src', response.url);
                            $('#currentProfileImage').show();

                            // Scroll to top to show success message
                            $('html, body').animate({ scrollTop: 0 }, 500);
                        } else {
                            errorAlert.text(response.message || 'Terjadi kesalahan!');
                            errorAlert.show();
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan saat mengupload!';
                        console.log(xhr)

                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            // Handle validation errors
                            const errors = xhr.responseJSON.errors;
                            if (errors.image) {
                                errorMessage = errors.image[0];
                            } else if (xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                        }

                        errorAlert.text(errorMessage);
                        errorAlert.show();
                    },
                    complete: function() {
                        // Reset loading state
                        submitBtn.prop('disabled', false);
                        submitText.show();
                        loadingSpinner.hide();
                    }
                });
            });
        });
    </script>
    <script>
        // Variabel untuk menyimpan data kelas
        let kelasData = {
            current: [], // Menyimpan objek kelas {id, nama} (ID GuruPivot)
            deleted: [], // Menyimpan objek kelas {id, nama} (ID GuruPivot)
            new: []      // Menyimpan objek kelas baru {kelas_id, jurusan_id, nama_tampilan}
        };

        var getKelasUrl = "{{ route('data.guru.class', ['id' => ':id']) }}"

        // Memuat data kelas dari server
        $(document).ready(function() {
            loadKelasData();
        });

        function loadKelasData() {
            $.ajax({
                url: getKelasUrl.replace(':id', $('#idGuru').val()),
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response){
                    if(response.success){
                        // Gabungkan data nama dan id menjadi array of objects
                        let kelasWithIds = [];
                        for(let i = 0; i < response.data.length; i++) {
                            kelasWithIds.push({
                                id: response.id[i],
                                nama: response.data[i]
                            });
                        }

                        kelasData.current = kelasWithIds;
                        renderKelasButtons();
                    }
                },
                error: function(xhr){
                    console.log(xhr);
                    alert('Terjadi kesalahan saat memuat data kelas');
                }
            });
        }

        // Render tombol kelas berdasarkan data
        function renderKelasButtons() {
            // Kosongkan container
            $('#classContainer-current').empty();
            $('#classContainer-delete').empty();

            // Render kelas aktif
            kelasData.current.forEach((kelas, index) => {
                $('#classContainer-current').append(
                    `<button class="btn btn-sm btn-danger m-1 btn-kelas" type="button"
                        data-id="${kelas.id}" data-index="${index}" onclick="removeClass(${index})">
                        ${kelas.nama} <i class="bi bi-x"></i>
                    </button>`
                );
            });

            // Render kelas yang dihapus
            kelasData.deleted.forEach((kelas, index) => {
                $('#classContainer-delete').append(
                    `<button class="btn btn-sm btn-secondary m-1 btn-kelas" type="button"
                        data-id="${kelas.id}" data-index="${index}" onclick="restoreClass(${index})">
                        ${kelas.nama} <i class="bi bi-arrow-counterclockwise"></i>
                    </button>`
                );
            });
        }

        // Fungsi untuk memindahkan kelas ke container "Dihapus"
        function removeClass(index) {
            // Pindahkan dari current ke deleted
            const movedClass = kelasData.current.splice(index, 1)[0];
            kelasData.deleted.push(movedClass);

            // Render ulang tombol
            renderKelasButtons();
        }

        // Fungsi untuk mengembalikan kelas ke container "Kelas Diajar"
        function restoreClass(index) {
            // Pindahkan dari deleted ke current
            const movedClass = kelasData.deleted.splice(index, 1)[0];
            kelasData.current.push(movedClass);

            // Render ulang tombol
            renderKelasButtons();
        }

        // Render tombol kelas baru berdasarkan data
        function renderNewClassButtons() {
            $('#classContainer-new').empty();

            // Render kelas baru
            kelasData.new.forEach((kelas, index) => {
                $('#classContainer-new').append(
                    `<button class="btn btn-sm btn-info m-1 btn-kelas" type="button"
                        data-kelas-id="${kelas.kelas_id}" data-jurusan-id="${kelas.jurusan_id}"
                        data-index="${index}" onclick="removeNewClass(${index})">
                        ${kelas.nama} <i class="bi bi-x"></i>
                    </button>`
                );
            });
            // Gabungkan render kelas aktif, deleted, dan new agar lebih bersih:
            renderKelasButtons();
        }

        // Fungsi untuk menghapus kelas baru dari list sebelum disimpan
        function removeNewClass(index) {
            kelasData.new.splice(index, 1);
            renderNewClassButtons();
        }

        // Mengirim data ke server (HANYA ID)
        $('#submitKelas').click(function() {
            $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');

            // Siapkan data yang akan dikirim
            const dataToSend = {
                current_class_ids: kelasData.current.map(kelas => kelas.id), // ID Pivot untuk di-restore
                deleted_class_ids: kelasData.deleted.map(kelas => kelas.id), // ID Pivot untuk di-soft delete
                // Data kelas baru yang belum punya ID Pivot, hanya (kelas_id, jurusan_id)
                new_classes: kelasData.new.map(kelas => ({
                    kelas_id: kelas.kelas_id,
                    jurusan_id: kelas.jurusan_id
                })),
                idGuru: $('#idGuru').val(),
            };

            console.log('Data yang dikirim:', dataToSend); // Untuk debugging

            $.ajax({
                url: '{{route('data.guru.setClass')}}',
                method: 'POST',
                data: dataToSend,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response){
                    if(response.success){
                        alert('Data kelas berhasil diperbarui');
                        // Refresh data jika perlu
                        loadKelasData();
                    } else {
                        alert('Gagal memperbarui data kelas: ' + response.message);
                    }
                },
                error: function(xhr){
                    console.log(xhr);
                    alert('Terjadi kesalahan saat mengirim data');
                },
                complete: function() {
                    // Kembalikan state tombol
                    $('#submitKelas').prop('disabled', false).html('Simpan Perubahan Kelas');
                }
            });
        });

        // Event Listener untuk tombol Tambah Kelas Baru
        $('#addClassBtn').click(function() {
            const kelasId = $('#kelas_select').val();
            const kelasNama = $('#kelas_select option:selected').text();
            const jurusanId = $('#jurusan_select').val();
            const jurusanNama = $('#jurusan_select option:selected').text();

            if (kelasId && jurusanId) {
                const newClass = {
                    kelas_id: kelasId,
                    jurusan_id: jurusanId,
                    nama: kelasNama.toUpperCase() + ' ' + jurusanNama.toUpperCase()
                };

                // Cek duplikasi di current (sudah ada)
                const isCurrentDuplicate = kelasData.current.some(c => c.nama === newClass.nama);
                // Cek duplikasi di new (kelas baru yang belum tersimpan)
                const isNewDuplicate = kelasData.new.some(c => c.nama === newClass.nama);

                if (isCurrentDuplicate || isNewDuplicate) {
                    alert('Kelas ' + newClass.nama + ' sudah ada di daftar.');
                    return;
                }

                // Cek duplikasi di deleted (jika ada, restore saja daripada buat baru)
                const deletedIndex = kelasData.deleted.findIndex(d => d.nama === newClass.nama);
                if (deletedIndex !== -1) {
                    restoreClass(deletedIndex); // Panggil fungsi restore yang sudah ada
                    alert('Kelas ' + newClass.nama + ' sudah terdaftar sebelumnya. Berhasil dikembalikan.');
                } else {
                    kelasData.new.push(newClass);
                }

                // Kosongkan selection
                $('#kelas_select').val('');
                $('#jurusan_select').val('');

                // Render ulang
                renderNewClassButtons();
            } else {
                alert('Mohon pilih Kelas dan Jurusan.');
            }
        });
    </script>
    @endpush
</x-layouts.app>
