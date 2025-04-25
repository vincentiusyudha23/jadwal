@extends('layouts.app')

@section('title', 'Riwayat Gaji')

@section('content')
    <x-navbar-admin :name="Auth::user()->name">
        <div class="py-2">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center p-2 mb-3">
                        <h5 class="text-gray-700 fw-bold">
                            Riwayat Penggajian
                        </h5>

                        <a href="{{ route('karyawan.gaji.export') }}" class="btn btn-sm btn-success fw-bold">Export</a>
                    </div>

                    <div class="card-content">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="datatable">
                                <thead>
                                    <tr>
                                        <th>Hari</th>
                                        <th>Tanggal</th>
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
                                    @foreach ($penggajian as $gaji)
                                        <tr>
                                            <td>{{ $gaji->created_at->translatedFormat('l') }}</td>
                                            <td>{{ $gaji->created_at->format('d/m/Y') }}</td>
                                            <td class="text-start">{{ $gaji->user->name }}</td>
                                            <td class="text-center">{{ $gaji->user->id_karyawan }}</td>
                                            <td>{{ \App\Enums\JabatanEnum::getItemJabatan($gaji->user?->karyawan?->jabatan ?? '') }}</td>
                                            <td>{{ \App\Enums\DivisiEnum::getItemDivisi($gaji->user?->karyawan?->divisi ?? '') }}</td>
                                            <td class="text-start">{{ $gaji->user->karyawan->nomor_rekening }}</td>
                                            <td class="text-start">{{ currency($gaji->total_diterima) }}</td>
                                            <td>
                                                <div class="w-100 d-flex gap-2 justify-content-center align-items-center">
                                                    <a href="{{ route('karyawan.gaji.slip-gaji.view', ['id' => $gaji->id]) }}" class="btn btn-sm btn-success">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </a>
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