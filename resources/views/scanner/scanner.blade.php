<x-layouts.app title="Dashboard" pageTitleName="Dashboard">
    @push('style')
    <style>
        video {
            transform: scaleX(-1);
        }

        .render-container {
            height: 600px;
            max-height: 600px;
            padding: 0.5rem 0;
        }
    </style>
    @endpush

    <button id="btn-open" class="btn btn-success">Buka Kamera</button>
    <button id="btn-close" class="btn btn-danger" style="display:none;">Tutup Kamera</button>

    <div class="render-container">
        <div id="reader" class="" style="min-width: 500px; max-width: 750px; margin: auto;"></div>
    </div>

    <div>Joss</div>

    @push('script')
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        let html5QrCode;
        let currentCameraId;

        function onScanSuccess(decodedText, decodedResult) {
            alert(`QR Code terdeteksi: ${decodedText}`);
            alert(`Code matched = ${decodedText}`, decodedResult);
            // console.log(result)

            // $.ajax({
            //     url: '{{route('scanner.scan')}}',
            //     method: 'POST',
            //     data: {
            //         decodedText: decodedText,
            //         decodedResult: decodedResult,
            //         _token: $('meta[name="csrf-token"]').attr('content')
            //     },
            //     success: function (res) {
            //         if(res.status === 200){
            //             console.log(res.message)
            //             alert(res.message)
            //         }else{
            //             alert('some error')
            //         }
            //     },
            //     error: function (xhr) {
            //         console.log(xhr)
            //     }
            // })
            try{
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
                    console.log(data.message)
                })
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
    @endpush

</x-layouts.app>
