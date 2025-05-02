<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Enums\BulanEnum;
use Carbon\CarbonPeriod;
use App\Enums\DivisiEnum;
use App\Enums\JabatanEnum;
use App\Models\GajiKaryawan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Requests\GajiRequest;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class GajiController extends Controller
{
    public function salary_page()
    {   
        $karyawans = User::hasKaryawan()->latest()->get()->transform(function($user){
            $total_absen = $user->absen()->whereMonth('created_at', now()->month)->where('type', 1)->count();
            $totalIjin = 0;
            $totalSakit = 0;
            $totalCuti = 0;

            $user->ijinKaryawan()->whereMonth('from_date', now()->month)->where('type', 1)->get()->each(function($ijin) use (&$totalIjin){
                $totalIjin += $this->calculateIjinKaryawan($ijin);
            });
            $user->ijinKaryawan()->whereMonth('from_date', now()->month)->where('type', 2)->get()->each(function($ijin) use (&$totalSakit){
                $totalSakit += $this->calculateIjinKaryawan($ijin);
            });
            $user->ijinKaryawan()->whereMonth('from_date', now()->month)->where('type', 3)->get()->each(function($ijin) use (&$totalCuti){
                $totalCuti += $this->calculateIjinKaryawan($ijin);
            });

            return [
                'name' => $user->name,
                'idKaryawan'  => $user->id_karyawan,
                'jabatan'  => JabatanEnum::getItemJabatan($user->karyawan->jabatan ?? ''),
                'divisi' => DivisiEnum::getItemDivisi($user->karyawan->divisi ?? ''),
                'no_rek' => $user->karyawan->nomor_rekening,
                'ijin' => $totalIjin,
                'sakit' => $totalSakit,
                'cuti' => $totalCuti,
                'total_absen' => $total_absen,
                'gaji_pokok' => $user->karyawan->gaji,
            ];
        })->toArray();

        return view('admin.gaji.index', [
            'karyawans' => $karyawans
        ]);
    }

    private function calculateIjinKaryawan($data)
    {
        $start = Carbon::parse($data->from_date);
        $end = Carbon::parse($data->to_date);

        $periodStart = $start->copy();
        $periodEnd = $end->copy();

        $daysInMonth = collect(CarbonPeriod::create($periodStart, $periodEnd))
            ->filter(function ($date){
                return $date->month == Carbon::now()->month &&
                       $date->year == Carbon::now()->year &&
                       !$date->isSunday() && 
                       !$date->isSaturday();
            });

        return $daysInMonth->count();
    }

    public function store(GajiRequest $request)
    {
        $data = $request->validated();

        $karyawan = User::HasKaryawan()->where('id_karyawan', $data['idKaryawan'])->first();

        $gajiKaryawan = $karyawan->gajiKaryawan()->whereMonth('created_at', now()->month)->first();

        if(!empty($gajiKaryawan)){
            return response()->json([
                'status' => 422,
                'message' => 'Gaji Karyawan '.$karyawan->name.' Sudah Dibuat Bulan ini.'
            ], 422);
        }
        
        try{
            DB::beginTransaction();
            unset($data['idKaryawan']);
            $karyawan->gajiKaryawan()->create([
                ...$data,
                'gp_bulanan' => $karyawan->karyawan->gaji
            ]);
            
            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil'
            ], 200);

        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => 422,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function riwayatGaji()
    {
        $penggajian = GajiKaryawan::latest()->get()->groupBy(function($query){
            return $query->created_at->translatedFormat('F Y');
        })->keys()->toArray();

        return view('admin.gaji.riwayat-gaji', [
            'penggajian' => $penggajian
        ]);
    }

    public function detailsGaji()
    {
        $bulan = request('bulan', '');

        abort_if(empty($bulan), 404);

        $bulan = explode(' ', $bulan);

        $fix_bulan = BulanEnum::getBulan($bulan[0]);
        $fix_tahun = $bulan[1];

        $gajiKaryawan = GajiKaryawan::whereMonth('created_at', $fix_bulan)->whereYear('created_at', $fix_tahun)->latest()->get();

        return view('admin.gaji.details-gaji', [
            'gajiKaryawan' => $gajiKaryawan
        ]);
    }

    public function slipGajiView($id)
    {
        $gaji = GajiKaryawan::findOrFail($id);

        return view('admin.gaji.slip-gaji', [
            'gaji' => $gaji
        ]);
    }

    public function frameSlipGaji($id)
    {
        $gaji = GajiKaryawan::findOrFail($id);
        return view('admin.gaji.frame-gaji', ['gaji' => $gaji]);
    }

    public function deleteGaji(Request $request)
    {
        $gaji = GajiKaryawan::find($request->data_id);

        if($gaji){
            $gaji->delete();

            return response()->json([
                'type' => 'success',
                'msg' => 'Berhasil Menghapus Slip Gaji',
            ]);
        }

        return response()->json([
            'type' => 'errors',
            'msg' => 'Slip Gaji Tidak ditemukan',
        ]);
    }

    public function exportGaji()
    {
        $ids = request('ids', []);
        $gaji = GajiKaryawan::whereIn('id', $ids)->orderBy('created_at', 'desc')->get();
        $export = (new FastExcel($gaji))->download('gaji_karyawan.xlsx', function($gaji){
            return [
                'Bulan Gaji' => $gaji->created_at->translatedFormat('F Y'),
                'Tanggal Gaji' => $gaji->created_at->translatedFormat('d/m/Y'),
                'Nama' => $gaji->user->name,
                'ID karyawan' => $gaji->user->id_karyawan,
                'Jabatan' => $gaji->user->karyawan->jabatan,
                'Divisi' => $gaji->user->karyawan->divisi,
                'Total Hari Kerja' => $gaji->total_hari_kerja,
                'Ijin' => $gaji->ijin,
                'Sakit' => $gaji->sakit,
                'Cuti' => $gaji->cuti,
                'Alpa' => $gaji->alpa,
                'Total Absen' => $gaji->total_absen,
                'Gaji Pokok' => $gaji->gp_bulanan,
                'Tunjangan Komunikasi' => $gaji->tj_komunikasi,
                'Tunjangan Keahlian' => $gaji->tj_keahlian,
                'Tunjangan Kesehatan' => $gaji->tj_kesehatan,
                'Total Upah Tetap' => $gaji->total_upah_tetap,
                'Tunjangan Makan' => $gaji->tj_makan,
                'Tunjangan Transportasi' => $gaji->tj_transportasi,
                'Lembur' => $gaji->lembur,
                'Penerimaan Lain-lain' => $gaji->pll,
                'Pinjaman Perusahaan' => $gaji->pp,
                'Lebih Bayar PPH21' => $gaji->lbpph21,
                'Total Upah Non Tetap' => $gaji->total_upah_non_tetap,
                'PPH21' => $gaji->pt_pph21,
                'Pinjaman Perusahaan (Potongan)' => $gaji->pt_pp,
                'BPJS Kesehatan' => $gaji->pt_bpjs_kesehatan,
                'BPJS Ketenagakerjaan' => $gaji->pt_bpjs_kerja,
                'Potongan Absensi' => $gaji->pt_absensi,
                'Potongan Lain-lain' => $gaji->pt_ll,
                'Total Potongan' => $gaji->total_potongan,
                'Total Diterima' => $gaji->total_diterima,
            ];
        });

        return $export;
    }
}
