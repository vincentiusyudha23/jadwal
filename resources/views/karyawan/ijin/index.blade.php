@extends('layouts.app')

@section('title', 'Pengajuan Ijin')

@push('styles')
    <style>
        .radio-ijin{
            width: 20px;
            height: 20px;
        }
    </style>
@endpush

@section('content')
    <x-navbar-admin :name="Auth::user()->name">
        <div class="py-2">
            <div class="card">
                <div class="card-body">
                    <div class="card-title mb-3">
                        <h4 class="fw-bold">Pengajuan Izin</h4>
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session('errors'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('errors') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="w-100 p-2 rounded border bg-secondary bg-opacity-10 d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <span class="fs-6"><strong>Nama :</strong> {{ Auth::user()->name }}</span>
                                <br>
                                <span class="fs-6"><strong>ID Karyawan :</strong> {{ Auth::user()->id_karyawan }}</span>
                            </div>
                        </div>
                    </div>
                    <form action="{{ route('karyawan.ijin.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3 w-100 d-flex flex-md-row flex-column gap-3">
                            <div class="form-check">
                                <input class="form-check-input border-2 border-primary" type="radio" required name="tipe_ijin" id="pengajuan_ijin1" value="{{ \App\Enums\IjinEnum::IJIN }}">
                                <label class="form-check-label" for="pengajuan_ijin1">Izin</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input border-2 border-primary" type="radio" required name="tipe_ijin" id="pengajuan_ijin2" value="{{ \App\Enums\IjinEnum::SAKIT }}">
                                <label class="form-check-label" for="pengajuan_ijin2">Sakit</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input border-2 border-primary" type="radio" required name="tipe_ijin" id="pengajuan_ijin3" value="{{ \App\Enums\IjinEnum::CUTI }}">
                                <label class="form-check-label" for="pengajuan_ijin3">Cuti</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold" for="keterangan">Keterangan</label>
                            <div class="input-group">
                                <input type="text" required name="keterangan" id="keterangan" class="form-control" placeholder="Masukkan Keterangan...">
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-5 col-12">
                                    <div class="row">
                                        <div class="col-sm-6 col-12 mb-3">
                                            <label class="form-label fw-bold" for="from_date">Dari</label>
                                            <div class="input-group">
                                                <input type="Date" required name="from_date" id="from_date" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-12">
                                            <label class="form-label fw-bold" for="to_date">Sampai</label>
                                            <div class="input-group">
                                                <input type="Date" required name="to_date" id="to_date" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold" for="surat">Surat Izin / Sakit / Cuti</label>
                            <div class="input-group">
                                <input type="file" required class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,image/*" name="surat" id="surat">
                            </div>
                            <small class="text-secondary">*Untuk sakit dapat berupa surat keterangan dokter</small><br>
                            <small class="text-secondary">*Max File 10 MB</small>
                        </div>

                        <div class="mb-3">
                            <button class="btn btn-success w-100 fw-bold" type="submit">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-navbar-admin>
@endsection