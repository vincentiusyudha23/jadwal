<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Enums\BulanEnum;
use App\Models\GajiKaryawan;
use Illuminate\Http\Request;
use App\Http\Requests\GajiRequest;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class GajiController extends Controller
{
    public function salary_page()
    {   
        $karyawans = User::hasKaryawan()->latest()->get()->transform(function($item){
            return [
                'name' => $item->name,
                'idKaryawan'  => $item->id_karyawan,
                'jabatan'  => $item->karyawan->jabatan,
                'divisi' => $item->karyawan->divisi,
                'no_rek' => $item->karyawan->nomor_rekening,
                'total_absen' => rand(1,24),
                'gaji_pokok' => $item->karyawan->gaji,
            ];
        })->toArray();

        return view('admin.gaji.index', [
            'karyawans' => $karyawans
        ]);
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
}
