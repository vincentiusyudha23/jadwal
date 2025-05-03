@extends('layouts.app')

@section('title', 'Slip Gaji')

@push('styles')
    <style>
        iframe{
            width: 22cm;
            height: 11cm;
        }
    </style>
@endpush

@section('content')
    <x-navbar-admin :name="Auth::user()->name">
        <div class="mt-3 overflow-x-auto d-flex justify-content-center" style="width: 100%;">
            <iframe src="{{ route(route_prefix() . 'gaji.slip-gaji.frame', ['id' => $gaji->id]) }}" id="iframe-slip-gaji"></iframe>
        </div>
        <div class="d-flex flex-column flex-md-row gap-2 w-100 justify-content-center mt-2">
            <button class="btn btn-info" onclick="printIframe()">Print</button>
            <button class="btn btn-success" onclick="downloadPDF()">Unduh</button>
            @if (Auth::user()->hasRole('admin'))
            <a href="{{ route('admin.gaji.details') . '?bulan=' . $gaji->created_at->translatedFormat('F Y') }}" class="btn btn-secondary">Cancel</a>
            @else
            <a href="{{ route(route_prefix() . 'gaji.riwayat') }}" class="btn btn-secondary">Cancel</a>
            @endif
        </div>
    </x-navbar-admin>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        function printIframe(id) {
            var iframe = document.getElementById('iframe-slip-gaji');
            if (iframe.contentWindow) {
                iframe.contentWindow.focus(); 
                iframe.contentWindow.print();
            }
        }

        async function downloadPDF() {
            const iframe = document.getElementById('iframe-slip-gaji');
            if (!iframe) {
                console.error("Iframe tidak ditemukan!");
                return;
            }

            const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
            const content = iframeDoc.querySelector('.a4');
            if (!content) {
                console.error("Elemen .a4 tidak ditemukan dalam iframe!");
                return;
            }

            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF("l", "px", "a4");

            try {
                const canvas = await html2canvas(content, { scale: 3 });
                const imgData = canvas.toDataURL("image/png");

                const imgWidth = canvas.width;
                const imgHeight = canvas.height;
                const pdfWidth = pdf.internal.pageSize.getWidth();
                const pdfHeight = (imgHeight * pdfWidth) / imgWidth;

                pdf.addImage(imgData, "PNG", 10, 10, pdfWidth - 20, pdfHeight);
                
                pdf.save(`slip-gaji-{{ $gaji->user->id_karyawan }}.pdf`);
            } catch (error) {
                console.error("Gagal membuat PDF:", error);
            }
        }
    </script>
@endpush