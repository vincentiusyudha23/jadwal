<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Absen;
use App\Models\Jadwal;
use App\Enums\IjinEnum;
use App\Enums\AbsenEnum;
use App\Models\MediaImage;
use Illuminate\Support\Str;
use App\Models\GajiKaryawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            'keterangan' => ['required'],
            'image' => ['required', 'array'],
            'image.0' => ['required', 'string'],
            'image.1' => ['nullable', 'string'],
            'work_report' => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,jpeg,png,jpg,gif', 'max:10240']
        ], [], [
            'image' => 'Foto Bukti',
            'image.0' => 'Foto Bukti',
        ]);
        
        try{
            $jadwal = jadwal::find($request->id);
            if($jadwal){
                $image = json_encode($request->image);
                $data = [
                    'status' => $request->status_jadwal,
                    'keterangan' => $request->keterangan,
                    'image' => $image
                ];

                if($request->hasFile('work_report')){
                    $file = $request->work_report;
                    $file_ext = $file->extension();
                    $file_name_with_ext = $file->getClientOriginalName();

                    $file_name = pathinfo($file_name_with_ext, PATHINFO_FILENAME);
                    $file_name = strtolower(Str::slug($file_name));

                    $file_db = $file_name.time().'.'.$file_ext;
                    $folder_path = global_assets_path('assets/work_report');
                    $file->move($folder_path, $file_db);

                    $data['work_report'] = $file_db;
                }

                
                $jadwal->update($data);

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
            $folder_path = global_assets_path('assets/img');
            $image->move($folder_path, $image_db);

            if($image){
                $mediaData = MediaImage::create([
                    'title' => $image_name_with_ext,
                    'path' => $image_db,
                    'size' => null,
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
                'Hari' => $jadwal?->tanggal->translatedFormat('l'),
                'Tanggal' => $jadwal?->tanggal->format('d/m/Y'),
                'Nama' => Auth::user()->name,
                'Tujuan' => $jadwal?->tujuan ?? '',
                'tugas' => $jadwal?->tugas ?? '',
                'status' => statusJadwal($jadwal->status),
                'keterangan' => $jadwal?->keterangan ?? '',
                'Work Report' => $jadwal?->work_report ? asset('assets/work_report/'.$jadwal->work_report) : '',
            ];
        });

        return $export;
    }

    public function absenView()
    {
        return view('karyawan.absen.index');
    }

    public function riwayatAbsen()
    {
        $absens = Auth::user()
            ->absen()
            ->where('type', 1)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($item){
                $absenPulang = Absen::where(['id_karyawan' => $item->id_karyawan, 'type' => 2])->whereDate('tanggal', $item->tanggal)->first();
                $item['waktu_masuk'] = $item->waktuFormat;
                $item['waktu_pulang'] = $absenPulang?->waktuFormat ?? '';
                $item['total'] = !empty($absenPulang) ? $item->created_at->diff($absenPulang->created_at)->format('%H jam %i menit') : '';
                return $item;
            });

        return view('karyawan.absen.riwayat')->with([
            'absens' => $absens
        ]);
    }

    public function storeAbsen(Request $request)
    {
        $this->validate($request, [
            'image_id' => 'required',
            'waktu' => 'required',
            'tanggal' => 'required',
            'lokasi' => 'required'
        ]);
        $user = Auth::user();
        $ijin = $user->ijinKaryawan()->whereDate('from_date', '<=', $request->tanggal)
            ->whereDate('to_date', '>=', $request->tanggal)
            ->exists();

        if ($ijin) {
            return redirect()->back()->with('errors', 'Anda tidak dapat melakukan absen karena sedang dalam masa ijin.');
        }

        $absens = Absen::query()->where('id_karyawan', $user->id)->whereDate('created_at', now())->get();
        
        if($absens->where('type', 2)->isNotEmpty()){
            return redirect()->back()->with('errors', 'Anda sudah melakukan absen hari ini.');
        }

        $type = 1;
        $message = 'masuk';
        if($absens->where('type', 1)->isNotEmpty()){
            $type = 2;
            $message = 'pulang';
        }

        Auth::user()->absen()->create([
            'image' => $request->image_id,
            'waktu' => $request->waktu,
            'tanggal' => $request->tanggal,
            'lokasi' => $request->lokasi,
            'type' => $type
        ]);

        return redirect()->back()->with('success', 'Berhasil melakukan absen ' . $message);
    }

    public function deleteAbsen(Request $request)
    {
        $this->validate($request, [
            'data_id' => 'required'
        ]);

        $user = Auth::user();
        $absen = $user->absen()->find($request->data_id);

        if($absen){
            $absenPulang = $user->absen()->whereDate('created_at', $absen->created_at)->where('type', 2)->first();
            if($absenPulang){
                $absenPulang->delete();
            }
            $absen->delete();

            return response()->json(['type' => 'success', 'msg' => 'Berhasil Menghapus Absen.']);
        }

        return response()->json(['type' => 'errors', 'msg' => 'Gagal Menghapus Absen.']);
    }

    public function detailsAbsen($id)
    {
        $absen = Auth::user()->absen()->findOrFail($id);
        $absens = Auth::user()->absen()->whereDate('created_at', $absen->created_at)->get();
        return view('karyawan.absen.details')->with(['absens' => $absens]);
    }

    public function exportAbsen()
    {
        $user = Auth::user();
        $absens = $user->absen()->where('type', 1)->latest()->get();

        $export = (new FastExcel($absens))->download('data_absen_' . $user->name . '_' . $user->id_karyawan . '.xlsx', function($absen) use ($user){
            $absenPulang = $user->absen()->where(['id_karyawan' => $absen->id_karyawan, 'type' => 2])->first();
            return [
                'Hari' => $absen->tanggal->translatedFormat('l'),
                'Tanggal' => $absen->tanggal->format('d/m/Y'),
                'Nama' => $absen->user->name,
                'ID Karyawan' => $absen->user->id_karyawan,
                'Waktu Masuk' => $absen->waktuFormat,
                'Waktu Pulang' => $absenPulang?->waktuFormat,
                'Total' => !empty($absenPulang) ? $absen->created_at->diff($absenPulang->created_at)->format('%H jam %i menit') : '',
                'Lokasi' => $absen->lokasi
            ];
        });

        return $export;
    }

    public function riwayatGaji()
    {
        $penggajian = Auth::user()->gajiKaryawan()->orderBy('created_at', 'desc')->get();
        return view('karyawan.gaji.riwayat')->with(['penggajian' => $penggajian]);
    }

    public function slipGajiView($id)
    {
        $gaji = Auth::user()->gajiKaryawan()->where('id', $id)->first();
        abort_if(empty($gaji), 404);
        return view('admin.gaji.slip-gaji', [
            'gaji' => $gaji
        ]);
    }

    public function frameSlipGaji($id)
    {
        $gaji = Auth::user()->gajiKaryawan()->where('id', $id)->first();
        abort_if(empty($gaji), 404);
        return view('admin.gaji.frame-gaji', ['gaji' => $gaji]);
    }

    public function exportGaji()
    {
        $user = Auth::user();
        $gaji = $user->gajiKaryawan()->orderBy('created_at', 'desc')->get();
        $export = (new FastExcel($gaji))->download('gaji_karyawan.xlsx', function($gaji) use ($user){
            return [
                'Bulan Gaji' => $gaji->created_at->translatedFormat('F Y'),
                'Tanggal Gaji' => $gaji->created_at->translatedFormat('d/m/Y'),
                'Nama' => $user->name,
                'ID karyawan' => $user->id_karyawan,
                'Jabatan' => $user->karyawan->jabatan,
                'Divisi' => $user->karyawan->divisi,
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

    public function pengajuanIjin()
    {
        return view('karyawan.ijin.index');
    }

    public function storeIjin(Request $request)
    {
        $this->validate($request, [
            'tipe_ijin' => 'required',
            'keterangan' => 'required',
            'from_date' => 'required',
            'to_date' => 'required',
            'surat' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpeg,png,jpg,gif|max:10240'
        ]);

        try {
            DB::beginTransaction();

            $user = Auth::user();

            if($user->ijinKaryawan()
                ->where(function ($query) use ($request) {
                    $query->where(function ($q) use ($request) {
                        $q->where('from_date', '<=', $request->from_date)
                        ->where('to_date', '>=', $request->from_date);
                    })->orWhere(function ($q) use ($request) {
                        $q->where('from_date', '<=', $request->to_date)
                        ->where('to_date', '>=', $request->to_date);
                    })->orWhere(function ($q) use ($request) {
                        $q->where('from_date', '>=', $request->from_date)
                        ->where('to_date', '<=', $request->to_date);
                    });
                })
                ->exists()
            ){
                return redirect()->back()->with('errors', 'Ijin sudah diajukan pada tanggal tersebut');
            }

            if($request->hasFile('surat')){
                $file = $request->surat;
                $file_ext = $file->extension();
                $file_name_with_ext = $file->getClientOriginalName();

                $file_name = pathinfo($file_name_with_ext, PATHINFO_FILENAME);
                $file_name = strtolower(Str::slug($file_name));

                $file_db = $file_name.time().'.'.$file_ext;
                $folder_path = global_assets_path('assets/surat_ijin');
                $file->move($folder_path, $file_db);

                $IjinKaryawan = $user->ijinKaryawan()->create([
                    'from_date' => $request->from_date,
                    'to_date' => $request->to_date ?? $request->from_date,
                    'keterangan' => $request->keterangan,
                    'type' => IjinEnum::getValue($request->tipe_ijin),
                    'surat' => $file_db
                ]);

                sendEmail([
                    'to' => User::where('role', 'admin')->first()?->email,
                    'subject' => 'Pengajuan Ijin Karyawan',
                    'view' => 'ijin',
                    'viewData' => [
                        'ijin' => $IjinKaryawan
                    ]
                ]);

                DB::commit();
                return redirect()->back()->with('success', 'Berhasil Mengajukan Ijin');
            }
            
        } catch (\Exception $e){
            DB::rollBack();
            return redirect()->back()->with('errors', 'Gagal Mengajukan Ijin');
        }
    }

    public function riwayatIjin()
    {
        $ijin = Auth::user()->ijinKaryawan()->orderBy('created_at', 'desc')->get();
        return view('karyawan.ijin.riwayat')->with(['ijins' => $ijin]);
    }

    public function detailsIjin($id)
    {
        $ijin = Auth::user()->ijinKaryawan()->where('id', $id)->first();
        abort_if(empty($ijin), 404);
        return view('karyawan.ijin.details')->with(['ijin' => $ijin]);
    }

    public function deleteIjin(Request $request)
    {
        $this->validate($request, [
            'data_id' => 'required'
        ]);
        
        return Auth::user()->ijinKaryawan()->where('id', $request->data_id)?->first()->delete() ?
            response()->json(['type' => 'success', 'msg' => 'Berhasil Menghapus Ijin.']) :
            response()->json(['type' => 'errors', 'msg' => 'Gagal Menghapus Ijin.']);
    }

    public function exportIjin()
    {
        $user = Auth::user();
        $ijin = $user->ijinKaryawan()->orderBy('created_at', 'desc')->get();
        $export = (new FastExcel($ijin))->download('ijin_karyawan.xlsx', function($ijin) use ($user){
            return [
                'Tanggal Pengajuan' => $ijin->created_at->translatedFormat('d/m/Y'),
                'Nama' => $user->name,
                'ID karyawan' => $user->id_karyawan,
                'Jabatan' => $user->karyawan->jabatan,
                'Divisi' => $user->karyawan->divisi,
                'Tipe Ijin' => IjinEnum::getLabel($ijin->type),
                'Dari Tanggal' => $ijin->from_date->format('d/m/Y'),
                'Sampai Tanggal' => $ijin->to_date->format('d/m/Y'),
                'Keterangan' => $ijin->keterangan,
                'Surat Ijin' => asset('assets/surat_ijin/'.$ijin->surat)
            ];
        });
        return $export;
    }
}
