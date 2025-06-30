@extends('layouts.app')

@section('title', 'Riwayat Izin')

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
                    <li class="breadcrumb-item active" aria-current="page">Riwayat Izin</li>
                </ol>
            </nav>
            <div class="card mx-w-100 p-2" x-data="riwayatIzin">
                <div class="card-body">
                    <div class="card-title mb-4 w-100 d-flex justify-content-between align-items-center">
                        <span class="fs-5 fw-bold text-gray-600">Riwayat Pengajuan Izin</span>
                    </div>
                    <div class="mb-4 d-flex gap-2 position-relative">
                        <div class="input-group ">
                            <input type="text" x-model="search" class="form-control" name="search" id="search" placeholder="Pencarian...">
                        </div>
                        <div class="d-flex gap-1">
                            <button class="btn btn-sm btn-secondary opacity-75" x-on:click="openDatePicker">
                                <i class="las la-calendar la-lg"></i>
                            </button>
                            <button x-show="isSelectDate" class="btn btn-sm btn-danger" x-on:click="clearSelectedDate">
                                X
                            </button>
                        </div>
                        <input x-ref="dateInput" type="text" class="position-absolute" style="opacity: 0; width: 1px; height: 1px; top: 100%; right: 0;">
                    </div>

                    <div class="d-flex flex-column list-gaji">
                        <template x-if="pengajuanArr.length > 0">
                            <template x-for="(item, index) in pengajuanArr" :key="index">
                                <a :href="'{{ route('admin.ijin.riwayat') }}' +'?tanggal='+ encodeURIComponent(item)" class="text-gray-700 fw-bold w-100 bg-gray-300 p-2 mb-2 rounded-2 list-gaji-item">
                                    <span>Pengajuan Tanggal :</span>
                                    <span x-text="item"></span>
                                </a>
                            </template>
                        </template>
                        <template x-if="pengajuanArr.length === 0">
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
            Alpine.data('riwayatIzin', () => ({
                pengajuan: @json($pengajuan),
                pengajuanArr : [],
                search: '',
                datePicker: null,
                isSelectDate: false,
                localSaved: null,
                get tanggal(){
                    return _.filter(this.pengajuan, (tanggal) => {
                        const formattedTanggal = new Date(tanggal).toLocaleDateString('id-ID', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric'
                        });
                        return formattedTanggal.includes(this.search);
                    });
                },
                openDatePicker(){
                    this.datePicker.open();
                    // Trigger reposition setelah dibuka
                    setTimeout(() => {
                        const calendar = document.querySelector('.flatpickr-calendar');
                        if (calendar) {
                            calendar.style.position = 'absolute';
                            calendar.style.top = '100%';
                            calendar.style.right = '0';
                            calendar.style.marginTop = '5px';
                        }
                    }, 10);
                },
                clearSelectedDate(){
                    this.datePicker.clear();
                    this.pengajuanArr = this.pengajuan
                    this.isSelectDate = false;

                    if(this.localSaved){
                        localStorage.removeItem('selectedDates');
                        this.localSaved = null;
                    }
                },
                init(){
                    const $this = this;
                    this.pengajuanArr = this.pengajuan
                    console.log(this.pengajuan);
                    
                    this.$watch('tanggal', val => {
                        this.pengajuanArr = val;
                    })

                    this.datePicker = flatpickr(this.$refs.dateInput, {
                        mode: 'range',
                        dateFormat: 'd-m-Y',
                        allowInput: true,
                        position: "below", // Posisi calendar
                        appendTo: this.$refs.dateInput.parentElement, // Menempel pada parent
                        static: true,
                        locale: "id",
                        onChange: (selectedDates) => {
                            $this.isSelectDate = true;
                            let start = selectedDates[0];
                            let end = selectedDates[1];
                            
                            let data = $this.pengajuan;

                            if(start && end){
                                localStorage.setItem('selectedDates', JSON.stringify(selectedDates));
                                $this.pengajuanArr = data.filter(item => {
                                    const [day, month, year] = item.split('/');
                                    const itemDate = new Date(year, month - 1, day);

                                    return itemDate >= start && (!end || itemDate <= end);
                                });
                            }
                        },
                        onOpen: () => {
                            const calendar = document.querySelector('.flatpickr-calendar');
                            calendar.style.position = 'absolute';
                            calendar.style.top = '100%';
                            calendar.style.right = '0';
                            calendar.style.marginTop = '5px';
                        }
                    });

                    this.localSaved = JSON.parse(localStorage.getItem('selectedDates'));
                    if(this.localSaved){
                        this.$nextTick(() => {
                            this.datePicker.setDate(this.localSaved, true, 'd-m-Y');
                        });
                    }
                }
            }))
        });
    </script>
@endpush