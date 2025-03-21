@extends('layouts.app')

@section('title', 'Penggajian')


@section('content')
    <x-navbar-admin :name="Auth::user()->name">
        <div x-data="penggajian">
            <div class="pt-3 pb-5 px-md-3">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title fw-bold">Input Penggajian Karyawan</h5>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-secondary m-1 p-0" for="karyawan">Karyawan</label>
                                    <div class="input-group">
                                        <select class="form-select select2" x-ref="selectKaryawan" x-model="selectKaryawanId" name="karyawan" data-placeholder="Pilih Karyawan">
                                            <option></option>
                                            @foreach ($karyawans as $karyawan)
                                                <option value="{{ $karyawan['idKaryawan'] }}" >{{ $karyawan['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <x-global-input type="text" label="Bulan" name="bulan" :value="now()->translatedFormat('F Y')" readonly disabled/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <x-global-input type="text" label="ID Karyawan" name="id_karyawan" placeHolder="ID Karyawan" x-model="form.idKaryawan" readonly disabled/>
                            </div>
                            <div class="col-12 col-md-6">
                                <x-global-input type="text" label="Total hari Kerja" name="total_hari_kerja" placeHolder="Total Hari Kerja" x-model="form.total_hari_kerja"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <x-global-input type="text" label="Jabatan" name="jabatan" placeHolder="Jabatan" x-model="form.jabatan" readonly disabled/>
                            </div>
                            <div class="col-12 col-md-6">
                                <x-global-input type="text" label="Divisi" name="divisi" placeHolder="Divisi" x-model="form.divisi" readonly disabled/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <x-global-input type="text" label="Ijin" name="ijin" placeHolder="Ijin" x-model="form.ijin"/>
                            </div>
                            <div class="col-12 col-md-6">
                                <x-global-input type="text" label="Sakit" name="sakit" placeHolder="Sakit" x-model="form.sakit"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <x-global-input type="text" label="Cuti" name="cuti" placeHolder="Cuti" x-model="form.cuti"/>
                            </div>
                            <div class="col-12 col-md-6">
                                <x-global-input type="text" label="Alpa" name="alpa" placeHolder="Alpa" x-model="form.alpa"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <x-global-input type="number" label="Nomor Rekening" name="no_rekening" placeHolder="Nomor Rekening" x-model="form.no_rek" disabled readonly/>
                            </div>
                            <div class="col-12 col-md-6">
                                <x-global-input type="number" label="Total Absensi" name="total_absen" placeHolder="Total Absensi" x-model="form.total_absen"/>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Upah Tetap --}}
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title fw-bold">Upah Tetap</h5>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <x-global-input type="number" name="gp_bulanan" placeHolder="GP Bulanan" x-model="form.gaji_pokok" disabled readonly/>
                            </div>
                            <div class="col-12 col-md-6">
                                <x-global-input type="number" name="tj_komunikasi" placeHolder="TJ Komunikasi" x-model="form.tj_komunikasi"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <x-global-input type="number" name="tj_keahlian" placeHolder="TJ Keahlian" x-model="form.tj_keahlian"/>
                            </div>
                            <div class="col-12 col-md-6">
                                <x-global-input type="number" name="tj_kesehatan" placeHolder="TJ Kesehatan" x-model="form.tj_kesehatan"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <x-global-input type="number" name="total_upah_tetap" placeHolder="Total Upah Tetap" x-model="form.total_upah_tetap" readonly/>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Upah Non-Tetap --}}
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title fw-bold">Upah Non-Tetap</h5>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <x-global-input type="text" name="tj_makan" placeHolder="TJ Makan" x-model="form.tj_makan"/>
                            </div>
                            <div class="col-12 col-md-6">
                                <x-global-input type="text" name="lembur" placeHolder="Lembur" x-model="form.lembur"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <x-global-input type="text" name="tj_transport" placeHolder="TJ Transport" x-model="form.tj_transport"/>
                            </div>
                            <div class="col-12 col-md-6">
                                <x-global-input type="text" name="pll" placeHolder="Penerimaan Lain-lain" x-model="form.pll"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <x-global-input type="text" name="pinjaman_perusahaan" placeHolder="Pinjaman Perusahaan" x-model="form.pp"/>
                            </div>
                            <div class="col-12 col-md-6">
                                <x-global-input type="text" name="_lbpph21" placeHolder="Lebih Bayar PPH21" x-model="form.lbpph21"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <x-global-input type="number" name="total_unt" placeHolder="Total Upah Non-Tetap" x-model="form.total_upah_non_tetap" readonly/>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Potongan --}}
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title fw-bold">Potongan</h5>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <x-global-input type="text" name="_pph21" placeHolder="PPH21" x-model="form.pt_pph21"/>
                            </div>
                            <div class="col-12 col-md-6">
                                <x-global-input type="text" name="pinjaman_perusahaan" placeHolder="Pinjaman Perusahaan" x-model="form.pt_pp"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <x-global-input type="text" name="bpjs_kesehatan" placeHolder="BPJS Kesehatan" x-model="form.pt_bpjs_kesehatan"/>
                            </div>
                            <div class="col-12 col-md-6">
                                <x-global-input type="text" name="potongan_absensi" placeHolder="Potongan Absensi" x-model="form.pt_absensi"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <x-global-input type="text" name="bpjs_ketenagakerjaan" placeHolder="BPJS Ketenagakerjaan" x-model="form.pt_bpjs_kerja"/>
                            </div>
                            <div class="col-12 col-md-6">
                                <x-global-input type="text" name="potongan_lain" placeHolder="Potongan Lain-lain" x-model="form.pt_ll"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <x-global-input type="number" name="total_potongan" placeHolder="Total Potongan" x-model="form.total_potongan"/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h4 class="fw-bold my-2 text-end mx-4" x-text="totalTerima"></h4>
                        <button class="btn btn-success w-100 fw-bold" x-on:click="saveFrom()" :disabled="isLoading">
                            <template x-if="!isLoading">
                                <span>Simpan</span>
                            </template>
                            <template x-if="isLoading">
                                <i class="fa-solid fa-spinner fa-spin"></i>
                            </template>
                        </button>
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
        const defaultForm = {
            idKaryawan : null,
            jabatan : null,
            divisi: null,
            ijin: 0,
            cuti: 0,
            total_hari_kerja : 0,
            sakit: 0,
            alpa: 0,
            no_rek: null,
            total_absen: 0,
            gaji_pokok: 0,
            tj_komunikasi: 0,
            tj_keahlian: 0,
            tj_kesehatan: 0,
            total_upah_tetap: 0,
            tj_makan: 0,
            tj_transport: 0,
            lembur: 0,
            pp: 0,
            pll: 0,
            lbpph21: 0,
            total_upah_non_tetap: 0,
            pt_pph21: 0,
            pt_pp: 0,
            pt_bpjs_kesehatan: 0,
            pt_bpjs_kerja: 0,
            pt_absensi: 0,
            pt_ll: 0,
            total_potongan: 0,
            total_terima: 0
        };
        
        document.addEventListener('alpine:init', () => {
            Alpine.data('penggajian', () => ({
                karyawans: @json($karyawans),
                selectKaryawanId: null,
                form: {
                    ...defaultForm
                },
                totalTerima: 'Total Diterima : Rp 0',
                isLoading: false,
                get karyawanData(){
                    return _.find(this.karyawans, {
                        idKaryawan: this.selectKaryawanId
                    })
                },
                calculateTotalTerima(){
                    let totalTerima = (this.calculateTotalUpah() + this.calculateTotalNonUpah()) - this.calculatePotongan()
                    this.form.total_terima = totalTerima;
                    this.totalTerima = 'Total Diterima : Rp ' + new Intl.NumberFormat('id-ID').format(totalTerima);
                },
                calculateTotalNonUpah(){
                    let total = 
                        (parseFloat(this.form.tj_makan) || 0) +
                        (parseFloat(this.form.tj_transport) || 0) +
                        (parseFloat(this.form.lembur) || 0) +
                        (parseFloat(this.form.pp) || 0) +
                        (parseFloat(this.form.pll) || 0) +
                        (parseFloat(this.form.lbpph21) || 0);

                    this.form.total_upah_non_tetap = total

                    return total
                },
                calculateTotalUpah() {
                    let total = 
                        (parseFloat(this.form.gaji_pokok) || 0) +
                        (parseFloat(this.form.tj_komunikasi) || 0) +
                        (parseFloat(this.form.tj_keahlian) || 0) +
                        (parseFloat(this.form.tj_kesehatan) || 0);
                    
                    this.form.total_upah_tetap = total

                    return total
                },
                calculatePotongan(){
                    let total = 
                        (parseFloat(this.form.pt_pph21) || 0) +
                        (parseFloat(this.form.pt_pp) || 0) +
                        (parseFloat(this.form.pt_bpjs_kesehatan) || 0) +
                        (parseFloat(this.form.pt_bpjs_kerja) || 0) +
                        (parseFloat(this.form.pt_absensi) || 0) +
                        (parseFloat(this.form.pt_ll) || 0);
                    this.form.total_potongan = total
                    return total
                },
                intializeSelect2(){
                    this.selectKaryawan = $(this.$refs.selectKaryawan).select2({
                        theme: "bootstrap-5",
                        selectionCssClass: "select2--small",
                        dropdownCssClass: "select2--small",
                        tags: true,
                    });
    
                    this.selectKaryawan.on('select2:select', async (event) => {
                        let id = event.target.value
                        this.selectKaryawanId = id
                    });
                },
                async saveFrom(){
                    if(this.isLoading) return

                    try{
                        this.isLoading = true

                        const response = await fetch('{{ route("admin.gaji.store") }}', {
                            method: 'POST',
                            headers: {
                                "Content-type": "application/json, charset=UTF-8",
                                "X-Requested-With": "XMLHttpRequest",
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
                            },
                            body: JSON.stringify(this.form)
                        });

                        const data = await response.json();

                        if(data.status === 200){
                            toastr.success(data.message);
                            location.reload();
                        }
                        
                        if(data.errors || data.status === 422){
                            toastr.error(data.message);
                        }
                        
                    }catch (e){
                        console.log(e);
                    }finally{
                        this.isLoading = false
                    }
                },
                init(){
                    this.$watch('karyawanData', val => {
                        let data = this.karyawanData

                        this.form.idKaryawan = data.idKaryawan
                        this.form.jabatan = data.jabatan
                        this.form.divisi = data.divisi
                        this.form.no_rek = data.no_rek
                        this.form.total_absen = data.total_absen || 0
                        this.form.gaji_pokok = data.gaji_pokok || 0
                        this.form.tj_komunikasi = 0
                        this.form.tj_keahlian = 0
                        this.form.tj_kesehatan = 0
                        this.form.tj_makan = 0
                        this.form.tj_transport = 0
                        this.form.lembur = 0
                        this.form.pp = 0
                        this.form.pll = 0
                        this.form.lbpph21 = 0
                        this.form.pt_pph21 = 0
                        this.form.pt_pp = 0
                        this.form.pt_bpjs_kesehatan = 0
                        this.form.pt_bpjs_kerja = 0
                        this.form.pt_absensi = 0
                        this.form.pt_ll = 0

                        this.calculateTotalTerima();
                    });

                    this.$watch('form.gaji_pokok', () => this.calculateTotalTerima());
                    this.$watch('form.tj_komunikasi', () => this.calculateTotalTerima());
                    this.$watch('form.tj_keahlian', () => this.calculateTotalTerima());
                    this.$watch('form.tj_kesehatan', () => this.calculateTotalTerima());
                    this.$watch('form.tj_makan', () => this.calculateTotalTerima());
                    this.$watch('form.tj_transport', () => this.calculateTotalTerima());
                    this.$watch('form.lembur', () => this.calculateTotalTerima());
                    this.$watch('form.pp', () => this.calculateTotalTerima());
                    this.$watch('form.pll', () => this.calculateTotalTerima());
                    this.$watch('form.lbpph21', () => this.calculateTotalTerima());
                    this.$watch('form.pt_pph21', () => this.calculateTotalTerima());
                    this.$watch('form.pt_pp', () => this.calculateTotalTerima());
                    this.$watch('form.pt_bpjs_kesehatan', () => this.calculateTotalTerima());
                    this.$watch('form.pt_bpjs_kerja', () => this.calculateTotalTerima());
                    this.$watch('form.pt_absensi', () => this.calculateTotalTerima());
                    this.$watch('form.pt_ll', () => this.calculateTotalTerima());
    
                    this.intializeSelect2()
                }
            }))
        });
    </script>
@endpush
