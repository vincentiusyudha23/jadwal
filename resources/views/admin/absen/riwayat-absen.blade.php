@extends('layouts.app')

@section('title', 'Data Absensi')

@push('styles')
    <style>
        .list-gaji .list-gaji-item:hover{
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
                    <li class="breadcrumb-item active" aria-current="page">Data Absensi</li>
                </ol>
            </nav>

            <div class="card" x-data="riwayatAbsen">
                <div class="card-body">
                    <div class="card-title mb-4">
                        <h5 class="text-gray-700 fw-bold">Data Absensi</h5>
                    </div>

                    <div class="mb-4">
                        <div class="input-group ">
                            <input x-model="search" type="text" class="form-control" name="search" id="search" placeholder="Pencarian...">
                        </div>
                    </div>

                    <div class="d-flex flex-column list-gaji">
                        <template x-if="absensiArr.length > 0">
                            <template x-for="(item, index) in absensiArr" :key="index">
                                <a :href="'{{ route('admin.absen.riwayat.tanggal') }}' +'?tanggal='+ encodeURIComponent(item)" class="text-gray-700 fw-bold w-100 bg-gray-300 p-2 mb-2 rounded-2 list-gaji-item">
                                    <span>Absen Tanggal :</span>
                                    <span x-text="item"></span>
                                </a>
                            </template>
                        </template>
                        <template x-if="absensiArr.length === 0">
                            <div class="w-100 d-flex justify-content-center align-items-center border rounded-2" style="height: 50px;">
                                <h6 class="m-0 p-0">Data Tidak Ditemukan</h6>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </x-navbar-admin>
@endsection

@push('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('riwayatAbsen', () => ({
                absensi: @json($absensi),
                absensiArr : [],
                search: '',
                get tanggal(){
                    return _.filter(this.absensi, (tanggal) => {
                        const formattedTanggal = new Date(tanggal).toLocaleDateString('id-ID', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric'
                        });
                        return formattedTanggal.includes(this.search);
                    });
                },
                init(){
                    this.absensiArr = this.absensi

                    this.$watch('tanggal', val => {
                        this.absensiArr = val;
                    })
                }
            }))
        });
    </script>
@endpush