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
        <div class="container-fluid py-4">
            <div class="card min-vh-100 mx-w-100 p-2">
                <div class="card-body">
                    <div class="card-title mb-4 w-100 d-flex justify-content-between align-items-center">
                        <span class="fs-5 fw-bold text-gray-600">Riwayat Jadwal</span>
                        <button class="btn btn-sm btn-info text-white fw-bold" type="button">
                            Export
                            <i class="fa-solid fa-download ps-1"></i>
                        </button>
                    </div>
                    <div class="card-content">
                        <div class="w-100 d-flex flex-column flex-sm-row justify-content-end align-items-center mb-3">
                            <div class="mx-2 mb-2 mb-sm-0 date-input">
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon3">From</span>
                                    <input type="date" class="form-control" id="basic-url" name="date_from"
                                        aria-describedby="basic-addon3 basic-addon4">
                                </div>
                            </div>
                            <div class="btn btn-sm bg-gray-200 d-none d-md-block">
                                <span class="fw-bold">-</span>
                            </div>
                            <div class="mx-2 mb-2 mb-sm-0 date-input">
                                <div class="input-group">
                                    <input type="date" class="form-control" name="date_to"
                                        aria-label="Recipient's username" aria-describedby="basic-addon2">
                                    <span class="input-group-text" id="basic-addon2">To</span>
                                </div>
                            </div>
                            <div class="date-input button">
                                <button class="btn btn-sm btn-success">Cari</button>
                                <a href="javascript:void(0)" class="btn btn-sm btn-danger">
                                    <i class="fa-solid fa-rotate-left"></i>
                                </a>
                            </div>
                        </div>
                        <div class="w-100 d-flex flex-column gap-3 list-riwayat">
                            <a href="#" class="text-gray-700 fw-bold w-100 bg-gray-300 p-2 rounded-2 list-riwayat-item">
                                Jadwal Tanggal : 28/09/2024
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-navbar-admin>
@endsection
