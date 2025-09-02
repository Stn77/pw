<x-layouts.app title="Dashboard" pageTitleName="Dashboard" sidebarShow=true>
    @push('style')
    <style>
        video{
            transform: scaleX(-1);
        }
    </style>
    @endpush
    login success
    <div id="reader" width="100px" style="width: 600px;"></div>

    @push('script')
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
      function onScanSuccess(decodedText, decodedResult) {
        alert(`QR Code terdeteksi: ${decodedText}`);
        console.log(`Code matched = ${decodedText}`, decodedResult);
      }

      function onScanFailure(error) {
        // Jangan spam console, cukup abaikan
      }

      // Coba akses webcam laptop (biasanya kamera depan)
      Html5Qrcode.getCameras().then(cameras => {
        if (cameras && cameras.length) {
          // Ambil kamera pertama (default laptop)
          let cameraId = cameras[0].id;

          const html5QrCode = new Html5Qrcode("reader");
          html5QrCode.start(
            cameraId,
            {
              fps: 10,
              qrbox: { width: 300, height: 300 }
            },
            onScanSuccess,
            onScanFailure
          );
        } else {
          alert("Tidak ada kamera ditemukan.");
        }
      }).catch(err => {
        console.error("Error akses kamera:", err);
      });
    </script>
    @endpush
</x-layouts.app>
