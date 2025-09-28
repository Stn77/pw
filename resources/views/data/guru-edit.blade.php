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

                        <div class="col">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <span id="submitText">Simpan</span>
                                <span id="loadingSpinner" class="loading">
                                    <i class="bi bi-arrow-repeat"></i> Mengupload...
                                </span>
                            </button>
                            <button type="reset" class="btn btn-warning" id="resetBtn">
                                Reset
                            </button>
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
    @endpush
</x-layouts.app>
