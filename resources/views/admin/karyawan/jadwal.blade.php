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
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <ul class="px-4 m-0">
                                @foreach ($errors->all() ?? [] as $msg)
                                    <li>{{ $msg }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('admin.karyawan.jadwal.store') }}" method="POST">
                        @csrf
                        <div class="w-100">
                            <div class="row mb-3">
                                <div class="col-12 col-lg-6">
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
                            <div class="input-group mb-3">
                                <input class="form-control" type="text" name="tujuan" placeholder="Tujuan...">
                            </div>
                            <div class="input-group mb-3">
                                <textarea class="form-control" name="tugas" placeholder="Tugas..."></textarea>
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
@endsection

@push('scripts')
    @if (session('success'))
        <script>
            toastr.success('{{ session("success") }}');
        </script>
    @endif
@endpush
