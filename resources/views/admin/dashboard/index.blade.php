@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
    <style>
    </style>
@endpush

@section('content')
    <x-navbar-admin :name="Auth::user()->name">
        <div class=" py-4">
            <div class="card w-100 bg-white rounded-3">
                <div class="card-body">
                    <div class="card-title mb-2">
                        <span class="fw-bold fs-5">Jadwal Saat Ini</span>
                    </div>
                    <div class="card-content p-3 rounded-3 d-sm-block d-none">
                        <div class="">
                            @include('admin.dashboard.partials.table')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-navbar-admin>
@endsection