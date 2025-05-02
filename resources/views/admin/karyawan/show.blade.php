@extends('layouts.app')

@section('title', 'Jadwal Karyawan')

@section('content')
    <x-navbar-admin :name="Auth::user()->name">
        <div class=" py-4">
            <form id="form-jadwal">
                @csrf
                <input type="hidden" name="id" value="{{ $jadwal->id }}">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title w-100">
                            <div class="w-100 p-2 rounded border bg-secondary bg-opacity-10 d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <span class="fs-6"><strong>Nama :</strong> {{ $jadwal->user->name }}</span>
                                    <br>
                                    <span class="fs-6"><strong>ID Karyawan :</strong> {{ $jadwal->user->id_karyawan }}</span>
                                </div>
                                <a href="{{ route('admin.history') }}" class="btn btn-sm btn-primary">
                                    Kembali
                                </a>
                            </div>
                            <span class="fw-bold fs-5 text-gray-600 text-start">Status</span>
                        </div>
                        <div class="row">
                            <div class="col-12 col-lg-6">
                                <div class="w-100 d-flex gap-3 align-items-center con-status">
                                    <div class="form-check">
                                        <input class="form-check-input radio-success" type="radio" value="1" name="status_jadwal" {{ $jadwal->status == 1 ? 'checked' : 'disabled' }} id="flexRadioDefault1">
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            Selesai
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input radio-warning" type="radio" value="0" name="status_jadwal" {{ $jadwal->status == 0 ? 'checked' : 'disabled' }} id="flexRadioDefault2">
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            Belum Selesai
                                        </label>
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
                                <textarea class="form-control" name="keterangan" readonly placeholder="Isi keterangan tentang pekerjaan anda...">{{ $jadwal->keterangan ?? '' }}</textarea>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-gray-600 fw-bold" for="work-report">Work Report</label>
                        </div>

                        @if ($jadwal?->work_report)
                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-lg-4 col-md-8 col-12">
                                        <div class="d-flex flex-column justify-content-center align-items-center">
                                            <div class="card d-inline-block">
                                                <div class="card-body">
                                                    <i class="las la-file-alt fs-1"></i>
                                                </div>
                                            </div>
                                            <a href="{{ asset('assets/work_report/'.$jadwal->work_report) }}" class="text-break" target="_blank">{{ $jadwal->work_report }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
    
                        <h6 class="text-gray-600 fw-bold">Dokumentasi</h6>
    
                        <hr>
                        @php
                            $images = json_decode($jadwal->image, true);
                        @endphp
                        <div class="w-100 row">
                            <div class="col-12 col-md-6">
                                <div class="row">
                                    @foreach ($images ?? [] as $item)
                                        @php
                                            $image = get_data_image($item ?? '');
                                        @endphp
                                        <div class="col-lg-4 col-md-6 col-12 mb-3">
                                            <div class="border rounded p-2" style="height: 200px;">
                                                <a class="w-100 h-100" href="{{ $image['img_url'] ?? '#' }}" target="_blank">
                                                    <img src="{{ $image['img_url'] ?? '' }}" alt="img" class="w-100 h-100 object-fit-contain">
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </x-navbar-admin>
@endsection
