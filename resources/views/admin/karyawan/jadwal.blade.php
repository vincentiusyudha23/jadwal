@extends('layouts.app')

@section('title', 'Jadwal Karyawan')

@section('content')
    <x-navbar-admin :name="Auth::user()->name">
        <div class="py-2">
            <nav aria-label="breadcrumb" class="p-0 mt-2">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Halaman Utama</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Jadwal Karyawan</li>
                </ol>
            </nav>
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="card-title d-flex justify-content-between align-items-center">
                        <p class="text-gray-600 fw-bold fs-5">Input Jadwal Baru</p>
                    </div>
                    @if (session('errors'))
                        <div class="alert alert-danger" role="alert">
                            <ul class="px-4 m-0">
                                <li>{{ session('errors') }}</li>
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('admin.karyawan.jadwal.store') }}" method="POST">
                        @csrf
                        <div class="w-100">
                            <div class="row mb-3">
                                <div class="col-12 col-lg-6 mb-3 mb-lg-0">
                                    <div class="input-group">
                                        <select class="form-select" name="karyawan" aria-label="Default select example">
                                            <option disabled selected>Pilih Karyawan</option>
                                            @foreach ($karyawans ?? [] as $karyawan)
                                                <option value="{{ $karyawan->id }}">{{ $karyawan->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="input-group">
                                        <input class="form-control" type="date" name="tanggal">
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6 col-12">
                                    <div class="input-group">
                                        <input class="form-control" type="text" name="tujuan" placeholder="Tujuan...">
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="input-group">
                                        <input class="form-control" type="time" name="waktu">
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6 col-12">
                                    <div class="input-group mb-3">
                                        <textarea class="form-control" name="tugas" placeholder="Tugas..."></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="input-group mb-3">
                                        <textarea class="form-control" name="note" placeholder="Catatan..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex w-100 justify-content-end">
                            <button class="btn btn-success w-100" type="submit">
                                <span class="fw-bold fs-6">Simpan</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card shadow-sm card-table">
                <div class="card-body p-4">
                    <div class="w-100 d-flex justify-content-between align-item-center mb-3">
                        <span class="text-gray-600 fs-5">Jadwal Karyawan</span>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.export.jadwal.all') }}" type="button" class="btn btn-sm btn-success">
                                Export
                            </a>
                            <button type="button" class="btn btn-primary btn-sm btn-import-jadwal">Import</button>
                        </div>
                    </div>
                    @include('admin.karyawan.partials.tabel-jadwal')
                </div>
            </div>
        </div>
    </x-navbar-admin>
@endsection

@push('scripts')
    @if (session('success'))
        <script>
            toastr.success('{{ session("success") }}');
        </script>
    @endif

    <script>
        $(document).ready(function(){
            $(document).on('click', '.btn-import-jadwal',function(){
                Swal.fire({
                    title: "Import Jadwal Karyawan",
                    input: "file",
                    inputAttributes:{
                        "accept" : ".xls,.xlsx,.csv",
                        "aria-label": "Unggah file excel anda"
                    },
                    showDenyButton: true,
                    confirmButtonText: "Simpan",
                    denyButtonText: "Unduh Template",
                    customClass: {
                        denyButton: "bg-primary",
                        confirmButton: "bg-success"
                    },
                    preConfirm: (file) => {
                        if(!file){
                            Swal.showValidationMessage("Silakan pilih file untuk diunggah");
                        }

                        return file;
                    }
                }).then(async (result) => {
                    if(result.isConfirmed){
                        const formData = new FormData();
                        formData.append('file', result.value);
                        formData.append('_token', '{{ csrf_token() }}');

                        await $.ajax({
                            url: '{{ route('admin.import.jadwal.karyawan') }}',
                            type: 'POST',
                            processData: false,
                            contentType: false,
                            data: formData,
                            beforeSend: function(){
                                Swal.fire({
                                    title: 'Uploading...',
                                    text: 'Harap tunggu, sistem sedang memproses file anda.',
                                    allowOutsideClick: false,
                                    showConfirmButton: false,
                                    willOpen: () => {
                                        Swal.showLoading();
                                    }
                                });
                            },
                            success: function(res){
                                Swal.hideLoading();
                                if(res.type == 'success'){
                                    Swal.fire({
                                        title: 'Success',
                                        text: res.msg,
                                        icon: 'success',
                                    }).then( () => {
                                        location.reload();
                                    });
                                }
                            },
                            error: function(err){
                                Swal.hideLoading();
                                
                                Swal.fire({
                                    title: 'Gagal',
                                    text: 'Gagal mengimport data karyawan.',
                                    icon: 'error',
                                });
                            }
                        });
                    } else if(result.isDenied){
                        window.location.href = "{{ route('admin.download.template.jadwal') }}";
                        setTimeout(() => {
                            $('.btn-import-jadwal').trigger('click');
                        }, 100);
                    }
                });
            });
        });
    </script>
@endpush
