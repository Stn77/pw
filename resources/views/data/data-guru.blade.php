<x-layouts.app title="Data Guru" pageTitleName="Data Guru" sidebarShow=true>
    @push('style')
    <style>
        .desktop-content{
            min-height: 300px;
            padding: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }
        .desktop-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        .desktop-container {
            padding: 15px;
        }
        .nav-tabs .nav-link {
            color: #495057;
            font-weight: 500;
        }
        .nav-tabs .nav-link.active {
            font-weight: 600;
        }
        .content-area {
            padding: 20px 0;
            min-height: 300px;
        }

        .form-import-excel, #desktopTabContent{
            width: 100%;
        }
        .submit-multi-user{
            gap: 1rem;
        }
    </style>
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

    </style>
    @endpush
    <div class="d-flex flex-column container-fluid" >
        <div class="d-flex mb-4">
            <div class="filter d-flex form-group flex-row">
                <div class="jurusan-f me-2 form-group">
                    {{-- <label for="kelas" class="form-label mb-0">Kelas</label> --}}
                    <select class="form-select form-select-sm me-2" name="kelas" id="kelas" aria-placeholder="konz">
                        <option value="">kelas</option>
                        <option value="x">X</option>
                        <option value="xi">XI</option>
                        <option value="xii">XII</option>
                    </select>
                </div>
                <div class="kelas-f me-2 form-group">
                    {{-- <label for="jurusan" class="form-label mb-0">Jurusan</label> --}}
                    <select class="form-select form-select-sm me-2" name="jurusan" id="jurusan">
                        <option value="">Jurusan</option>
                        <option value="pplg">PPLG</option>
                        <option value="tsm">TSM</option>
                        <option value="dkv">DKV</option>
                        <option value="mp">mp</option>
                        <option value="ak">ak</option>
                        <option value="bd">bd</option>
                        <option value="tkkr">tkkr</option>
                    </select>
                </div>
                <div class="action">
                    <button class="btn btn-success btn-sm" id="filter">Filter</button>
                    <button class="btn btn-danger btn-sm" id="reset">Reset</button>
                </div>
            </div>
        </div>
        <div class="d-flex flex-column">
            <div class="d-flex w-100 mb-3">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="bi bi-plus"></i> Tambah Data
                </button>
            </div>
            <div class="table-responsive">
                <table class="table mt-2 table-striped table-bordered" id="data-guru">
                    <thead class="text-light table-dark mt-2"></thead>
                    <tbody>
                        <!-- Data akan diisi oleh DataTables -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- modal --}}
    <div class="modal fade" id="infoGuruModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

            </div>
        </div>
    </div>

    {{-- add modal --}}
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Data Guru</h5>
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
                                    <h4>Form Tambah Satu Data Guru</h4>
                                    <form action="" method="POST">
                                        @csrf

                                        <div class="mb-3">
                                            <label for="nama" class="form-label">Nama Guru</label>
                                            <input type="text" class="form-control" id="nama">
                                        </div>

                                        <div class="mb-3">
                                            <label for="username" class="form-label">Username Guru</label>
                                            <input type="text" class="form-control" id="username">
                                        </div>

                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email">
                                        </div>

                                    </form>
                                </div>
                                <div class="tab-pane fade" id="banyak-data-content" role="tabpanel" aria-labelledby="banyak-data-tab">
                                    <form action="{{route('data.guru.import')}}" class="form-import-excel" method="POST" enctype="multipart/form-data" id="upload-form">
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
                                                        <thead class="table-light">
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td><strong>NIP</strong></td>
                                                                <td><strong>Username</strong></td>
                                                                <td><strong>Nama Lengkap</strong></td>
                                                                <td><strong>Email (opsional)</strong></td>
                                                                <td><strong>Password</strong></td>
                                                            </tr>
                                                            <tr class="table-light">
                                                                <td>1920090210982792</td>
                                                                <td>jesi182</td>
                                                                <td>Jesika Kharis</td>
                                                                <td>jesika12@gmail.com</td>
                                                                <td>password123</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex submit-multi-user">
                                            <button type="submit" class="btn btn-primary btn-lg flex-fill" id="submit-btn" disabled>
                                                <i class="fas fa-upload me-2"></i>Import Data Guru
                                            </button>
                                            <a href="{{route('data.guru.template')}}" id="unduhTemplate" class="btn btn-success btn-lg flex-fill">
                                                <i class="fas fa-download me-2"></i>Unduh Template ?
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
            let absenSiswaTable = $('#data-guru').DataTable({
                processing: true,
                pageLength: 50,
                serverSide: true,
                autoFill: false,
                ajax: {
                    url: '{{route('data.guru.get')}}',
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
                    {data: 'nip', title: 'NIP'},
                    {data: 'name', title: 'Nama'},
                    {
                        data: 'action',
                        title: 'Aksi',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            // let id = ri;
                            return `
                                <div class="btn-group">
                                    <a href="{{route('data.guru.edit', '')}}/ ${row.id}" class="btn btn-sm btn-primary info-btn mx-2" data-id="${row.id}">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button class="btn btn-sm btn-warning info-btn mx-2" onclick="infoGuru(${row.id})" data-id="${row.id}">
                                        <i class="bi bi-info"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger delete-btn mx-2" data-id="${row.id}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            `;
                        }
                    }
                ]
            })

        })

        function infoGuru(id){
            $('#infoGuruModal').modal('show')
            $('#modalTitle').text('Informasi Guru')


        }

        function editGuru(id){

        }

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
                fetch('{{route('data.guru.import')}}', {
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
                submitBtn.innerHTML = '<i class="fas fa-upload me-2"></i>Import Data Guru';
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
                        Klik "Import Data Guru" untuk memulai proses import
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
