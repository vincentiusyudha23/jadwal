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
@endsection
