<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Jadwal;
use App\Models\Karyawan;
use App\Enums\DivisiEnum;
use App\Enums\JabatanEnum;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Imports\KaryawanImport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Jobs\ImportDataKaryawan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller
{
    
    // public function __construct()
    // {
    //     $this->middleware('role:admin');
    // }
    
    public function index()
    {
        $jadwals = Jadwal::whereDate('tanggal', Carbon::now())->orderBy('created_at', 'desc')->get();
        $karyawans = User::hasKaryawan()->select('name', 'id')->latest()->get();
        return view('admin.dashboard.index', compact('jadwals', 'karyawans'));
    }

    public function karyawan()
    {
        $karyawans = User::hasKaryawan()->latest()->get();
       
        $divisi = DivisiEnum::getKeyDivisi();
        $jabatan = JabatanEnum::getKeyJabatan();
        
        $divisi_db = Karyawan::select('divisi')->distinct()->pluck('divisi')->toArray();
        $jabatan_db = Karyawan::select('jabatan')->distinct()->pluck('jabatan')->toArray();

        foreach($divisi_db as $val){
            if(!in_array(strtolower($val), $divisi)){
                $divisi[] = $val;
            }
        }

        foreach($jabatan_db as $val){
            if(!in_array(strtolower($val), $jabatan)){
                $jabatan[] = $val;
            }
        }

        return view('admin.karyawan.index', compact('karyawans', 'divisi', 'jabatan'));
    }

    public function store_karyawan(Request $request)
    {
        $this->validate($request, [
            'nama' => ['required'],
            'id_karyawan' => ['required', 'unique:users,id_karyawan'],
            'username' => ['required', 'unique:users,username'],
            'password' => ['required', Password::min(8)],
            'jabatan' => ['required', 'string'],
            'divisi' => ['required'],
            'nomor_rekening' => ['required'],
            'email' => ['required', 'email'],
            'gaji' => ['required', 'numeric']
        ]);

        try{
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->nama,
                'id_karyawan' => $request->id_karyawan,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'enc_password' => Crypt::encryptString($request->password),
                'role' => 'karyawan',
                'email' => $request->email
            ]);

            Karyawan::create([
                'id_karyawan' => $user->id, 
                'name' => $request->nama,
                'jabatan' => $request->jabatan,
                'divisi' => $request->divisi,
                'nomor_rekening' => $request->nomor_rekening,
                'gaji' => $request->gaji
            ]);

            $user->assignRole('karyawan');

            DB::commit();

            return redirect()->back()->with('success', 'Berhasil Menambah Data.');

        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->back()->with('errors', 'Terjadi Kesalahan.');
        }
    }

    public function update_karyawan(Request $request)
    {
        $this->validate($request, [
            'id' => ['required'],
            'nama' => ['required'],
            'id_karyawan' => ['required'],
            'username' => ['required'],
            'password' => ['required', Password::min(8)],
            'jabatan' => ['required', 'string'],
            'divisi' => ['required'],
            'nomor_rekening' => ['required'],
            'email' => ['required', 'email'],
            'gaji' => ['required', 'numeric']
        ]);

        try{
            DB::beginTransaction();

            $user = User::find($request->id);

            $user->update([
                'name' => $request->nama,
                'id_karyawan' => $request->id_karyawan,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'enc_password' => Crypt::encryptString($request->password),
                'email' => $request->email
            ]);

            $user->karyawan?->update([
                'jabatan' => $request->jabatan,
                'divisi' => $request->divisi,
                'nomor_rekening' => $request->nomor_rekening,
                'gaji' => $request->gaji
            ]);

            $karyawans = User::where('role', 'karyawan')->latest()->get();

            $markup = View::make('admin.karyawan.partials.table', compact('karyawans'))->render();
            
            DB::commit();

            return response()->json([
                'type' => 'success',
                'markup' => $markup 
            ]);

        }catch(\Exception $e){
            
            DB::rollBack();

            return response()->json([
                'type' => 'errors',
                'msg' => $e->getMessage()
            ]);
        }
    }

    public function delete_karyawan(Request $request)
    {
        $id_karyawan = $request->data_id;

        $user = User::find($id_karyawan);

        if($user){
            $user->removeRole('karyawan');
            $user->delete();

            $karyawans = User::where('role', 'karyawan')->latest()->get();

            $markup = View::make('admin.karyawan.partials.table', compact('karyawans'))->render();

            return response()->json([
                'type' => 'success',
                'msg' => 'Berhasil Menghapus Data Karyawan',
                'markup' => $markup
            ]);
        }else{
            return response()->json([
                'type' => 'errors',
                'msg' => 'Data Karyawan Tidak Ditemukan'
            ]);
        }
    }

    public function view_id_card($id)
    {
        $karyawan = User::findOrFail($id);
        $image = assets('img/logo-1.png');

        return view('admin.karyawan.partials.card-id', compact('karyawan', 'image'));
    }

    public function downloadCardId($id)
    {
        $karyawan = User::findOrFail($id);

        $pdf = Pdf::loadView('admin.karyawan.partials.card-id', [
            'karyawan' => $karyawan,
            'image' => public_path('/assets/img/logo-1.png')
        ]);

        $pdfPath = global_assets_path("assets/img/id-card-{$karyawan->id_karyawan}.pdf");
        $pdf->save($pdfPath);

        return response()->download($pdfPath)->deleteFileAfterSend(true);
    }

    public function jadwal()
    {
        $karyawans = User::hasKaryawan()->select('name', 'id')->latest()->get();
        $jadwals = Jadwal::latest()->get();
        return view('admin.karyawan.jadwal', compact('karyawans', 'jadwals'));
    }

    public function store_jadwal(Request $request)
    {
        $this->validate($request, [
            'karyawan' => ['required','max:8'],
            'tanggal' => ['required'],
            'tujuan' => ['required', 'max:255'],
            'tugas' => ['required'],
            'waktu' => ['required']
        ]);

        try{
            $user = User::find($request->karyawan);

            if($user){
                Jadwal::create([
                    'id_karyawan' => $user->id,
                    'tanggal' => $request->tanggal,
                    'tujuan' => $request->tujuan,
                    'tugas' => $request->tugas,
                    'waktu' => $request->waktu
                ]);

                return redirect()->back()->with('success', 'Berhasil Membuat Jadwal');
            } else {
                return redirect()->back()->with('errors', 'Karyawan tidak ditemukan');
            }
        }catch(\Exception $e){
            dd($e->getMessage());
        }
    }

    public function update_jadwal(Request $request)
    {
        $this->validate($request, [
            'jadwal' => ['required', 'max:8'],
            'karyawan' => ['required','max:8'],
            'tanggal' => ['required'],
            'tujuan' => ['required', 'max:255'],
            'tugas' => ['required'],
            'waktu' => ['required']
        ]);

        try{
            $jadwal = Jadwal::find($request->jadwal);

            $user = User::find($request->karyawan);

            if($user && $jadwal){
                $jadwal->update([
                    'id_karyawan' => $user->id,
                    'tanggal' => $request->tanggal,
                    'tujuan' => $request->tujuan,
                    'tugas' => $request->tugas,
                    'waktu' => $request->waktu
                ]);

                return redirect()->back()->with('success', 'Berhasil Memperbarui Jadwal');
            } else {
                return redirect()->back()->with('errors', 'Karyawan/Jadwal tidak ditemukan');
            }
        }catch(\Exception $e){
            dd($e->getMessage());
        }
    }

    public function delete_jadwal(Request $request)
    {
        $id_jadwal = $request->data_id;

        $jadwal = Jadwal::find($id_jadwal);

        if($jadwal){
            $jadwal->delete();

            $jadwals = Jadwal::latest()->get();
            $karyawans = User::where('role', 'karyawan')->select('name', 'id')->latest()->get();

            // $markup = View::make('admin.karyawan.partials.tabel-jadwal', compact('jadwals', 'karyawans'))->render();

            return response()->json([
                'type' => 'success',
                'msg' => 'Berhasil Menghapus Jadwal Karyawan',
            ]);
        }

        return response()->json([
            'type' => 'errors',
            'msg' => 'Jadwal Tidak ditemukan',
        ]);
    }

    public function show_jadwal($id)
    {
        $jadwal = Jadwal::find($id);

        return view('admin.karyawan.show', compact('jadwal'));
    }

    public function profile()
    {
        return view('admin.auth.profile');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validateWithBag('updatePassword', [
            'username' => ['required'],
            'password' => ['nullable',Password::defaults(), 'confirmed'],
            'email' => ['required']
        ]);
        
        $data = [
            'email' => $validated['email']
        ];

        if($request->password && isset($validated['password'])){
            $data = [
                ...$data,
                'password' => Hash::make($validated['password']),
                'enc_password' => Crypt::encryptString($validated['password'])
            ];
        }

        $request->user()->update($data);

        return back()->with('success', 'Berhasil Memperbarui Akun');
    }

    public function history()
    {
        return view('admin.history.index');
    }

    public function getHistory($tanggal)
    {
        $jadwals = Jadwal::whereDate('tanggal', $tanggal)->orderBy('created_at', 'desc')->get();
        $karyawans = User::hasKaryawan()->select('name', 'id')->latest()->get(); 

        return view('admin.history.show', compact('jadwals', 'tanggal', 'karyawans'));
    }

    public function export_jadwal($option)
    {
        $tanggal = Carbon::parse($option);

        $jadwal = Jadwal::whereDate('tanggal', $tanggal)->orderBy('tanggal', 'desc')->get();

        $export = (new FastExcel($jadwal))->download('jadwal_karyawan_'.$tanggal->format('d-m-Y').'.xlsx', function($jadwal){
            return [
                'Hari' => $jadwal->tanggal->translatedFormat('l'),
                'Tanggal' => $jadwal->tanggal->format('d/m/Y'),
                'Waktu' => $jadwal->waktuFormat,
                'Nama' => $jadwal->user->name,
                'Tujuan' => $jadwal->tujuan,
                'tugas' => $jadwal->tugas,
                'status' => statusJadwal($jadwal->status),
                'keterangan' => $jadwal->keterangan
            ];
        });

        return $export;
    }

    public function export_jadwal_all()
    {
        $jadwal = Jadwal::orderBy('tanggal', 'asc')->get();

        $export = (new FastExcel($jadwal))->download('jadwal_karyawan.xlsx', function($jadwal){
            return [
                'Hari' => $jadwal->tanggal->translatedFormat('l'),
                'Tanggal' => $jadwal->tanggal->format('d/m/Y'),
                'Waktu' => $jadwal->waktuFormat,
                'Nama' => $jadwal->user->name,
                'Tujuan' => $jadwal->tujuan,
                'tugas' => $jadwal->tugas,
                'status' => statusJadwal($jadwal->status),
                'keterangan' => $jadwal->keterangan
            ];
        });

        return $export;
    }

    public function export_akun_karyawan()
    {
        $karyawan = User::hasKaryawan()->latest()->get();

        $export = (new FastExcel($karyawan))->download('data_karyawan.xlsx', function($user){
            return [
                'Nama' => $user->name,
                'ID Karyawan' => $user->id_karyawan,
                'username' => $user->username,
                'password' => decryptPassword($user->enc_password)
            ];
        });

        return $export;
    }

    public function importDataKaryawan(Request $request)
    {
        $request->validate([
            'file' => 'required'
        ]);

        try{
            DB::beginTransaction();

            Excel::import(new KaryawanImport, $request->file('file'));

            DB::commit();

            return response()->json([
                'type' => 'success',
                'msg' => 'Berhasil mengimport data karyawan.'
            ]);

        }catch(\Exception $e){
            DB::rollBack();
            // if(app()->isLocal()){
            //     dd($e->getMessage());
            // }
            return response()->json([
                'type' => 'error',
                'msg' => $e->getMessage()
            ], 422);
        }
    }

    public function downloadTemplateImport()
    {
        $file_path = global_assets_path('assets/template_import_data.xlsx');
        $file_name = 'Template_Import_karyawan.xlsx';

        return response()->download($file_path, $file_name);
    }

    public function importJadwalKaryawan(Request $request)
    {
        $request->validate([
            'file' => 'required'
        ]);

        try{
            DB::beginTransaction();

            (new FastExcel)->import($request->file, function($line){
                $user = User::where('name', $line['Nama Karyawan'])
                    ->orWhere('id_karyawan', $line['ID Karyawan'])
                    ->select('id')
                    ->first();
                if($user){
                    return Jadwal::create([
                        'id_karyawan' => $user->id,
                        'tanggal' => $line['Tanggal'],
                        'waktu' => $line['Waktu'],
                        'tujuan' => $line['Tujuan'],
                        'tugas' => $line['Tugas'] 
                    ]);
                }
            });

            DB::commit();

            return response()->json([
                'type' => 'success',
                'msg' => 'Berhasil mengimport Jadwal karyawan.'
            ]);

        }catch(\Exception $e){
            DB::rollBack();
            dd($e->getMessage());
        }
    }

    public function downloadTemplateJadwal()
    {
        $file_path = global_assets_path('assets/template_import_jadwal.xlsx');
        $file_name = 'Template_Import_Jadwal.xlsx';

        return response()->download($file_path, $file_name);
    }

    public function salary_page()
    {
        return view('admin.gaji.index');
    }
}
