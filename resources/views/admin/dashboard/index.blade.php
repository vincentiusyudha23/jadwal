@extends('layouts.app')

@section('title', 'Halaman Utama')

@push('styles')
    <style>
    </style>
@endpush

@section('content')
    <x-navbar-admin :name="Auth::user()->name">
        <div class="py-4">
            <div class="card w-100 bg-white rounded-3">
                <div class="card-body">
                    <div class="w-100 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="fw-bold fs-5">Jadwal Saat Ini</span>
                        </div>
                        <div>
                            <span>Tanggal : {{ Carbon\Carbon::now()->format('d-M-Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 d-none d-lg-block">
                    <div class="card w-100">
                        <div class="card-body">
                            @include('admin.dashboard.partials.table')
                        </div>
                    </div>
                </div>
                <x-card-tugas/>
                <x-card-tugas/>
                <x-card-tugas/>
                <x-card-tugas/>
            </div>
        </div>
    </x-navbar-admin>
@endsection
