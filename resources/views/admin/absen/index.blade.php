@extends('layouts.app')

@section('title', 'Data Absensi')

@section('content')
    <x-navbar-admin :name="Auth::user()->name">
        <div class="py-2">
            <nav aria-label="breadcrumb" class="p-0 mt-2">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Halaman Utama</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.absen.riwayat') }}">Data Absensi</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ str_replace('/', '-', request('tanggal', '')) }}</li>
                </ol>
            </nav>
            <div class="card">
                <div class="card-body">
                    <div class="card-title w-100 d-flex justify-content-between align-items-center mb-3">
                        <h5 class="text-gray-700 fw-bold">Data Absensi</h5>
                        <a href="{{ route('admin.absen.export', ['tanggal' => \Carbon\Carbon::parse(str_replace('/', '-', request('tanggal', '')))]) }}" class="btn btn-sm btn-success fw-bold">Export</a>
                    </div>
                    <div class="card-content">
                        <x-table-absensi :absens="$absensi" :routeDelete="route('admin.absen.delete')"/>
                    </div>
                </div>
            </div>
        </div>
    </x-navbar-admin>
@endsection