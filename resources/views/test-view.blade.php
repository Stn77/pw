<x-layouts.app title="Profile" pageTitleName="Profile" sidebarShow=true>
    @push('style')
    <style>
        .profile-pic{
            width: 200px;
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
            border-radius: 50%;
        }
        .current-image {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #dee2e6;
        }
        .loading {
            display: none;
            color: #007bff;
        }
        .alert {
            display: none;
            margin-top: 10px;
        }
        #imageError {
            font-size: 0.875rem;
            margin-top: 5px;
        }
    </style>
    @endpush

    <div class="w-100">
        <a href="{{route('data.guru.index')}}" class="btn btn-success"><i class="bi bi-arrow-left mx-2"></i> Kembali</a>
    </div>

    <form id="profileForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="d-flex m-c">
            <div class="mx-auto text-center">
                <div class="mx-auto overflow-hidden rounded-circle ratio ratio-1x1 p-2 d-flex justify-content-center mt-5 profile-pic">
                    <!-- Gambar Profile Saat Ini -->
                    <img id="currentProfileImage"
                         class="current-image"
                         alt="Foto Profile"
                         src="{{ $user->profile_image ? Storage::url('profile-images/' . $user->profile_image) : asset('images/default-profile.png') }}"
                         onerror="this.src='{{ asset('images/default-profile.png') }}'">
                </div>

                <!-- Preview Gambar Baru -->
                <div class="mb-3 col">
                    <div id="imagePreview-c">
                        <img id="imagePreview" class="preview-image" src="#" alt="Preview Gambar">
                    </div>
                </div>

                <!-- Info File -->
                <div id="fileInfo" class="text-muted small mt-2" style="display: none;"></div>
            </div>

            <div class="info">
                <div class="p-2">
                    <div class="form border shadow-sm rounded-2 p-2">
                        <!-- Alert Messages -->
                        <div id="successAlert" class="alert alert-success" role="alert"></div>
                        <div id="errorAlert" class="alert alert-danger" role="alert"></div>

                        <div class="form-group mb-3 col">
                            <label for="image" class="form-label">Pilih Gambar Untuk Foto Profile</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <div class="form-text">Format yang didukung: JPG, PNG, GIF, SVG. Maksimal 2MB Rasio 1:1</div>
                            <div id="imageError" class="text-danger"></div>
                        </div>

                        <div class="row">
                            <div class="form-group mb-3 col">
                                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" class="form-control" value="{{ $user->name ?? '' }}" readonly>
                            </div>

                            <div class="form-group mb-3 col">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" value="{{ $user->username ?? '' }}" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group mb-3 col">
                                <label for="nip" class="form-label">NIP</label>
                                <input type="text" name="nip" class="form-control" value="{{ $user->nip ?? '' }}" readonly>
                            </div>
                        </div>

                        <div class="col">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <span id="submitText">Simpan</span>
                                <span id="loadingSpinner" class="loading">
                                    <i class="bi bi-arrow-repeat spinner"></i> Mengupload...
                                </span>
                            </button>
                            <button type="button" class="btn btn-warning" id="resetBtn">
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
            // Image Preview dengan informasi file
            $('#image').change(function() {
                const file = this.files[0];
                const preview = $('#imagePreview');
                const currentImage = $('#currentProfileImage');
                const errorDiv = $('#imageError');
                const fileInfo = $('#fileInfo');

                // Reset error message
                errorDiv.text('');
                $('#errorAlert').hide();
                fileInfo.hide();

                if (file) {
                    // Tampilkan informasi file
                    const fileSize = (file.size / 1024 / 1024).toFixed(2);
                    fileInfo.html(`File: ${file.name}<br>Size: ${fileSize} MB`).show();

                    // Validate file type
                    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml'];
                    if (!allowedTypes.includes(file.type)) {
                        errorDiv.text('Format file tidak didukung. Harap pilih file JPG, PNG, GIF, atau SVG.');
                        this.value = '';
                        fileInfo.hide();
                        return;
                    }

                    // Validate file size (2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        errorDiv.text('Ukuran file terlalu besar. Maksimal 2MB.');
                        this.value = '';
                        fileInfo.hide();
                        return;
                    }

                    const reader = new FileReader();

                    reader.onload = function(e) {
                        // Hide current image and show preview
                        currentImage.hide();
                        preview.attr('src', e.target.result);
                        preview.show();
                    }

                    reader.onerror = function() {
                        errorDiv.text('Gagal membaca file gambar.');
                    }

                    reader.readAsDataURL(file);
                } else {
                    preview.hide();
                    fileInfo.hide();
                    currentImage.show();
                }
            });

            // Reset button functionality
            $('#resetBtn').click(function(e) {
                e.preventDefault();

                // Reset form
                $('#profileForm')[0].reset();

                // Reset image preview dan tampilkan gambar saat ini
                $('#imagePreview').hide();
                $('#currentProfileImage').show();
                $('#fileInfo').hide();

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

                // Validasi jika tidak ada file yang dipilih
                if (!$('#image').val()) {
                    errorAlert.text('Pilih gambar terlebih dahulu!');
                    errorAlert.show();
                    return;
                }

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
                                $('#currentProfileImage').attr('src', response.image_url + '?t=' + new Date().getTime());
                            }

                            // Reset form and preview
                            $('#profileForm')[0].reset();
                            $('#imagePreview').hide();
                            $('#fileInfo').hide();
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

                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            // Handle validation errors
                            const errors = xhr.responseJSON.errors;
                            if (errors.image) {
                                errorMessage = errors.image[0];
                            } else if (xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
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
