@extends('layouts.app')

@section('title', 'Gaji Details')

@section('content')
    <x-navbar-admin :name="Auth::user()->name">
        <div class="py-2">
            <nav aria-label="breadcrumb" class="p-0 mt-2">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Halaman Utama</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.gaji.riwayat') }}">Riwayat Gaji</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ request('bulan', '') }}</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center p-2 mb-3">
                        <h5 class="text-gray-700 fw-bold">
                            Riwayat Penggajian : {{ request('bulan', '') }}
                        </h5>
                        <a href="{{ route('admin.gaji.export', ['ids' => $gajiKaryawan?->pluck('id')->toArray()]) }}" class="btn btn-sm btn-success">Export</a>
                    </div>

                    <div class="card-content">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="datatable">
                                <thead>
                                    <tr>
                                        <th class="text-start">Nama</th>
                                        <th class="text-center">ID</th>
                                        <th>Jabatan</th>
                                        <th>Divisi</th>
                                        <th class="text-start">No. Rekening</th>
                                        <th class="text-start">Total Diterima</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($gajiKaryawan as $gaji)
                                        <tr>
                                            <td class="text-start">{{ $gaji->user->name }}</td>
                                            <td class="text-center">{{ $gaji->user->id_karyawan }}</td>
                                            <td>{{ \App\Enums\JabatanEnum::getItemJabatan($gaji->user->karyawan->jabatan ?? '') }}</td>
                                            <td>{{ \App\Enums\DivisiEnum::getItemDivisi($gaji->user->karyawan->divisi ?? '') }}</td>
                                            <td class="text-start">{{ $gaji->user->karyawan->nomor_rekening }}</td>
                                            <td class="text-start">{{ currency($gaji->total_diterima) }}</td>
                                            <td>
                                                <div class="w-100 d-flex gap-2 justify-content-center align-items-center">
                                                    <a href="{{ route('admin.gaji.slip-gaji.view', ['id' => $gaji->id]) }}" class="btn btn-sm btn-success">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </a>
                                                    <x-button-delete table="datatable" :data_id="$gaji->id" :route="route('admin.gaji.slip-gaji.delete')" method="POST"/>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-navbar-admin>
@endsection