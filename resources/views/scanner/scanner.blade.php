<x-layouts.app title="Scanner" pageTitleName="Scanner">
    @push('style')
    <style>
        video {
            /* transform: scaleX(-1); */
        }

        .render-container {
            height: 600px;
            max-height: 600px;
            padding: 0.5rem 0;
        }
        .btn-c{
            width: max-content;
            height: max-content;
        }

    </style>
    @endpush

    <!-- Area notifikasi -->
    {{-- <div id="notification-area"></div> --}}

    <div class="btn-c">
        <button id="btn-open" class="btn btn-success">Buka Kamera</button>
        <button id="btn-close" class="btn btn-danger" style="display:none;">Tutup Kamera</button>
    </div>

    <div class="render-container">
        <div id="reader" class="" style="min-width: 500px; max-width: 750px; margin: auto;"></div>
    </div>

    @push('script')
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        let html5QrCode;
        let currentCameraId;

        // Fungsi untuk menampilkan notifikasi
        function showNotification(message, type = 'success') {
            // Tentukan kelas alert berdasarkan type
            let alertClass;
            switch(type) {
                case 'success':
                    alertClass = 'alert-success';
                    break;
                case 'warning':
                    alertClass = 'alert-warning';
                    break;
                case 'error':
                    alertClass = 'alert-danger';
                    break;
                case 'info':
                default:
                    alertClass = 'alert-info';
                    break;
            }

            // Buat elemen notifikasi
            const notificationId = 'notif-' + Date.now();
            const notification = $(
                `<div id="${notificationId}" class="alert ${alertClass} alert-auto-close alert-dismissible fade show" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    ${message}
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 100%"></div>
                    </div>
                </div>`
            );

            // Tambahkan notifikasi ke area notifikasi
            $('#notification-area').append(notification);

            // Animasikan progress bar
            setTimeout(function() {
                notification.find('.progress-bar').css('width', '0%');
            }, 10);

            // Hilangkan notifikasi setelah 2 detik
            setTimeout(function() {
                $('#' + notificationId).alert('close');
            }, 2000);
        }

        function onScanSuccess(decodedText, decodedResult) {
            try{
                setTimeout(() => {
                    fetch('{{route('scanner.scan')}}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token
                        },
                        body: JSON.stringify({
                            decodedText: decodedText,
                            decodedResult: decodedResult,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.status === 200){
                            // notifikasi tepat waktu
                            showNotification('Absensi berhasil dicatat (Tepat waktu)', 'success');
                        }else if (data.status === 201){
                            // notifikasi terlambat
                            showNotification('Absensi berhasil dicatat (Terlambat)', 'warning');
                        }else if  (data.status === 400){
                            // notifikasi sudah absen
                            showNotification('Anda sudah melakukan absensi hari ini', 'info');
                        }else{
                            // error
                            showNotification('Terjadi kesalahan: ' + (data.message || 'Unknown error'), 'error');
                        }
                    })
                    .catch(error => {
                        showNotification('Terjadi kesalahan jaringan: ' + error, 'error');
                    });
                },1000);
            } catch (error) {
                showNotification('Terjadi kesalahan: ' + error, 'error');
            }
        }

        function onScanFailure(error) {
            // Abaikan error kecil
        }

        document.getElementById("btn-open").addEventListener("click", function () {
            Html5Qrcode.getCameras().then(cameras => {
                if (cameras && cameras.length) {
                    // Cari kamera DroidCam
                    let droidCam = cameras.find(cam => cam.label.toLowerCase().includes("droidcam"));
                    currentCameraId = droidCam ? droidCam.id : cameras[0].id;

                    html5QrCode = new Html5Qrcode("reader");
                    html5QrCode.start(
                        currentCameraId,
                        { fps: 10, qrbox: { width: 300, height: 300 } },
                        onScanSuccess,
                        onScanFailure
                    );
                }
            })
            .then(() => {
                document.getElementById("reader").style.display = "block";
                document.getElementById("btn-open").style.display = "none";
                document.getElementById("btn-close").style.display = "inline";
            })
            .catch(err => {
                showNotification('Tidak dapat mengakses kamera: ' + err, 'error');
            });

        });

        document.getElementById("btn-close").addEventListener("click", function () {
            if (html5QrCode) {
                html5QrCode.stop().then(() => {
                document.getElementById("reader").style.display = "none";
                document.getElementById("btn-open").style.display = "inline";
                document.getElementById("btn-close").style.display = "none";
            }).catch(err => {
                showNotification('Gagal menghentikan kamera: ' + err, 'error');
            });
            }
        });
    </script>
    @endpush
</x-layouts.app>
