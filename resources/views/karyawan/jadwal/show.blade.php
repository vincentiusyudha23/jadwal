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
                        <div class="card-title w-100">
                            <div class="w-100 p-2 rounded border bg-secondary bg-opacity-10 d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <span class="fs-6"><strong>Nama :</strong> {{ auth()->user()->name }}</span>
                                    <br>
                                    <span class="fs-6"><strong>ID Karyawan :</strong> {{ auth()->user()->id_karyawan }}</span>
                                </div>
                                <a href="{{ route('karyawan.jadwal.riwayat') }}" class="btn btn-sm btn-primary">
                                    Kembali
                                </a>
                            </div>
                            <span class="fw-bold fs-5 text-gray-600 text-start">Status</span>
                        </div>
                        <div class="row">
                            <div class="col-12 col-lg-6">
                                <div class="w-100 d-flex gap-3 align-items-center con-status">
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

                        <div class="mb-3">
                            <label class="form-label text-gray-600 fw-bold" for="work-report">Work Report</label>
                            <div class="input-group">
                                <input class="form-control" name="work_report" id="work-report" type="file" accept=".pdf,.doc,.docx,.xls,.xlsx,image/*">
                            </div>
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
                        <div class="w-100 d-flex gap-2">
                            @if ($images)
                                @if (count((array)($images ?? [])) > 1)
                                    @foreach ($images as $item)
                                        <x-media-upload-v2 :image="$item"/>
                                    @endforeach
                                @else
                                    @foreach ($images as $item)
                                        <x-media-upload-v2 :image="$item"/>
                                    @endforeach
                                    <x-media-upload-v2/>
                                @endif
                            @else
                                <x-media-upload-v2/>
                                <x-media-upload-v2/>
                            @endif
                        </div>
                        <div class="w-100 mt-4">
                            <button type="submit" id="btn-submit-form" class="btn btn-success w-100 disabled">Simpan</button>
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
                            location.reload();
                        }
                    },
                    error: function(err){
                        if (err.responseJSON && err.responseJSON.errors) {
                            Object.values(err.responseJSON.errors).forEach(messages => {
                                messages.forEach(message => {
                                    toastr.error(message);
                                });
                            });
                        } else {
                            toastr.error('Terjadi kesalahan yang tidak diketahui');
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
