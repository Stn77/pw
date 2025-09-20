<x-layouts.app title="Qr Code" pageTitleName="Qr Code" sidebarShow=true>
    @push('style')
    <style>
        .qr-container {
            min-width: 300px;
            min-height: 300px;
            max-width: max-content;
            max-height: 600px;
            border: 3px solid gray;
        }
        img{
            max-width: 100%;
        }
    </style>
    @endpush
    <div class="qr-container mx-auto p-4 m-3">
        <img class="mx-auto" src="data:image/png;base64,{{ $qrcode }}" alt="QR Code">
    </div>
</x-layouts.app>
