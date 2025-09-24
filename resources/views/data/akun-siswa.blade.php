<x-layouts.app title="Akun Siswa" pageTitleName="Akun Siswa" sidebarShow=true>
    @push('style')
    <style>
        .drag-drop-area {
            border: 2px dashed #cbd5e0;
            border-radius: 8px;
            padding: 3rem 2rem;
            text-align: center;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            min-height: 200px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .drag-drop-area:hover {
            border-color: #007bff;
            background-color: #e6f3ff;
        }

        .drag-drop-area.drag-over {
            border-color: #007bff;
            background-color: #e6f3ff;
            border-style: solid;
            transform: scale(1.02);
        }

        .drag-drop-area.has-file {
            border-color: #28a745;
            background-color: #d4edda;
        }

        .file-input-hidden {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .upload-icon {
            font-size: 3rem;
            color: #6c757d;
            margin-bottom: 1rem;
        }

        .drag-over .upload-icon {
            color: #007bff;
            animation: bounce 0.6s ease-in-out;
        }

        .has-file .upload-icon {
            color: #28a745;
        }

        @keyframes bounce {
            0%, 20%, 60%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            80% { transform: translateY(-5px); }
        }

        .file-info {
            display: none;
            background: white;
            padding: 1rem;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            margin-top: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .file-info.show {
            display: block;
        }

        .remove-file {
            color: #dc3545;
            cursor: pointer;
            float: right;
            font-size: 1.2rem;
        }

        .remove-file:hover {
            color: #a71d2a;
        }

        .upload-text {
            font-size: 1.1rem;
            color: #495057;
            margin: 0.5rem 0;
        }

        .upload-subtext {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .progress {
            display: none;
            margin-top: 1rem;
        }

        .form-import-excel, #desktopTabContent{
            width: 100%;
        }
        .submit-multi-user{
            gap: 1rem;
        }
    </style>
    @endpush
    <div class="container-fluid py-3">
        <div class="d-flex flex-column">
            <div class="d-flex w-100 mb-3">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addmodal">
                    <i class="bi bi-plus"></i> Tambah Data
                </button>
            </div>
            <div class="table-responsive">
                <table class="table mt-2 table-striped table-bordered" id="akun-siswa">
                    <thead class="text-light table-dark mt-2"></thead>
                    <tbody>
                        <!-- Data akan diisi oleh DataTables -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addmodal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Data Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Tab Navigation -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="satu-data-tab" data-bs-toggle="tab"
                                    data-bs-target="#satu-data-content" type="button" role="tab"
                                    aria-controls="satu-data-content" aria-selected="true">
                                Satu Data
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="banyak-data-tab" data-bs-toggle="tab"
                                    data-bs-target="#banyak-data-content" type="button" role="tab"
                                    aria-controls="banyak-data-content" aria-selected="false">
                                Banyak Data
                            </button>
                        </li>
                    </ul>
                    <div class="desktop-container">
                        <div class="desktop-content">

                            <!-- Tab Content -->
                            <div class="tab-content content-area" id="desktopTabContent">
                                <div class="tab-pane fade show active" id="satu-data-content" role="tabpanel"
                                    aria-labelledby="satu-data-tab">
                                    <h4>Form Tambah Satu Data Siswa</h4>
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
                                <div class="tab-pane fade" id="banyak-data-content" role="tabpanel" aria-labelledby="banyak-data-tab">
                                    <form action="" class="form-import-excel" method="POST" enctype="multipart/form-data" id="upload-form">
                                        @csrf

                                        <div class="mb-4">
                                            <div class="drag-drop-area" id="drag-drop-area">
                                                <input type="file" class="file-input-hidden" id="excel_file" name="excel_file"
                                                    accept=".xlsx,.xls,.csv" required>

                                                <div class="upload-content">
                                                    <div class="upload-icon">
                                                        <i class="fas fa-cloud-upload-alt"></i>
                                                    </div>
                                                    <div class="upload-text">
                                                        <strong>Drag & Drop file Excel di sini</strong>
                                                    </div>
                                                    <div class="upload-subtext">
                                                        atau <span style="color: #007bff; font-weight: 500;">klik untuk browse</span>
                                                    </div>
                                                    <div class="upload-subtext mt-2">
                                                        <small>Format: .xlsx, .xls, .csv (Max: 10MB)</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="file-info" id="file-info">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <i class="fas fa-file-excel text-success me-2"></i>
                                                        <span id="file-name"></span>
                                                        <small class="text-muted ms-2">(<span id="file-size"></span>)</small>
                                                    </div>
                                                    <span class="remove-file" id="remove-file">
                                                        <i class="fas fa-times"></i>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="progress" id="upload-progress">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                    role="progressbar" style="width: 0%"></div>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <div class="card">
                                                <div class="card-header bg-light">
                                                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Format Excel yang diharapkan:</h6>
                                                </div>
                                                <div class="card-body">
                                                    <table class="table table-sm table-bordered mb-0">
                                                        <tbody>
                                                            <tr>
                                                                <td><strong>NISN</strong></td>
                                                                <td><strong>Nama Lengkap</strong></td>
                                                                <td><strong>Username</strong></td>
                                                                <td><strong>Email (Opsional)</strong></td>
                                                                <td><strong>Password</strong></td>
                                                                <td><strong>Kelas</strong></td>
                                                                <td><strong>Jurusan</strong></td>
                                                            </tr>
                                                            <tr class="table-light">
                                                                <td>12345/1234</td>
                                                                <td>John Doe</td>
                                                                <td>john_doe</td>
                                                                <td>john@example.com</td>
                                                                <td>password123</td>
                                                                <td>1,2,3</td>
                                                                <td>1,2,3,4,5,6,7</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex submit-multi-user">
                                            <button type="submit" class="btn btn-primary btn-lg flex-fill" id="submit-btn" disabled>
                                                <i class="fas fa-upload me-2"></i>Import Data Siswa
                                            </button>
                                            <a href="{{route('data.siswa.template')}}" id="unduhTemplate" class="btn btn-success btn-lg flex-fill">
                                                <i class="fas fa-download"></i>Unduh Template ?
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
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
                    {data: 'siswa.nisn', title: 'NISN Siswa'},
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

                            showNotifCreate('Data Siswa berhasil Dibuat', 'success')
                            $('#addmodal').modal('hide');
                            // Refresh tabel
                            akunSiswaTable.ajax.reload();
                        } else {
                            showNotifCreate(response.message, 'alert')
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dragDropArea = document.getElementById('drag-drop-area');
            const fileInput = document.getElementById('excel_file');
            const fileInfo = document.getElementById('file-info');
            const fileName = document.getElementById('file-name');
            const fileSize = document.getElementById('file-size');
            const removeFileBtn = document.getElementById('remove-file');
            const submitBtn = document.getElementById('submit-btn');
            const uploadForm = document.getElementById('upload-form');
            const uploadProgress = document.getElementById('upload-progress');
            const progressBar = uploadProgress.querySelector('.progress-bar');

            // Prevent default drag behaviors
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dragDropArea.addEventListener(eventName, preventDefaults, false);
                document.body.addEventListener(eventName, preventDefaults, false);
            });

            // Highlight drop area when item is dragged over it
            ['dragenter', 'dragover'].forEach(eventName => {
                dragDropArea.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dragDropArea.addEventListener(eventName, unhighlight, false);
            });

            // Handle dropped files
            dragDropArea.addEventListener('drop', handleDrop, false);
            dragDropArea.addEventListener('click', () => fileInput.click());
            fileInput.addEventListener('change', function(e) {
                if (e.target.files.length > 0) {
                    handleFiles(e.target.files);
                }
            });

            // Remove file
            removeFileBtn.addEventListener('click', function() {
                removeFile();
            });

            // AJAX FORM SUBMISSION
            uploadForm.addEventListener('submit', function(e) {
                e.preventDefault(); // Prevent normal form submission

                if (fileInput.files.length === 0) {
                    showNotifCreate('Pilih file Excel terlebih dahulu', 'error');
                    return;
                }

                uploadFileAjax();
            });

            function uploadFileAjax() {
                const formData = new FormData();
                formData.append('excel_file', fileInput.files[0]);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                // Show progress and disable button
                showProgress();
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Importing...';

                // Show processing notification
                showNotifCreate('Sedang memproses file Excel...', 'info');

                // AJAX Request
                fetch('{{route('data.siswa.import')}}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => Promise.reject(err));
                    }
                    return response.json();
                })
                .then(data => {
                    // Success handling
                    hideProgress();
                    resetForm();

                    // Show success notification
                    showNotifCreate(data.message, 'success');

                    // Show detailed errors if any
                    if (data.data.errors && data.data.errors.length > 0) {
                        setTimeout(() => {
                            let errorMessage = 'Detail Error:\n';
                            data.data.errors.slice(0, 5).forEach(error => {
                                errorMessage += '• ' + error + '\n';
                            });
                            if (data.data.errors.length > 5) {
                                errorMessage += `... dan ${data.data.errors.length - 5} error lainnya`;
                            }
                            showNotifCreate(errorMessage, 'warning', 3000);
                        }, 1000);
                    }

                    // RELOAD DATATABLE & Hide modal
                    $('#addModal').modal('hide');
                    $('#data-guru').DataTable().ajax.reload(null, false);
                })
                .catch(error => {
                    // Error handling
                    hideProgress();
                    resetSubmitButton();

                    let errorMessage = 'Terjadi kesalahan saat import';
                    if (error.message) {
                        errorMessage = error.message;
                    } else if (error.errors) {
                        // Validation errors
                        const firstError = Object.values(error.errors)[0];
                        if (Array.isArray(firstError)) {
                            errorMessage = firstError[0];
                        }
                    }

                    showNotifCreate(errorMessage, 'error');
                    console.error('Import Error:', error);
                });
            }

            function showProgress() {
                uploadProgress.style.display = 'block';
                let progress = 0;

                const interval = setInterval(() => {
                    progress += Math.random() * 10;
                    if (progress > 90) progress = 90;
                    progressBar.style.width = progress + '%';

                    if (progress >= 90) {
                        clearInterval(interval);
                    }
                }, 200);

                // Store interval untuk cleanup nanti
                uploadProgress.progressInterval = interval;
            }

            function hideProgress() {
                // Complete progress bar
                progressBar.style.width = '100%';
                progressBar.classList.remove('progress-bar-animated');

                // Clear interval if exists
                if (uploadProgress.progressInterval) {
                    clearInterval(uploadProgress.progressInterval);
                }

                // Hide after animation
                setTimeout(() => {
                    uploadProgress.style.display = 'none';
                    progressBar.style.width = '0%';
                    progressBar.classList.add('progress-bar-animated');
                }, 1000);
            }

            function resetForm() {
                fileInput.value = '';
                removeFile();
                resetSubmitButton();
            }

            function resetSubmitButton() {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-upload me-2"></i>Import Data Siswa';
            }

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            function highlight() {
                dragDropArea.classList.add('drag-over');
            }

            function unhighlight() {
                dragDropArea.classList.remove('drag-over');
            }

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                handleFiles(files);
            }

            function handleFiles(files) {
                if (files.length > 0) {
                    const file = files[0];

                    // Validate file type
                    const allowedTypes = [
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'application/vnd.ms-excel',
                        'text/csv'
                    ];

                    if (!allowedTypes.includes(file.type) && !file.name.match(/\.(xlsx|xls|csv)$/i)) {
                        showNotifCreate('Format file tidak didukung. Gunakan .xlsx, .xls, atau .csv', 'error');
                        return;
                    }

                    // Validate file size (10MB)
                    if (file.size > 10 * 1024 * 1024) {
                        showNotifCreate('Ukuran file terlalu besar. Maksimal 10MB', 'error');
                        return;
                    }

                    // Update file input
                    const dt = new DataTransfer();
                    dt.items.add(file);
                    fileInput.files = dt.files;

                    // Show file info
                    showFileInfo(file);
                    showNotifCreate(`File "${file.name}" siap untuk diupload`, 'success');
                }
            }

            function showFileInfo(file) {
                fileName.textContent = file.name;
                fileSize.textContent = formatFileSize(file.size);
                fileInfo.classList.add('show');
                dragDropArea.classList.add('has-file');
                submitBtn.disabled = false;

                // Update drag drop area content
                const uploadContent = dragDropArea.querySelector('.upload-content');
                uploadContent.innerHTML = `
                    <div class="upload-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="upload-text text-success">
                        <strong>File siap untuk diupload!</strong>
                    </div>
                    <div class="upload-subtext">
                        Klik "Import Data Siswa" untuk memulai proses import
                    </div>
                `;
            }

            function removeFile() {
                fileInput.value = '';
                fileInfo.classList.remove('show');
                dragDropArea.classList.remove('has-file');
                submitBtn.disabled = true;

                // Reset drag drop area content
                const uploadContent = dragDropArea.querySelector('.upload-content');
                uploadContent.innerHTML = `
                    <div class="upload-icon">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <div class="upload-text">
                        <strong>Drag & Drop file Excel di sini</strong>
                    </div>
                    <div class="upload-subtext">
                        atau <span style="color: #007bff; font-weight: 500;">klik untuk browse</span>
                    </div>
                    <div class="upload-subtext mt-2">
                        <small>Format: .xlsx, .xls, .csv (Max: 10MB)</small>
                    </div>
                `;
            }

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }
        });
    </script>
    @endpush
</x-layouts.app>
