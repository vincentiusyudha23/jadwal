@extends('layouts.app')

@section('title', 'Details Absen')

@section('content')
    <x-navbar-admin :name="Auth::user()->name">
        <div class="py-2">
            <div class="card">
                <div class="card-body">
                    <div class="card-title w-100 d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold">Absen Tanggal: <br> <small>{{ $absens->first()->created_at->translatedFormat('l, d/m/Y') }}</small></h5>
                        <a href="{{ route( route_prefix() . 'absen.riwayat') }}" class="btn btn-sm btn-primary">Kembali</a>
                    </div>
                    <div class="card-content">
                        <div class="card bg-secondary bg-opacity-10 p-2 mb-3">
                            <div>
                                <span class="fw-bold">Nama :</span>
                                <span>{{ $absens->first()->user->name }}</span>
                            </div>
                            <div>
                                <span class="fw-bold">ID Karyawan :</span>
                                <span>{{ $absens->first()->user->id_karyawan }}</span>
                            </div>
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