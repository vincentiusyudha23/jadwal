@extends('layouts.app')

@section('title', 'Jadwal')

@push('styles')
    <style>
        .card-content{
            width: 100%;
            overflow-x: scroll;
            scrollbar-color: transparent transparent;
        }

        .table-content{
            width: max-content;
        }
    </style>
@endpush

@section('content')
    <x-navbar-admin :name="Auth::user()->name">
        <div class="py-2">
            <div class="card">
                <div class="card-body">
                    <div class="card-title w-100 d-flex justify-content-between align-items-center mb-3">
                        <h5 class="text-gray-700 fw-bold">Jadwal Sebelumnya</h5>
                        <a href="{{ route('karyawan.export.jadwal') }}" class="btn btn-sm btn-success fw-bold">Export</a>
                    </div>
                    <div class="card-content">
                        <div class="tabel-content">
                            @include('admin.dashboard.partials.table')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-navbar-admin>
@endsection