<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dokumen A4</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            width: 22cm;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }
        .a4 {
            width: 21cm;
            height: 10cm;
            background: white;
            border: 1px solid black;
        }

        table{
            border-collapse: collapse;
        }

        .border {
            border: 1px solid black;
        }

        .border-top {
            border-top: 1px solid black; 
        }

        .border-right {
            border-right: 1px solid black;
        }

        .border-left {
            border-left: 1px solid black;
        }

        .border-bottom {
            border-bottom: 1px solid black;
        }

        @media print {
            body {
                width: auto !important;
                height: auto !important;
                display: block !important;
                justify-content: normal !important;
                align-items: normal !important;
                overflow: visible !important;
            }
            .a4 {
                box-shadow: none;
                margin: 0;
                display: block;
            }
        }
        @page {
            size: landscape;
        }
    </style>
</head>
<body>
    <div class="a4">
        {{-- Line 1 --}}
        <table class="border-bottom" style="width: 100%; margin: 0; padding: 0;">
            <thead>
                <tr style="margin: 0; padding: 0;">  
                    <td class="border-right" style="width: 20%; margin: 0; padding: 0;">
                        <div style="
                                display: flex;
                                justify-content: start;
                                padding: 0 10px;
                            ">
                            <img src="{{ assets('img/logo-1.png') }}" alt="img" width="40px" height="40px">
                            <div style="margin: 0 5px;">
                                <strong style="font-size: 10px;">PT. WIRA GRIYA</strong>
                                <small style="font-size: 6px; display: block;">
                                    Jl. Kyai Haji Zainul Arifin No.40, RT.1/RW.5, Tanah Sereal,Kec. Tambora, Kota Jakarta Barat
                                </small>
                            </div>
                        </div>
                    </td>
                    <th style="width: 60%; margin: 0; padding: 0;">
                        <h3>SLIP GAJI</h3>
                    </th>
                    <td class="border-left" style="width: 20%; margin: 0; padding: 0;">
                        <table cellspacing="0" cellpadding="9" style="width:100%; font-size: 10px;">
                            <tr>
                                <td class="border-bottom border-right" ><strong>Tanggal</strong></td>
                                <td class="border-bottom">{{ $gaji->created_at->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <td class="border-right"><strong>No. Rekening</strong></td>
                                <td style="max-width: 50%;">{{ $gaji->user?->karyawan?->nomor_rekening }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </thead>
        </table>

        {{-- Line 2 --}}
        <table  width="100%" class="border-bottom" cellpadding="7" cellspacing="0">
            <thead>
                <tr>
                    <td style="width: 28%;">
                        <table style="font-size: 10px;" cellpadding="2">
                            <thead>
                                <tr>
                                    <td><strong>Nama</strong></td>
                                    <td><strong>:</strong> {{ $gaji->user->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>ID</strong></td>
                                    <td><strong>:</strong> {{ $gaji->user->id_karyawan }}</td>
                                </tr>
                            </thead>
                        </table>
                    </td>
                    <td style="width: 30%;">
                        <table style="font-size: 10px;" cellpadding="2">
                            <thead>
                                <tr>
                                    <td><strong>Jabatan</strong></td>
                                    <td><strong>:</strong> {{ \App\Enums\JabatanEnum::getItemJabatan($gaji->user?->karyawan?->jabatan ?? '') }} </td>
                                </tr>
                                <tr>
                                    <td><strong>Divisi</strong></td>
                                    <td><strong>:</strong> {{ \App\Enums\DivisiEnum::getItemDivisi($gaji->user?->karyawan?->divisi ?? '') }} </td>
                                </tr>
                            </thead>
                        </table>
                    </td>
                    <td style="width: 25%;">
                        <table style="font-size: 10px;" cellpadding="2">
                            <thead>
                                <tr>
                                    <td><strong>Total Hari Kerja</strong></td>
                                    <td><strong>:</strong> {{ $gaji->total_hari_kerja }} </td>
                                </tr>
                                <tr>
                                    <td><strong>Total Absensi</strong></td>
                                    <td><strong>:</strong> {{ $gaji->total_absen }} </td>
                                </tr>
                            </thead>
                        </table>
                    </td>
                    <td style="width: 17%;" align="right">
                        <table style="font-size: 10px;" cellpadding="2" width="50%">
                            <thead>
                                <tr>
                                    <td align="center"><strong>S :</strong> {{ $gaji->sakit }}</td>
                                    <td align="center"><strong>C :</strong> {{ $gaji->cuti }}</td>
                                </tr>
                                <tr>
                                    <td align="center"><strong>I :</strong> {{ $gaji->ijin }}</td>
                                    <td align="center"><strong>A :</strong> {{ $gaji->alpa }}</td>
                                </tr>
                            </thead>
                        </table>
                    </td>
                </tr>
            </thead>
        </table>

        {{-- Line 3 --}}
        <table width="100%" cellpadding="5">
            <thead class="border-bottom">
                <tr style="font-size: 12px;">
                    <th colspan="3" class="border-right" style="width: 32%;">Upah Tetap</th>
                    <th colspan="3" style="width: 36%;">Upah Non-Tetap</th>
                    <th colspan="3" class="border-left" style="width: 32%;">Potongan</th>
                </tr>
            </thead>
            <tbody style="font-size: 10px;">
                <tr>
                    <td style="width: 20%;">Gaji Pokok</td>
                    <td style="width: 2%;">Rp</td>
                    <td align="right" class="border-right" style="width: 10%; padding: 0 5px;">{{ withoutCurrency($gaji->gp_bulanan) }}</td>
                    
                    <td style="width: 24%;">Tunjangan Makan</td>
                    <td style="width: 2%;">Rp</td>
                    <td align="right" class="border-right" style="width: 10%; padding: 0 5px;">{{ withoutCurrency($gaji->tj_makan) }}</td>

                    <td style="width: 20%;">PPH21</td>
                    <td style="width: 2%;">RP</td>
                    <td align="right" style="width: 10%; padding: 0 5px;">{{ withoutCurrency($gaji->pt_pph21) }}</td>
                </tr>

                <tr>
                    <td>Tunjangan Keahlian</td>
                    <td>Rp</td>
                    <td align="right" class="border-right" style="padding: 0 5px;">{{ withoutCurrency($gaji->tj_keahlian) }}</td>

                    <td>Tunjangan Transport</td>
                    <td>Rp</td>
                    <td align="right" class="border-right" style="padding: 0 5px;">{{ withoutCurrency($gaji->tj_transport) }}</td>

                    <td>BPJS Kesehatan</td>
                    <td>Rp</td>
                    <td align="right" style="padding: 0 5px;">{{ withoutCurrency($gaji->pt_bpjs_kesehatan) }}</td>
                </tr>

                <tr>
                    <td>Tunjangan Komunikasi</td>
                    <td>Rp</td>
                    <td align="right" class="border-right" style="padding: 0 5px;">{{ withoutCurrency($gaji->tj_komunikasi) }}</td>

                    <td>Lembur</td>
                    <td>Rp</td> 
                    <td align="right" class="border-right" style="padding: 0 5px;">{{ withoutCurrency($gaji->lembur) }}</td>

                    <td>BPJS Ketenagakerjaan</td>
                    <td>Rp</td>
                    <td align="right" style="padding: 0 5px;">{{ withoutCurrency($gaji->pt_bpjs_kerja) }}</td>
                </tr>

                <tr>
                    <td>Tunjangan Kesehatan</td>
                    <td>Rp</td>
                    <td align="right" class="border-right" style="padding: 0 5px;">{{ withoutCurrency($gaji->tj_kesehatan) }}</td>

                    <td>Penerimaan Lain-lain</td>
                    <td>Rp</td>
                    <td align="right" class="border-right" style="padding: 0 5px;">{{ withoutCurrency($gaji->pll) }}</td>

                    <td>Pinjaman Perusahaan</td>
                    <td>Rp</td>
                    <td align="right" style="padding: 0 5px;">{{ withoutCurrency($gaji->pt_pp) }}</td>
                </tr>

                <tr>
                    <td></td>
                    <td></td>
                    <td class="border-right"></td>

                    <td>Pinjaman Perusahaan</td>
                    <td>Rp</td>
                    <td align="right" class="border-right" style="padding: 0 5px;">{{ withoutCurrency($gaji->pp) }}</td>

                    <td>Potongan Absensi</td>
                    <td>Rp</td>
                    <td align="right" style="padding: 0 5px;">{{ withoutCurrency($gaji->pt_absensi) }}</td>
                </tr>

                <tr>
                    <td></td>
                    <td></td>
                    <td class="border-right"></td>

                    <td>Lebih Bayar PPH21</td>
                    <td>Rp</td>
                    <td class="border-right" align="right" style="padding: 0 5px;">{{ withoutCurrency($gaji->lbpph21) }}</td>

                    <td>Potongan Lain-lain</td>
                    <td>Rp</td>
                    <td align="right" style="padding: 0 5px;">{{ withoutCurrency($gaji->pt_ll) }}</td>
                </tr>

                <tr>
                    <td colspan="3" class="border-right"></td>
                    <td colspan="3" class="border-right"></td>
                    <td colspan="3"></td>
                </tr>

                <tr>
                    <td colspan="3" class="border-right"></td>
                    <td colspan="3" class="border-right"></td>
                    <td colspan="3"></td>
                </tr>

                <tr class="border-top border-bottom">
                    <td>Total Upah Tetap</td>
                    <td>Rp</td>
                    <td class="border-right" align="right">{{ withoutCurrency($gaji->total_upah_tetap) }}</td>

                    <td>Total Upah Non-Tetap</td>
                    <td>Rp</td>
                    <td class="border-right" align="right">{{ withoutCurrency($gaji->total_upah_non_tetap) }}</td>

                    <td>Total Potongan</td>
                    <td>Rp</td>
                    <td align="right">{{ withoutCurrency($gaji->total_potongan) }}</td>
                </tr>
            </tbody>
        </table>

        {{-- Line 4 --}}
        <div style="width: 100%; height: 2cm; position: relative; font-size: 10px;">
            <div style="
                width: 50px; 
                height: 100%;
                position: absolute; 
                left: 20px; 
                display: flex; 
                flex-direction: column;
                align-items: center;
                justify-content: space-between;
                ">
                <span style="padding-top: 5px;">Dibuat</span>
                <span style="padding-bottom: 5px;">HRD</span>
            </div>

            <div style="
                width: 50px; 
                height: 100%;
                position: absolute; 
                left: 190px; 
                display: flex; 
                flex-direction: column;
                align-items: center;
                justify-content: space-between;
                ">
                <span style="padding-top: 5px;">Diterima</span>
                <span style="padding-bottom: 5px;">{{ $gaji->user->name }}</span>
            </div>

            <div style="
                width: 245px;
                position: absolute; 
                bottom: 5px;
                right: 5px; 
                display: flex;
                align-items: center;
                justify-content: space-between;
                ">
                <span>Total Diterima</span>
                <span style="margin-left: 78px;">Rp.</span>
                <span>{{ withoutCurrency($gaji->total_diterima) }}</span>
            </div>
        </div>
    </div>
</body>
</html>