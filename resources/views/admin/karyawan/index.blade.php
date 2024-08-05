@extends('layouts.app')

@section('title', 'Halaman Utama')

@push('styles')
    <style>
        .input-group .input-group-text:hover {
            cursor: pointer;
            background: rgb(0, 0, 0, 0.1);
        }
    </style>
@endpush

@section('content')
    <x-navbar-admin :name="Auth::user()->name">
        <div class="py-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="card-title d-flex justify-content-between align-items-center">
                        <p class="fs-5 text-gray-600 fw-bold">Input Data Karyawan</p>
                        <button type="button" class="btn btn-sm btn-info fw-bold text-light">Import</button>
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <ul class="px-4 m-0">
                                @foreach ($errors->all() ?? [] as $msg)
                                    <li>{{ $msg }}</li>
                                @endforeach
                            </ul>
                        </div>
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
                                        placeholder="Username" required value="{{ old('username') }}">
                                    <span class="input-group-text" id="add_value_username"><i
                                            class="fa-solid fa-plus"></i></span>
                                </div>
                                <div class="input-group mb-3">
                                    <input class="form-control" id="password" name="password" type="text"
                                        placeholder="Password" required value="{{ old('password') }}">
                                    <span class="input-group-text" id="add_value_password"><i
                                            class="fa-solid fa-plus"></i></span>
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
                    @include('admin.karyawan.partials.table')
                </div>
            </div>
        </div>
    </x-navbar-admin>
    <div class="modal fade" id="edit-karyawan-form" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Data Karyawan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" class="edit-karyawan-form">
                    @csrf
                    <input type="hidden" name="id" value="">
                    <div class="modal-body">
                        <div class="mb-2">
                            <label for="nama" class="form-label">Nama Karyawan</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="nama" id="nama" value="">
                            </div>
                        </div>
                        <div class="mb-2">
                            <label for="id_karyawan" class="form-label">ID Karyawan</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="id_karyawan" id="id_karyawan" value="">
                            </div>
                        </div>
                        <div class="mb-2">
                            <label for="username" class="form-label">Username</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="username" id="username" value="">
                            </div>
                        </div>
                        <div class="mb-2">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="password" id="password" value="">
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
    <script>
        $(document).ready(function() {
            $('#add_value_username').on('click', function() {
                let nama = $('#nama_karyawan_new').val();
                if (nama.length > 0) {
                    let namaPart = nama.split(' ');
                    let valNama = namaPart[0];

                    $(this).prev().val(valNama.toLowerCase());
                } else {
                    toastr.warning('Isi Terlebih Dahulu Username dan ID Karyawan.');
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
            
            $(document).on('click', '#btn-edit-karyawan', function(){
                var el = $(this);
                var id = el.data('id');
                var name = el.data('nama');
                var id_karyawan = el.data('id_karyawan');
                var username = el.data('username');
                var password = el.data('password');

                let form = $('#edit-karyawan-form');
                form.find('input[name="id"]').val(id);
                form.find('input[name="nama"]').val(name);
                form.find('input[name="id_karyawan"]').val(id_karyawan);
                form.find('input[name="username"]').val(username);
                form.find('input[name="password"]').val(password);
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
                            $('.table-data-karyawan').html(response.markup);
                            $('.table-data-karyawan').DataTable();
                            $('#edit-karyawan-form').find('button[aria-label="Close"]').click();
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
        });
    </script>
@endpush
