@extends('layouts.app')

@section('title', 'Riwayat Gaji')

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
                    <li class="breadcrumb-item active" aria-current="page">Riwayat Gaji</li>
                </ol>
            </nav>
            <div class="card mx-w-100 p-2" x-data="riwayatGaji">
                <div class="card-body">
                    <div class="card-title mb-4 w-100 d-flex justify-content-between align-items-center">
                        <span class="fs-5 fw-bold text-gray-600">Riwayat Gaji</span>
                    </div>
                    <div class="mb-4">
                        <div class="input-group ">
                            <input type="text" x-model="search" class="form-control" name="search" id="search" placeholder="Pencarian...">
                        </div>
                    </div>

                    <div class="d-flex flex-column list-gaji">
                        <template x-if="penggajianArr.length > 0">
                            <template x-for="(item, index) in penggajianArr" :key="index">
                                <a :href="'{{ route('admin.gaji.details') }}' +'?bulan='+ encodeURIComponent(item)" class="text-gray-700 fw-bold w-100 bg-gray-300 p-2 rounded-2 list-gaji-item">
                                    <span>Penggajian :</span>
                                    <span x-text="item"></span>
                                </a>
                            </template>
                        </template>
                        <template x-if="penggajianArr.length === 0">
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
            Alpine.data('riwayatGaji', () => ({
                penggajian: @json($penggajian),
                penggajianArr : [],
                search: '',
                get bulanGaji(){
                    return _.filter(this.penggajian, (bulan) => bulan.toLowerCase().includes(this.search.toLowerCase()));
                },
                init(){
                    this.penggajianArr = this.penggajian

                    this.$watch('bulanGaji', val => {
                        this.penggajianArr = val;
                    })
                }
            }))
        });
    </script>
@endpush