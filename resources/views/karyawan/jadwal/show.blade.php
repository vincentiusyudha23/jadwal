@extends('layouts.app')

@section('title', 'Jadwal')

@push('styles')
    <style>
        .radio-success:checked{
            background-color: var(--bs-success);
        }
        .radio-warning:checked{
            background-color: var(--bs-warning);
        }
    </style>
@endpush

@section('content')
    <x-navbar-admin :name="Auth::user()->name">
        <div class=" py-4">
            <form id="form-jadwal">
                @csrf
                <input type="hidden" name="id" value="{{ $jadwal->id }}">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title w-100 d-flex justify-content-between align-items-center">
                            <span class="fw-bold fs-5 text-gray-600">Status</span>
                        </div>
                        <div class="row">
                            <div class="col-12 col-lg-6">
                                <div class="w-100 d-flex gap-3 align-items-center con-status">
                                    {{-- <div class="d-flex btn-status align-items-center" style="width: max-content;">
                                        <div class="rounded-circle border border-2 me-2 {{ $jadwal->status == 1 ? 'bg-primary' : '' }}"
                                            style="width: 20px; height: 20px;"></div>
                                        <span>Sudah Selesai</span>
                                    </div>
                                    <div class="d-flex btn-status align-items-center" style="width: max-content;">
                                        <div class="rounded-circle border border-2 me-2 {{ $jadwal->status == 0 ? 'bg-warning' : '' }}"
                                            style="width: 20px; height: 20px;"></div>
                                        <span>Belum Selesai</span>
                                    </div> --}}
                                    <div class="form-check">
                                        <input class="form-check-input radio-success" type="radio" value="1" name="status_jadwal" {{ $jadwal->status == 1 ? 'checked' : '' }} id="flexRadioDefault1">
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            Selesai
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input radio-warning" type="radio" value="0" name="status_jadwal" {{ $jadwal->status == 0 ? 'checked' : '' }} id="flexRadioDefault2">
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
                                <textarea class="form-control" name="keterangan" placeholder="Isi keterangan tentang pekerjaan anda...">{{ $jadwal->keterangan ?? '' }}</textarea>
                            </div>
                        </div>
    
                        <h6 class="text-gray-600 fw-bold">Dokumentasi</h6>
    
                        <hr>
                        @php
                            $images = json_decode($jadwal->image);
                        @endphp
                        <div class="w-100 d-flex gap-2">
                            @forelse ($images as $item)
                                <x-media-upload-v2 :image="$item"/>
                            @empty
                                <x-media-upload-v2 />
                                <x-media-upload-v2 />
                            @endforelse
                            
                        </div>
                        <div class="w-100 mt-4">
                            <button type="submit" id="btn-submit-form" class="btn btn-success w-100">Simpan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </x-navbar-admin>
@endsection

@push('scripts')
    <script>
        $(document).ready(function(){
            $('#form-jadwal').submit(function(e){
                e.preventDefault();
                var formData = new FormData(this);
                var btn = $(this).find('#btn-submit-form');
                $.ajax({
                    type: 'POST',
                    url: "{{ route('karyawan.jadwal.update') }}",
                    processData: false,
                    contentType: false,
                    data: formData,
                    beforeSend: function(){
                        btn.html('<i class="fa-solid fa-spinner fa-spin"></i>').addClass('disabled');
                    },
                    success: function(response){
                        if(response.type == 'success'){
                            toastr.success(response.msg);
                        }
                    },
                    complete: function(){
                        btn.text('Simpan').removeClass('disabled');
                    }
                });
            })
        })
    </script>
@endpush
