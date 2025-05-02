@extends('layouts.app')

@section('title', 'Data Karyawan')

@push('styles')
    <style>
        .input-group .input-group-text:hover {
            cursor: pointer;
            background: rgb(0, 0, 0, 0.1);
        }
        @media (min-width: 576px) {
            .modal-card-id {
                width: fit-content;
            }
        }
    </style>
@endpush

@section('content')
    <x-navbar-admin :name="Auth::user()->name">
        <div class="py-2">
            <nav aria-label="breadcrumb" class="p-0 mt-2">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Halaman Utama</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Data Karyawan</li>
                </ol>
            </nav>
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="card-title d-flex justify-content-between align-items-center">
                        <p class="fs-5 text-gray-600 fw-bold">Input Data Karyawan</p>
                        {{-- <button type="button" class="btn btn-sm btn-info fw-bold text-light">Import</button> --}}
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <ul class="px-4 m-0">
                                @foreach ($errors->all() as $msg)
                                    <li>{{ $msg }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger msg-danger" role="alert">{{ session('error') }}</div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success msg-success" role="alert">{{ session('success') }}</div>
                    @endif
                    <form id="karyawan_form" method="POST" action="{{ route('admin.karyawan.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="input-group mb-3">
                                    <input class="form-control" id="nama_karyawan_new" name="nama" type="text"
                                        placeholder="Nama Karyawan" required value="{{ old('nama') }}">
                                </div>
                                <div class="input-group mb-3">
                                    <input class="form-control" id="id_karyawan_new" name="id_karyawan" type="text"
                                        placeholder="ID Karyawan" required value="{{ old('id_karyawan') }}">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="input-group mb-3">
                                    <input class="form-control" id="username" name="username" type="text"
                                        placeholder="Username" value="{{ old('username') }}">
                                    <span class="input-group-text" id="add_value_username"><i
                                            class="fa-solid fa-plus"></i></span>
                                </div>
                                <div class="input-group mb-3">
                                    <input class="form-control" id="password" name="password" type="text"
                                        placeholder="Password" value="{{ old('password') }}">
                                    <span class="input-group-text" id="add_value_password"><i
                                            class="fa-solid fa-plus"></i></span>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="input-group mb-3">
                                    <div class="input-group">
                                        <select class="form-select select2" name="jabatan" id="jabatan" data-placeholder="Jabatan">
                                            <option></option>
                                            @foreach ($jabatan as $item)
                                                <option value="{{ $item }}">{{ \App\Enums\JabatanEnum::getItemJabatan($item) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group">
                                        <select class="form-select select2" name="divisi" data-placeholder="Divisi">
                                            <option></option>
                                            @foreach ($divisi as $item)
                                                <option value="{{ $item }}">{{ \App\Enums\DivisiEnum::getItemDivisi($item) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="input-group mb-3">
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="nomor_rekening" id="nomor_rekening" value="{{ old('nomor_rekening') }}" placeholder="Nomor Rekening">
                                    </div>
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group">
                                        <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}" placeholder="Email">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="input-group mb-3">
                                    <input class="form-control input-uang" type="text" name="gaji" id="gaji" value="{{ old('gaji') }}" placeholder="Gaji Pokok">
                                </div>
                            </div>
                        </div>
                        <div class="d-flex w-100 justify-content-end">
                            <button class="btn btn-success w-100" type="submit" id="submit-new-karyawan">
                                <span class="fw-bold fs-6">Simpan</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card shadow-sm card-table">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-gray-600 fs-5">Data Karyawan</span>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.export.akun.all') }}"  class="btn btn-sm btn-success">
                                <span class="me-1 fw-bold">Export</span>
                            </a>
                            <button type="button" class="btn btn-sm btn-primary" id="import-btn">
                                <span class="me-1 fw-bold">Import</span>
                            </button>
                        </div>
                    </div>
                    @include('admin.karyawan.partials.table')
                </div>
            </div>
        </div>
    </x-navbar-admin>
    <div class="modal fade" id="edit-karyawan-form" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Data Karyawan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" class="edit-karyawan-form">
                    @csrf
                    <input type="hidden" name="id" value="">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="mb-2">
                                    <label for="nama-edit" class="form-label">Nama Karyawan</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="nama" id="nama-edit" value="">
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label for="id_karyawan-edit" class="form-label">ID Karyawan</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="id_karyawan" id="id_karyawan-edit" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-2">
                                    <label for="username-edit" class="form-label">Username</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="username" id="username-edit" value="">
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label for="password-edit" class="form-label">Password</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="password" id="password-edit" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-2">
                                    <label for="jabatan-edit" class="form-label">Jabatan</label>
                                    <div class="input-group">
                                        <select class="form-select select2" name="jabatan" id="jabatan-edit" data-placeholder="Jabatan" data-bs-parent="#edit-karyawan-form">
                                            <option></option>
                                            @foreach ($jabatan as $item)
                                                <option value="{{ strtolower($item) }}">{{ \App\Enums\JabatanEnum::getItemJabatan($item) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label for="divisi-edit" class="form-label">Divisi</label>
                                    <div class="input-group">
                                        <select class="form-select select2" name="divisi" data-placeholder="Divisi" id="divisi-edit" data-bs-parent="#edit-karyawan-form">
                                            <option></option>
                                            @foreach ($divisi as $item)
                                                <option value="{{ strtolower($item) }}">{{ \App\Enums\DivisiEnum::getItemDivisi($item) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-2">
                                    <label for="nomor_rekening-edit" class="form-label">No. Rekening</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="nomor_rekening" id="nomor_rekening-edit" value="">
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label for="email-edit" class="form-label">Email</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="email" id="email-edit" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-2">
                                    <label class="form-label" for="gaji-edit">Gaji</label>
                                    <div class="input-group">
                                        <input class="form-control input-edit-uang" type="text" name="gaji" id="gaji-edit" placeholder="Gaji Pokok">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success w-100">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/cleave.min.js"></script>
    <script>
        function printIframe(id) {
            var iframe = document.getElementById('iframe-card-id-'+id);
            if (iframe.contentWindow) {
                iframe.contentWindow.focus(); 
                iframe.contentWindow.print();
            }
        }

        $(document).ready(function() {

            new Cleave($('.input-uang'), {
                numeral: true,
                prefix: 'Rp ',
                delimiter: '.',       
                numeralDecimalMark: ',',
                numeralThousandsGroupStyle: 'thousand',
            });

            $('.btn-download-card').on('click', function(){
                let id = $(this).data('id_card');
                let name = $(this).data('name').toLowerCase().replace(/\s+/g, '_');
                const screenshotCard = document.getElementById('idcard-'+id);

                html2canvas(screenshotCard).then((canvas) => {
                    const base64image = canvas.toDataURL("image/png");
                    var anchor = document.createElement('a');
                    anchor.setAttribute("href", base64image);
                    anchor.setAttribute("download", `${name}-${id}.png`);
                    anchor.click();
                    anchor.remove();
                });
            });

            $('.select2').each(function() {
                var parent = $(this).data('bs-parent');

                if(parent){
                    $(this).select2({
                        theme: "bootstrap-5",
                        selectionCssClass: "select2--small",
                        dropdownCssClass: "select2--small",
                        tags: true,
                        dropdownParent: $(parent)
                    });
                }else{
                    $(this).select2({
                        theme: "bootstrap-5",
                        selectionCssClass: "select2--small",
                        dropdownCssClass: "select2--small",
                        tags: true
                    });
                }
            });

            
            $('#add_value_username').on('click', function() {
                let nama = $('#nama_karyawan_new').val();
                if (nama.length > 0) {
                    let namaPart = nama.split(' ');
                    let valNama = namaPart[0];

                    $(this).prev().val(valNama.toLowerCase());
                } else {
                    toastr.warning('Isi Terlebih Dahulu Nama dan ID Karyawan.');
                }
            });

            $('#add_value_password').on('click', function() {
                let nama = $('#nama_karyawan_new').val();
                let id_karyawan = $('#id_karyawan_new').val();

                if (nama.length > 0 && id_karyawan.length > 0) {
                    let namaPart = nama.split(' ');
                    let valNama = namaPart[0];

                    $(this).prev().val(valNama.toLowerCase() + id_karyawan);
                } else {
                    toastr.warning('Isi Terlebih Dahulu Username dan ID Karyawan.');
                }
            });
            $('#submit-new-karyawan').on('click', function(){
                var spinner = '<i class="fa-solid fa-spinner fa-spin"></i>';
                $(this).html(spinner).addClass('disabled');
            });
            
            $(document).on('click', '.btn-edit-karyawan', function(){
                var el = $(this);
                var id = el.data('id');
                var name = el.data('nama');
                var id_karyawan = el.data('id_karyawan');
                var username = el.data('username');
                var password = el.data('password');
                var jabatan = el.data('jabatan');
                var divisi = el.data('divisi');
                var norek = el.data('norek');
                var email = el.data('email');
                var gaji = el.data('gaji');

                let form = $('#edit-karyawan-form');
                form.find('input[name="id"]').val(id);
                form.find('input[name="nama"]').val(name);
                form.find('input[name="id_karyawan"]').val(id_karyawan);
                form.find('input[name="username"]').val(username);
                form.find('input[name="password"]').val(password);
                form.find('select[name="jabatan"]').val(jabatan).trigger('change');
                form.find('select[name="divisi"]').val(divisi).trigger('change');
                form.find('input[name="nomor_rekening"]').val(norek);
                form.find('input[name="email"]').val(email);

                const tagGaji = form.find('input[name="gaji"]');
                new Cleave(tagGaji, {
                    numeral: true,
                    prefix: 'Rp ',
                    delimiter: '.',       
                    numeralDecimalMark: ',',
                    numeralThousandsGroupStyle: 'thousand',
                }).setRawValue(gaji);
            });

            $('.edit-karyawan-form').on('submit', function(e){
                e.preventDefault();
                var el = $(this);
                var formData = new FormData(this);
                var btn = el.find('button[type="submit"]');

                $.ajax({
                    type: 'POST',
                    url: '{{ route("admin.karyawan.update") }}',
                    cache:false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    beforeSend: function(){
                        btn.html('<i class="fa-solid fa-spinner fa-spin"></i>');
                        btn.addClass('disabled');
                    },
                    success: function(response){
                        if(response.type === 'success'){
                            toastr.success('Berhasil Memperbarui Data');
                            location.reload();
                            // $('.table-data-karyawan').parent().html(response.markup);
                            // $('.table-data-karyawan').DataTable();
                            // $('#edit-karyawan-form').find('button[aria-label="Close"]').click();
                        }
                    },
                    error: function(response){

                    },
                    complete: function(){
                        btn.find('i').remove();
                        btn.text('Simpan');
                        btn.removeClass('disabled');
                    }
                });
            });

            $(document).on('click', '#import-btn',function(){
                Swal.fire({
                    title: "Import Data Karyawan",
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
                            url: '{{ route('admin.import.data.karyawan') }}',
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
                                var error = err.responseJSON.msg ?? [];
                                
                                let htmlErr = error.map(msg => `<span>${msg}</span>`).join('<br>');
                                
                                Swal.fire({
                                    title: 'Gagal',
                                    html: htmlErr,
                                    icon: 'error',
                                });
                            }
                        });
                    } else if(result.isDenied){
                        // window.open("{{ route('admin.download.template.import') }}", "_blank");
                        window.location.href = "{{ route('admin.download.template.import') }}";
                        setTimeout(() => {
                            $('#import-btn').trigger('click');
                        }, 100);
                    }
                });
            });
        });
    </script>
@endpush
