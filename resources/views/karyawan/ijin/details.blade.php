@extends('layouts.app')

@section('title', 'Details Izin')

@section('content')
    <x-navbar-admin :name="Auth::user()->name">
        <div class="py-2">
            <div class="card">
                <div class="card-body">
                    <div class="card-title mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h4 class="fw-bold">Pengajuan Izin</h4>
                            <a class="btn btn-sm btn-primary" href="{{ route('karyawan.ijin.riwayat') }}">Kembali</a>
                        </div>
                        <div class="w-100 p-2 rounded border bg-secondary bg-opacity-10 d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <span class="fs-6"><strong>Nama :</strong> {{ Auth::user()->name }}</span>
                                <br>
                                <span class="fs-6"><strong>ID Karyawan :</strong> {{ Auth::user()->id_karyawan }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 w-100 d-flex flex-md-row flex-column gap-3">
                        <div class="form-check">
                            <input class="form-check-input border-2 border-primary" type="radio" required name="tipe_ijin" id="pengajuan_ijin1" {{ $ijin->type == \App\Enums\IjinEnum::IJIN ? 'checked' : 'disabled' }} readonly value="{{ \App\Enums\IjinEnum::IJIN }}">
                            <label class="form-check-label" for="pengajuan_ijin1">Izin</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input border-2 border-primary" type="radio" required name="tipe_ijin" id="pengajuan_ijin2" {{ $ijin->type == \App\Enums\IjinEnum::SAKIT ? 'checked' : 'disabled' }} readonly value="{{ \App\Enums\IjinEnum::SAKIT }}">
                            <label class="form-check-label" for="pengajuan_ijin2">Sakit</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input border-2 border-primary" type="radio" required name="tipe_ijin" id="pengajuan_ijin3" {{ $ijin->type == \App\Enums\IjinEnum::CUTI ? 'checked' : 'disabled' }} readonly value="{{ \App\Enums\IjinEnum::CUTI }}">
                            <label class="form-check-label" for="pengajuan_ijin3">Cuti</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold" for="keterangan">Keterangan</label>
                        <div class="input-group">
                            <input type="text" required name="keterangan" id="keterangan" class="form-control" readonly value="{{ $ijin->keterangan }}" placeholder="Masukkan Keterangan...">
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-5 col-12">
                                <div class="row">
                                    <div class="col-sm-6 col-12 mb-3">
                                        <label class="form-label fw-bold" for="from_date">Dari</label>
                                        <div class="input-group">
                                            <input type="Date" required name="from_date" readonly value="{{ $ijin->from_date->format('Y-m-d') }}" id="from_date" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-12">
                                        <label class="form-label fw-bold" for="to_date">Sampai</label>
                                        <div class="input-group">
                                            <input type="Date" required name="to_date" readonly value="{{ $ijin->to_date->format('Y-m-d') }}" id="to_date" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold" for="surat">Surat Izin / Sakit / Cuti</label>
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-lg-4 col-md-8 col-12">
                                    <div class="d-flex flex-column justify-content-center align-items-center">
                                        <a href="{{ asset('assets/surat_ijin/'.$ijin->surat) }}" target="_blank">
                                            <div class="card d-inline-block mb-2">
                                                <div class="card-body">
                                                    <i class="las la-file-alt fs-1"></i>
                                                </div>
                                            </div>
                                        </a>
                                        <a href="{{ asset('assets/surat_ijin/'.$ijin->surat) }}" class="text-break" target="_blank">{{ $ijin->surat }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-navbar-admin>
@endsection