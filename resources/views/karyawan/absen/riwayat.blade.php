@extends('layouts.app')

@section('title', 'Riwayat Absen')

@section('content')
    <x-navbar-admin :name="Auth::user()->name">
        <div class="py-2">
            <div class="card">
                <div class="card-body">
                    <div class="card-title w-100 d-flex justify-content-between align-items-center mb-3">
                        <h5 class="text-gray-700 fw-bold">Riwayat Absensi</h5>
                        <a href="{{ route('karyawan.absen.export') }}" class="btn btn-sm btn-success fw-bold">Export</a>
                    </div>
                    <div class="card-content">
                        <x-table-absensi :absens="$absens" :routeDelete="route('karyawan.absen.delete')"/>
                    </div>
                </div>
            </div>
        </div>
    </x-navbar-admin>
@endsection