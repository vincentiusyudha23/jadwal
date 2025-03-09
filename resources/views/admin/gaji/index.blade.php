@extends('layouts.app')

@section('title', 'Penggajian')


@section('content')
    <x-navbar-admin :name="Auth::user()->name">
        <div class="pt-3 pb-5 px-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Input Penggajian Karyawan</h5>
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <div class="form-group">
                            <select class="form-select select2" name="karyawan" data-placeholder="Pilih Karyawan">
                                    <option></option>
                            </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <x-global-input type="date" name="tanggal"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <x-global-input type="text" name="id_karyawan" placeHolder="ID Karyawan"/>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <x-global-input type="text" name="total_hari_kerja" placeHolder="Total Hari Kerja"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <x-global-input type="text" name="jabatan" placeHolder="Jabatan"/>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <x-global-input type="text" name="divisi" placeHolder="Divisi"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <x-global-input type="text" name="ijin" placeHolder="Ijin"/>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <x-global-input type="text" name="sakit" placeHolder="Sakit"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <x-global-input type="text" name="cuti" placeHolder="Cuti"/>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <x-global-input type="text" name="alpa" placeHolder="Alpa"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <x-global-input type="number" name="no_rekening" placeHolder="Nomor Rekening"/>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <x-global-input type="number" name="total_absen" placeHolder="Total Absensi"/>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Upah Tetap --}}
            <div class="card mt-2">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Upah Tetap</h5>
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <x-global-input type="text" name="gp_bulanan" placeHolder="GP Bulanan"/>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <x-global-input type="text" name="tj_komunikasi" placeHolder="TJ Komunikasi"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <x-global-input type="text" name="tj_keahlian" placeHolder="TJ Keahlian"/>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <x-global-input type="text" name="tj_kesehatan" placeHolder="TJ Kesehatan"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <x-global-input type="text" name="total_upah_tetap" placeHolder="Total Upah Tetap"/>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Upah Non-Tetap --}}
            <div class="card mt-2">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Upah Non-Tetap</h5>
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <x-global-input type="text" name="tj_makan" placeHolder="TJ Makan"/>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <x-global-input type="text" name="lembur" placeHolder="Lembur"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <x-global-input type="text" name="tj_transport" placeHolder="TJ Transport"/>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <x-global-input type="text" name="pll" placeHolder="Penerimaan Lain-lain"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <x-global-input type="text" name="pinjaman_perusahaan" placeHolder="Pinjaman Perusahaan"/>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <x-global-input type="text" name="_lbpph21" placeHolder="Lebih Bayar PPH21"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <x-global-input type="number" name="total_unt" placeHolder="Total Upah Non-Tetap"/>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Potongan --}}
            <div class="card mt-2">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Potongan</h5>
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <x-global-input type="text" name="_pph21" placeHolder="PPH21"/>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <x-global-input type="text" name="pinjaman_perusahaan" placeHolder="Pinjaman Perusahaan"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <x-global-input type="text" name="bpjs_kesehatan" placeHolder="BPJS Kesehatan"/>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <x-global-input type="text" name="potongan_absensi" placeHolder="Potongan Absensi"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <x-global-input type="text" name="bpjs_ketenagakerjaan" placeHolder="BPJS Ketenagakerjaan"/>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <x-global-input type="text" name="potongan_lain" placeHolder="Potongan Lain-lain"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <x-global-input type="number" name="total_potongan" placeHolder="Total Potongan"/>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-2">
                <div class="card-body">
                    <button class="btn btn-success w-100 fw-bold">Simpan</button>
                </div>
            </div>
        </div>
    </x-navbar-admin>
@endsection

@push('scripts')
    <script>
        $(document).ready(function(){
            $('.select2').each(function() {
                var parent = $(this).data('bs-parent');

                if(parent){
                    $(this).select2({
                        theme: "bootstrap-5",
                        selectionCssClass: "select2--small",
                        dropdownCssClass: "select2--small",
                        tags: true,
                        dropdownParent: $(parent)
                    });
                }else{
                    $(this).select2({
                        theme: "bootstrap-5",
                        selectionCssClass: "select2--small",
                        dropdownCssClass: "select2--small",
                        tags: true
                    });
                }
            });
        })
    </script>
@endpush