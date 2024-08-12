@extends('layouts.app')

@section('title', 'Halaman Utama')

@push('styles')
    <style>
    </style>
@endpush

@section('content')
    <x-navbar-admin :name="Auth::user()->name">
        <div class="py-2">
            <nav aria-label="breadcrumb" class="p-0 mt-2">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Halaman Utama</a></li>
                </ol>
            </nav>
            <div class="card w-100 bg-white rounded-3">
                <div class="card-body">
                    <div class="w-100 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="fw-bold fs-5">Jadwal Saat Ini</span>
                        </div>
                        <div>
                            <span>Tanggal : {{ Carbon\Carbon::now()->translatedFormat('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 d-none d-lg-block">
                    <div class="card w-100">
                        <div class="card-body">
                            @include('admin.dashboard.partials.table')
                        </div>
                    </div>
                </div>
                @foreach ($jadwals as $jadwal)
                    <x-card-tugas :jadwal="$jadwal" :karyawans="$karyawans"/>
                @endforeach
            </div>
        </div>
    </x-navbar-admin>
@endsection

@push('scripts')
    <script>
        $(document).on('click', '#simpan_edit', function(){
            var el = $(this);
            el.html('<i class="fa-solid fa-spinner fa-spin"></i>');
            el.addClass('disabled');
        });
    </script>
    @if (session('success'))
        <script>
            toastr.success('{{ session("success") }}');
        </script>
    @endif
@endpush
