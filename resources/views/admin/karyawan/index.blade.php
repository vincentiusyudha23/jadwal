@extends('layouts.app')

@section('title', 'Halaman Utama')

@section('content')
    <x-navbar-admin :name="Auth::user()->name">
        <div class="py-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="card-title d-flex justify-content-between align-items-center">
                        <p class="fs-5 text-gray-600 fw-bold">Input Data Karyawan</p>
                        <button type="button" class="btn btn-sm btn-info fw-bold text-light">Import</button>
                    </div>
                    <form>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group mb-3">
                                    <input class="form-control" name="nama" type="text" placeholder="Nama Karyawan">
                                </div>
                                <div class="form-group mb-3">
                                    <input class="form-control" name="id_karyawan" type="text" placeholder="ID Karyawan">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group mb-3">
                                    <input class="form-control" name="username" type="text" placeholder="Username">
                                </div>
                                <div class="form-group mb-3">
                                    <input class="form-control" name="password" type="password" placeholder="Password">
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
                            <input type="number" class="form-control" name="id_karyawan" id="id_karyawan" value="">
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
                            <input type="password" class="form-control" name="password" id="password" value="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success w-100">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection
