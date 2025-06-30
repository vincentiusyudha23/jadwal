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

                    <div class="mb-4 position-relative d-flex gap-2">
                        <div class="input-group ">
                            <input x-model="search" type="text" class="form-control" name="search" id="search" placeholder="Pencarian...">
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
                datePicker: null,
                selectedDate: {
                    start: null,
                    end: null
                },
                localSaved: null,
                isSelectDate: false,
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
                    this.selectedDate = null;
                    this.datePicker.clear();
                    this.absensiArr = this.absensi;
                    this.isSelectDate = false;

                    if(this.localSaved){
                        localStorage.removeItem('selectedDates');
                        this.localSaved = null;
                    }
                },
                handlefilter(date){
                    this.isSelectDate = true;
                    let data = this.absensi;
                    let start = new Date(date[0]);
                    let end = new Date(date[1]);
                    this.absensiArr = data.filter(item => {
                        const itemDate = new Date(item);
                        return itemDate >= start && (!end || itemDate <= end);
                    });
                },
                init(){
                    const $this = this;
                    this.absensiArr = this.absensi

                    this.$watch('tanggal', val => {
                        this.absensiArr = val;
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
                            let start = selectedDates[0];
                            let end = selectedDates[1];
                            
                            if(start && end){
                                localStorage.setItem('selectedDates', JSON.stringify(selectedDates));
                                $this.handlefilter(selectedDates);
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
                            this.datePicker.setDate(this.localSaved, true);
                        });
                    }
                }
            }))
        });
    </script>
@endpush