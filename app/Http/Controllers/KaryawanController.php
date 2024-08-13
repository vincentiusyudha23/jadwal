<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Jadwal;
use App\Models\MediaImage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
