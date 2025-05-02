@extends('layouts.app')

@section('title', 'Riwayat Izin')

@section('content')
    <x-navbar-admin :name="Auth::user()->name">
        <div class="py-2">
            <nav aria-label="breadcrumb" class="p-0 mt-2">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Halaman Utama</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.ijin') }}">Riwayat Izin</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ str_replace('/', '-', request('tanggal', '')) }}</li>
                </ol>
            </nav>
            <div class="card">
                <div class="card-body">
                    <div class="card-title mb-3 d-flex justify-content-between align-items-center">
                        <h4 class="fw-bold">Riwayat Pengajuan Izin</h4>
                        <a href="{{ route('admin.ijin.export', ['ids' => $ijins?->pluck('id')->toArray()]) }}" class="btn btn-sm btn-success">Export</a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="datatable" style="width: 100%">
                            <thead>
                                <tr>
                                    <th class="text-center">Hari</th>
                                    <th class="text-center">Tanggal</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">ID</th>
                                    <th class="text-center">Tipe</th>
                                    <th class="text-center">Dari</th>
                                    <th class="text-center">Sampai</th>
                                    <th class="text-center">Total</th>
                                    <th>Keterangan</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ijins as $item)
                                    <tr>
                                        <td class="text-center">{{ $item->created_at->translatedFormat('l') }}</td>
                                        <td class="text-center">{{ $item->created_at->format('d/m/Y') }}</td>
                                        <td class="text-center">{{ $item->user->name }}</td>
                                        <td class="text-center">{{ $item->user->id_karyawan }}</td>
                                        <td class="text-center">{{ \App\Enums\IjinEnum::getLabel($item->type) }}</td>
                                        <td class="text-center">{{ $item->from_date->format('d/m/Y') }}</td>
                                        <td class="text-center">{{ $item->to_date->format('d/m/Y') }}</td>
                                        <td class="text-center">{{ kalkulasiHariIjin($item) }} Hari</td>
                                        <td>{{ $item->keterangan }}</td>
                                        <td class="text-center">
                                            <div class="w-100 d-flex gap-2 justify-content-center align-items-center">
                                                <a href="{{ route('admin.ijin.details', $item->id) }}" class="btn btn-sm btn-success" type="button">
                                                    <i class="fa-solid fa-eye text-white"></i>
                                                </a>
                                                <x-button-delete table="datatable" :data_id="$item->id" :route="route('admin.ijin.delete')" method="POST"/>
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
    </x-navbar-admin>
@endsection