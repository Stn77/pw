<x-layouts.app title="Dashboard" pageTitleName="Dashboard">
    @push('style')

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.2.1/dist/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
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
        .notification{
            width: 100%;
            height: max-content;
            position: relative;
            z-index: 5;
            top: 1rem;
            background-color: red;
        }
    </style>
    @endpush
    <div class="btn-c">
        <button id="btn-open" class="btn btn-success">Buka Kamera</button>
        <button id="btn-close" class="btn btn-danger" style="display:none;">Tutup Kamera</button>
        <div class="">

        </div>
    </div>

    <div class="render-container">
        <div id="reader" class="" style="min-width: 500px; max-width: 750px; margin: auto;"></div>
    </div>

    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <img src="..." class="rounded mr-2" alt="...">
            <strong class="mr-auto">Bootstrap</strong>
            <small class="text-muted">just now</small>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body">
            See? Just like this.
        </div>
    </div>

    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <img src="..." class="rounded mr-2" alt="...">
            <strong class="mr-auto">Bootstrap</strong>
            <small class="text-muted">2 seconds ago</small>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body">
            Heads up, toasts will stack automatically
        </div>
    </div>

    <div>Joss</div>

    @push('script')
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        let html5QrCode;
        let currentCameraId;

        function onScanSuccess(decodedText, decodedResult) {
            // alert(`QR Code terdeteksi: ${decodedText}`);
            // alert(`Code matched = ${decodedText}`, decodedResult);

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

                    })
                }, 2000);
            } catch (error) {
                alert(error)
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
            });

        });

        document.getElementById("btn-close").addEventListener("click", function () {
            if (html5QrCode) {
                html5QrCode.stop().then(() => {
                document.getElementById("reader").style.display = "none";
                document.getElementById("btn-open").style.display = "inline";
                document.getElementById("btn-close").style.display = "none";
            }).catch(err => {
                console.error("Gagal menghentikan kamera:", err);
            });
            }
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.6/dist/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.2.1/dist/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
    @endpush

</x-layouts.app>
