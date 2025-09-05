<div>
    @push('style')
    <style>
        video {
            transform: scaleX(-1);
        }
        .render-container{
            height: 600px;;
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
</div>

@push('script')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
  let html5QrCode;
  let currentCameraId;

  function onScanSuccess(decodedText, decodedResult) {
    alert(`QR Code terdeteksi: ${decodedText}`);
    console.log(`Code matched = ${decodedText}`, decodedResult);
    let result = sendToLivewire(decodedText, decodedResult);
    console.log(result)
  }

//   function sendToLivewire(decodedText, decodedResult) {
//     @this->call('checkScan', ${decodedText})
//   }

  function onScanFailure(error) {
    // Abaikan error kecil
  }

  document.getElementById("btn-open").addEventListener("click", function () {
    Html5Qrcode.getCameras().then(cameras => {
      if (cameras && cameras.length) {
        currentCameraId = cameras[0].id;
        html5QrCode = new Html5Qrcode("reader");

        html5QrCode.start(
          currentCameraId,
          {
            fps: 10,
            qrbox: { width: 300, height: 300 }
          },
          onScanSuccess,
          onScanFailure
        ).then(() => {
          document.getElementById("reader").style.display = "block";
          document.getElementById("btn-open").style.display = "none";
          document.getElementById("btn-close").style.display = "inline";
        });
      } else {
        alert("Tidak ada kamera ditemukan.");
      }
    }).catch(err => {
      console.error("Error akses kamera:", err);
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
