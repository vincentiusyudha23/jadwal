<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Jadwal;
use App\Models\MediaImage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rules\Password;

class KaryawanController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('role:karyawan');
    // }

    public function index()
    {
        $jadwals = Jadwal::where('id_karyawan', Auth::user()->id)->whereDate('tanggal', Carbon::now())->orderBy('created_at', 'desc')->get();

        return view('karyawan.dashboard.index', compact('jadwals'));
    }

    public function show_jadwal($id)
    {
        $jadwal = Jadwal::find($id);

        return view('karyawan.jadwal.show', compact('jadwal'));
    }

    public function update_jadwal(Request $request)
    {
        $this->validate($request, [
            'id' => ['required'],
            'status_jadwal' => ['required','max:2'],
            'keterangan' => ['nullable'],
            'image' => ['nullable']
        ]);
        
        try{
            $jadwal = jadwal::find($request->id);
            if($jadwal){
                $image = json_encode($request->image);
                $jadwal->update([
                    'status' => $request->status_jadwal,
                    'keterangan' => $request->keterangan,
                    'image' => $image
                ]);

                return response()->json([
                    'type' => 'success',
                    'msg' => 'Berhasil Memperbarui Jadwal'
                ]);
            }
        }catch(\Exception $e){
            return response()->json([
                'type' => 'errors',
                'msg' => $e->getMessage()
            ]);
        }
    }

    public function uploaderImage(Request $request)
    {
        $this->validate($request, [
            'file' => ['nullable', 'mimes:jpg,jpeg,png,gif,webp,pdf', 'max:25000']
        ]);

        if($request->hasFile('file')){
            $image = $request->file;

            $image_extension = $image->extension();
            $image_name_with_ext = $image->getClientOriginalName();

            $image_name = pathinfo($image_name_with_ext, PATHINFO_FILENAME);
            $image_name = strtolower(Str::slug($image_name));

            $image_db = $image_name.time().'.'.$image_extension;

            $image->storeAs('public/media', $image_db);

            if($image){
                $mediaData = MediaImage::create([
                    'title' => $image_name_with_ext,
                    'path' => $image_db,
                    'size' => formatBytes($image->getSize()),
                    'user_type' => 0,
                    'user_id' => Auth::user()->id
                ]);

                if($mediaData){
                    return response()->json([
                        'type' => 'success',
                        'id' => $mediaData->id
                    ], 200);
                }
            }
        }
    }

    public function profile()
    {
        return view('karyawan.auth.profile');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validateWithBag('updatePassword', [
            'username' => ['required'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
            'enc_password' => Crypt::encryptString($validated['password'])
        ]);

        return back()->with('success', 'Berhasil Memperbarui Password');
    }

    public function riwayat_jadwal()
    {
        $jadwals = Jadwal::where('id_karyawan', Auth::user()->id)->orderBy('tanggal', 'desc')->get();

        return view('karyawan.jadwal.riwayat', compact('jadwals'));
    }

    public function export_jadwal()
    {
        $jadwal = Jadwal::where('id_karyawan', Auth::user()->id)->orderBy('tanggal', 'desc')->get();

        $export = (new FastExcel($jadwal))->download('jadwal.xlsx', function($jadwal){
            return [
                'Hari' => $jadwal->tanggal->translatedFormat('l'),
                'Tanggal' => $jadwal->tanggal->format('d/m/Y'),
                'Nama' => Auth::user()->name,
                'Tujuan' => $jadwal->tujuan,
                'tugas' => $jadwal->tugas,
                'status' => statusJadwal($jadwal->status),
                'keterangan' => $jadwal->keterangan
            ];
        });

        return $export;
    }
}
