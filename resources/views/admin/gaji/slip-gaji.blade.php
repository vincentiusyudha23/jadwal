@extends('layouts.app')

@section('title', 'Slip Gaji')

@push('styles')
    <style>
        iframe{
            width: 100%;
            height: 100%;
            transform: scale(1.35);
            transform-origin: center;
        }
    </style>
@endpush

@section('content')
    <x-navbar-admin :name="Auth::user()->name">
        <div class="mt-3 overflow-hidden" style="width: 100%; height: 550px;">
            <iframe src="{{ route('admin.gaji.slip-gaji.frame', ['id' => $gaji->id]) }}" id="iframe-slip-gaji"></iframe>
        </div>
        <div class="d-flex flex-column flex-md-row gap-2 w-100 justify-content-center mt-2">
            <button class="btn btn-info" onclick="printIframe()">Print</button>
            <button class="btn btn-success" onclick="downloadPDF()">Unduh</button>
            <button class="btn btn-secondary">Cancel</button>
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
                
                const karyawanID = iframeDoc.querySelector("#id_karyawan")?.textContent || "unknown"; 
                pdf.save(`slip-gaji-${karyawanID}.pdf`);
            } catch (error) {
                console.error("Gagal membuat PDF:", error);
            }
        }
    </script>
@endpush