@extends('layouts.app')

@section('title', 'Jadwal Karyawan')

@section('content')
    <x-navbar-admin :name="Auth::user()->name">
        <div class="py-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="card-title d-flex justify-content-between align-items-center">
                        <p class="text-gray-600 fw-bold fs-5">Input Jadwal Baru</p>
                    </div>
                    <form>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group mb-3">
                                    <select class="form-select" name="hari" aria-label="Default select example">
                                        <option selected disabled>Hari</option>
                                        <option value="senin">Senin</option>
                                        <option value="selasa">Selasa</option>
                                        <option value="rabu">Rabu</option>
                                        <option value="kamis">Kamis</option>
                                        <option value="jumat">Jumat</option>
                                        <option value="sabtu">Sabtu</option>
                                        <option value="minggu">Minggu</option>
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <input class="form-control" name="tanggal" type="date" placeholder="Tanggal">
                                </div>
                                <div class="form-group mb-3">
                                    <select class="form-select" name="nama_karyawan" aria-label="Default select example">
                                        <option selected disabled>Nama Karyawan</option>
                                        <option value="1">Hanggar</option>
                                        <option value="2">Jati</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group mb-3">
                                    <input class="form-control" name="tujuan" type="text" placeholder="Tujuan">
                                </div>
                                <div class="form-group mb-3">
                                    <textarea class="form-control" name="tugas" style="height: 100%;" placeholder="Tugas"></textarea>
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
                        <button type="button" class="btn btn-sm btn-success">
                            <span class="me-1">Export</span>
                            <i class="fa-solid fa-download"></i>
                        </button>
                    </div>
                    @include('admin.karyawan.partials.tabel-jadwal')
                </div>
            </div>
        </div>
    </x-navbar-admin>
    <div class="modal fade" id="edit-jadwal-form" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Jadwal</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <select class="form-select" name="hari" aria-label="Default select example">
                                <option selected disabled>Hari</option>
                                <option value="senin">Senin</option>
                                <option value="selasa">Selasa</option>
                                <option value="rabu">Rabu</option>
                                <option value="kamis">Kamis</option>
                                <option value="jumat">Jumat</option>
                                <option value="sabtu">Sabtu</option>
                                <option value="minggu">Minggu</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <input class="form-control" name="tanggal" type="date" placeholder="Tanggal">
                        </div>
                        <div class="form-group mb-3">
                            <select class="form-select" name="nama_karyawan" aria-label="Default select example">
                                <option selected disabled>Nama Karyawan</option>
                                <option value="1">Hanggar</option>
                                <option value="2">Jati</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <input class="form-control" name="tujuan" type="text" placeholder="Tujuan">
                        </div>
                        <div class="form-group mb-3">
                            <textarea class="form-control" name="tugas" style="height: 100%;" placeholder="Tugas"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success w-100" type="submit">
                            <span class="fw-bold fs-6">Simpan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
