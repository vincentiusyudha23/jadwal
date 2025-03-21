<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\GajiRequest;
use Illuminate\Support\Facades\DB;

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
}
