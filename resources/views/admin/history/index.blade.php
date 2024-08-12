@extends('layouts.app')

@section('title', 'Riwayat Jadwal')

@push('styles')
    <style>
        @media (max-width: 576px){
            .date-input{
                width: 100%;
            }
            .date-input.button{
                display: flex;
                gap: 10px;
                margin-top: 15px;
            }
            .date-input.button button{
                width: 80%;
            }
            .date-input.button a{
                width: 20%;
            }
        }
        .list-riwayat .list-riwayat-item:hover{
            scale: 0.99;
            opacity: 0.75;
        }
    </style>
@endpush

@section('content')
    <x-navbar-admin :name="Auth::user()->name">
        <div class="py-2">
            <nav aria-label="breadcrumb" class="p-0 mt-2">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Halaman Utama</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Riwayat Jadwal</li>
                </ol>
            </nav>
            <div class="card mx-w-100 p-2">
                <div class="card-body">
                    <div class="card-title mb-4 w-100 d-flex justify-content-between align-items-center">
                        <span class="fs-5 fw-bold text-gray-600">Riwayat Jadwal</span>
                        <button class="btn btn-sm btn-info text-white fw-bold" type="button">
                            Export
                            <i class="fa-solid fa-download ps-1"></i>
                        </button>
                    </div>
                    <livewire:riwayat-jadwal/>
                </div>
            </div>
        </div>
    </x-navbar-admin>
@endsection
