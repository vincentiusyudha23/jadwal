@extends('layouts.app')

@section('title', 'Details Absen')

@section('content')
    <x-navbar-admin :name="Auth::user()->name">
        <div class="py-2">
            @if (Auth::user()->hasRole('admin'))
            <nav aria-label="breadcrumb" class="p-0 mt-2">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Halaman Utama</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.absen.riwayat') }}">Data Absensi</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ back()->getTargetUrl() }}">{{ $absens->first()->tanggal->format('d-m-Y') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detail</li>
                </ol>
            </nav>
            @endif
            <div class="card">
                <div class="card-body">
                    <div class="card-content">
                        <div class="card d-flex justify-content-between align-items-start flex-row bg-secondary bg-opacity-10 p-2 mb-3">
                            <div>
                                <div>
                                    <span class="fw-bold">Nama :</span>
                                    <span>{{ $absens->first()->user->name }}</span>
                                </div>
                                <div>
                                    <span class="fw-bold">ID Karyawan :</span>
                                    <span>{{ $absens->first()->user->id_karyawan }}</span>
                                </div>
                            </div>

                            <a href="{{ back()->getTargetUrl() }}" class="btn btn-sm btn-primary">Kembali</a>
                        </div>

                        <div class="row">
                            @foreach ($absens as $absen)
                                <div class="col-md-6 col-12">
                                    <x-card-absen :absen="$absen"/>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-navbar-admin>
@endsection