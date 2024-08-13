@extends('layouts.app')

@section('title', 'Jadwal Karyawan')

@section('content')
    <x-navbar-admin :name="Auth::user()->name">
        <div class=" py-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title w-100 d-flex justify-content-between align-items-center">
                        <span class="fw-bold fs-5 text-gray-600">Status</span>
                        <a href="{{ route('admin.karyawan.jadwal') }}"
                            class="btn btn-sm btn-primary text-white fw-bold">Kembali</a>
                    </div>
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="w-100 d-flex gap-3 align-items-center con-status">
                                <div class="d-flex btn-status align-items-center" style="width: max-content;">
                                    <div class="rounded-circle border border-2 me-2 {{ $jadwal->status == 1 ? 'bg-success' : '' }}"
                                        style="width: 20px; height: 20px;"></div>
                                    <span>Sudah Selesai</span>
                                </div>
                                <div class="d-flex btn-status align-items-center" style="width: max-content;">
                                    <div class="rounded-circle border border-2 me-2 {{ $jadwal->status == 0 ? 'bg-warning' : '' }}"
                                        style="width: 20px; height: 20px;"></div>
                                    <span>Belum Selesai</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-gray-600 fw-bold" for="username">Keterangan</label>
                        <div class="input-group">
                            <textarea class="form-control" readonly>{{ $jadwal->keterangan }}</textarea>
                        </div>
                    </div>

                    <div>
                        <h6 class="text-gray-600 fw-bold">Dokumentasi</h6>
                        <hr>
                        @php
                            $images = json_decode($jadwal->image);
                        @endphp
                        <div class="parent-box">
                            <div class="image-container">
                                @if ($images)
                                    @foreach ($images as $item)
                                        @php
                                            $image = get_data_image($item ?? '');
                                        @endphp
                                        <div class="image-box">
                                            <img src="{{ $image['img_url'] ?? '' }}" alt="{{ $image['alt'] ?? '' }}">
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-navbar-admin>
@endsection
