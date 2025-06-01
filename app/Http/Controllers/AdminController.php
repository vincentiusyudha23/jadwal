<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Absen;
use App\Models\Jadwal;
use App\Enums\IjinEnum;
use App\Models\Karyawan;
use App\Enums\DivisiEnum;
use App\Enums\JabatanEnum;
use Illuminate\Support\Str;
use App\Models\IjinKaryawan;
use Illuminate\Http\Request;
use App\Imports\JadwalImport;
use App\Imports\KaryawanImport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Jobs\ImportDataKaryawan;
use Illuminate\Support\Facades\DB;
use App\Jobs\NotificationJadwalJob;
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
    
    /**
     * Menampilkan dashboard admin dengan jadwal hari ini dan daftar karyawan.
     * Data diteruskan ke tampilan 'admin.dashboard.index' untuk dirender.
     */
    public function index()
    {
        $jadwals = Jadwal::whereDate('tanggal', Carbon::now())->orderBy('created_at', 'desc')->get();
        $karyawans = User::hasKaryawan()->select('name', 'id')->latest()->get();
        return view('admin.dashboard.index', compact('jadwals', 'karyawans'));
    }

    /**
     * Menampilkan halaman data karyawan.
     * Mengambil data karyawan, divisi, dan jabatan untuk ditampilkan 
     * pada halaman indeks karyawan admin.
     */
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
    
     /**
     * Menyimpan data karyawan baru ke dalam database.
     * Data yang disimpan meliputi informasi user dan karyawan.
     * Setelah data berhasil disimpan, role 'karyawan' akan ditambahkan ke user.
     */
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
            'gaji' => ['required', 'string']
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
                'gaji' => (int) str_replace(['.', 'Rp '], '', $request->gaji)
            ]);

            $user->assignRole('karyawan');

            DB::commit();

            return redirect()->back()->with('success', 'Berhasil Menambah Data.');

        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi Kesalahan.');
        }
    }

    /**
     * Fungsi untuk mengupdate data karyawan
     */
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
            'gaji' => ['required', 'string']
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
                'gaji' => (int) str_replace(['.', 'Rp '], '', $request->gaji)
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

    /**
     * fungsi untuk melakukan penghapusan data karyawan
     */
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

    /**
     * fungsi untuk menampilkan id card karyawan
     */
    public function view_id_card($id)
    {
        $karyawan = User::findOrFail($id);
        $image = assets('img/logo-1.png');

        return view('admin.karyawan.partials.card-id', compact('karyawan', 'image'));
    }

    /**
     * fungsi untuk memproses download id card karyawan dalam bentuk PDF
     */
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

    /**
     * fungsi untuk menampilkan halaman pembuatan jadwal karyawan
     */
    public function jadwal()
    {
        $karyawans = User::hasKaryawan()->select('name', 'id')->latest()->get();
        $jadwals = Jadwal::latest()->get();
        return view('admin.karyawan.jadwal', compact('karyawans', 'jadwals'));
    }

    /**
     * fungsi untuk memproses pembuatan jadwal karyawan ke dalam database
     */
    public function store_jadwal(Request $request)
    {
        $this->validate($request, [
            'karyawan' => ['required','max:8'],
            'tanggal' => ['required'],
            'tujuan' => ['required', 'max:255'],
            'tugas' => ['required'],
            'waktu' => ['required'],
            'note' => ['nullable']
        ]);

        try{
            $user = User::find($request->karyawan);

            $jadwal = Jadwal::where([
                'id_karyawan' => $user->id,
                'tanggal' => $request->tanggal,
                'waktu' => $request->waktu
            ])->first();

            if($jadwal){
                return redirect()->back()->with('errors', 'Tanggal dan waktu sudah dijadwalkan.');
            }

            if($user){
                $jadwalNew = Jadwal::create([
                    'id_karyawan' => $user->id,
                    'tanggal' => $request->tanggal,
                    'tujuan' => $request->tujuan,
                    'tugas' => $request->tugas,
                    'waktu' => $request->waktu,
                    'note' => $request->note
                ]);
                
                NotificationJadwalJob::dispatch($jadwalNew);  // fungsi pengiriman notifikasi ke email karyawan

                return redirect()->back()->with('success', 'Berhasil Membuat Jadwal');
            } else {
                return redirect()->back()->with('errors', 'Karyawan tidak ditemukan');
            }
        }catch(\Exception $e){
            dd($e->getMessage());
        }
    }

    /**
     * fungsi untuk memproses update jadwal karyawan
     */
    public function update_jadwal(Request $request)
    {
        $this->validate($request, [
            'jadwal' => ['required', 'max:8'],
            'karyawan' => ['required','max:8'],
            'tanggal' => ['required'],
            'tujuan' => ['required', 'max:255'],
            'tugas' => ['required'],
            'waktu' => ['required'],
            'note' => ['nullable']
        ]);

        try{
            $jadwal = Jadwal::find($request->jadwal);

            $user = User::find($request->karyawan);

            if($user && $jadwal){

                // menghalang admin untuk melakukan update jadwal kurang dari 1 jam dari waktu yang sudah ditetapkam
                $waktuJadwal = Carbon::parse($jadwal->tanggal->format('Y-m-d') . ' ' . $jadwal->waktu);
                $waktuSekarang = Carbon::now();
                $selisihWaktu = $waktuSekarang->diffInMinutes($waktuJadwal, false);
                $waktu_bisa_edit = $waktuJadwal->copy()->addDay()->setTime(6, 0, 0);
                
                if($selisihWaktu < 60 && $waktuSekarang->lt($waktu_bisa_edit)){
                    return redirect()->back()->with('errors', 'Anda dapat melakukan edit jadwal di esok hari pukul 06:00');
                }

                // Update Jadwal ke database
                $jadwal->update([
                    'id_karyawan' => $user->id,
                    'tanggal' => $request->tanggal,
                    'tujuan' => $request->tujuan,
                    'tugas' => $request->tugas,
                    'waktu' => $request->waktu,
                    'note' => $request->note
                ]);

                return redirect()->back()->with('success', 'Berhasil Memperbarui Jadwal');
            } else {
                return redirect()->back()->with('errors', 'Karyawan/Jadwal tidak ditemukan');
            }
        }catch(\Exception $e){
            dd($e->getMessage());
        }
    }

    /**
     * fungsi untuk memproses penghapusan data jadwal karyawan
     */
    public function delete_jadwal(Request $request)
    {
        $id_jadwal = $request->data_id;

        $jadwal = Jadwal::find($id_jadwal);

        if($jadwal){

            $waktuJadwal = Carbon::parse($jadwal->tanggal->format('Y-m-d') . ' ' . $jadwal->waktu);
            $waktuSekarang = Carbon::now();
            $selisihWaktu = $waktuSekarang->diffInMinutes($waktuJadwal, false);
            $waktu_bisa_hapus = $waktuJadwal->copy()->addDay()->setTime(6, 0, 0);

            // admin hanya bisa menghapus jadwal paling lambat 1 jam dari waktu jadwal yang sudah di tentukan
            if($selisihWaktu < 60 && $waktuSekarang->lt($waktu_bisa_hapus)){
                return response()->json([
                    'type' => 'errors',
                    'msg' => 'Anda dapat menghapus jadwal di esok hari pukul 06:00',
                ]);
            }

            $jadwal->delete();

            $jadwals = Jadwal::latest()->get();
            $karyawans = User::where('role', 'karyawan')->select('name', 'id')->latest()->get();

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

    /**
     * fungsi untuk menampilkan detail jadwal yang sudah dibuat
     */
    public function show_jadwal($id)
    {
        $jadwal = Jadwal::find($id);

        return view('admin.karyawan.show', compact('jadwal'));
    }

    /**
     * fungsi untuk menampilkan halaman profile admin / update password dan email
     */
    public function profile()
    {
        return view('admin.auth.profile');
    }
    
    /**
     * fungsi untuk memproses update password dan email admin
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validateWithBag('updatePassword', [
            'username' => ['required'],
            'password' => ['nullable',Password::defaults(), 'confirmed'],
            'email' => ['required', 'unique:users,email,'.$request->user()->id]
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

    /**
     * fungsi untuk menampilkan halaman riwayat jadwal
     */
    public function history()
    {
        return view('admin.history.index');
    }

    /**
     * fungsi untuk menampilkan riwayat jadwal berdasarkan tanggal
     */
    public function getHistory($tanggal)
    {
        $jadwals = Jadwal::whereDate('tanggal', $tanggal)->orderBy('created_at', 'desc')->get();
        $karyawans = User::hasKaryawan()->select('name', 'id')->latest()->get(); 

        return view('admin.history.show', compact('jadwals', 'tanggal', 'karyawans'));
    }

    /**
     * fungsi untuk memproses export jadwal ke excel
     */
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
                'keterangan' => $jadwal->keterangan,
                'Work Report' => $jadwal?->work_report ? asset('assets/work_report/'.$jadwal->work_report) : '',
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
                'password' => decryptPassword($user->enc_password),
                'email' => $user->email ?? '',
                'Jabatan' => \App\Enums\JabatanEnum::getItemJabatan($user->karyawan->jabatan ?? ''),
                'Divisi' => \App\Enums\DivisiEnum::getItemDivisi($user->karyawan?->divisi ?? ''),
                'Nomor Rekening' => $user->karyawan?->nomor_rekening ?? '',
                'Gaji Pokok' => $user->karyawan?->gaji ?? ''
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

        }catch(\Maatwebsite\Excel\Validators\ValidationException $e){
            DB::rollBack();
            return response()->json([
                'type' => 'error',
                'msg' => collect($e->failures())->map(function($err){
                    return "Row '{$err->row()}', {$err->errors()[0]}";
                })
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

            Excel::import(new JadwalImport, $request->file('file'));

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

    public function dataAbsensi()
    {
        $absensi = Absen::where('type', 1)->orderBy('created_at', 'desc')->get()->groupBy('tanggal')->keys()->map(function($item){
            return Carbon::parse($item)->format('d/m/Y');
        })->toArray();
        
        return view('admin.absen.riwayat-absen', compact('absensi'));
    }

    public function dataAbsensiByDate()
    {
        $tanggal = request('tanggal', '');
        abort_if(empty($tanggal), 404);
        
        $tanggal = str_replace('/', '-', $tanggal);

        $absensi = Absen::whereDate('created_at', Carbon::parse($tanggal)->format('Y-m-d'))
            ->where('type', 1)
            ->latest()
            ->get()
            ->map(function($item){
                $absenPulang = Absen::where(['id_karyawan' => $item->id_karyawan, 'type' => 2])->whereDate('tanggal', $item->tanggal)->first();
                $item['waktu_masuk'] = $item->waktuFormat;
                $item['waktu_pulang'] = $absenPulang?->waktuFormat ?? '';
                $item['total'] = !empty($absenPulang) ? $item->created_at->diff($absenPulang->created_at)->format('%H jam %i menit') : '';
                return $item;
            });
            
        return view('admin.absen.index')->with([
            'absensi' => $absensi
        ]);
    }

    public function detailAbsensi($id)
    {
        $absen = Absen::findOrFail($id);
        $absens = Absen::whereDate('created_at', $absen->created_at)->where('id_karyawan', $absen->user->id)->get();

        return view('karyawan.absen.details')->with(['absens' => $absens]);
    }

    public function deleteAbsen(Request $request)
    {
        $this->validate($request, [
            'data_id' => 'required'
        ]);

        $absen = Absen::find($request->data_id);

        if($absen){
            $absenPulang = Absen::where(['id_karyawan' => $absen->id_karyawan, 'type' => 2])->whereDate('created_at', $absen->created_at)->first();
            if($absenPulang){
                $absenPulang->delete();
            }
            $absen->delete();

            return response()->json(['type' => 'success', 'msg' => 'Berhasil Menghapus Absen.']);
        }

        return response()->json(['type' => 'errors', 'msg' => 'Gagal Menghapus Absen.']);
    }

    public function exportAbsen($tanggal)
    {
        abort_if(empty($tanggal), 404);
        $absens = Absen::whereDate('tanggal', $tanggal)->where('type', 1)->latest()->get();

        $export = (new FastExcel($absens))->download('data_absen_' . Carbon::parse($tanggal)->format('d-m-Y') . '.xlsx', function($absen){
            $absenPulang = Absen::whereDate('tanggal', $absen->tanggal)->where(['id_karyawan' => $absen->id_karyawan, 'type' => 2])->first();
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

    public function pengajuanIzin()
    {
        $pengajuan = IjinKaryawan::latest()->get()->groupBy(function($item) {
            return $item->from_date->format('d/m/Y');
        })->keys()->toArray();
        
        return view('admin.ijin.index', compact('pengajuan'));
    }

    public function showRiwayatIjin()
    {
        $tanggal = request()->query('tanggal');
        $ijins = IjinKaryawan::whereDate('from_date', Carbon::createFromFormat('d/m/Y', $tanggal))->latest()->get();

        return view('admin.ijin.riwayat', compact('ijins'));
    }

    public function detailsIjin($id)
    {
        $ijin = IjinKaryawan::findOrFail($id);
        $urlPrev = url()->previous();
        return view('admin.ijin.details', compact('ijin', 'urlPrev'));
    }

    public function deleteIjin(Request $request)
    {
        $this->validate($request, [
            'data_id' => 'required'
        ]);
        
        return IjinKaryawan::where('id', $request->data_id)?->first()->delete() ?
            response()->json(['type' => 'success', 'msg' => 'Berhasil Menghapus Ijin.']) :
            response()->json(['type' => 'errors', 'msg' => 'Gagal Menghapus Ijin.']);
    }

    public function exportIjin()
    {
        $ids = request('ids', []);;
        $ijin = IjinKaryawan::whereIn('id', $ids)->orderBy('created_at', 'desc')->get();
        $export = (new FastExcel($ijin))->download('ijin_karyawan.xlsx', function($ijin){
            return [
                'Tanggal Pengajuan' => $ijin->created_at->translatedFormat('d/m/Y'),
                'Nama' => $ijin->user->name,
                'ID karyawan' => $ijin->user->id_karyawan,
                'Jabatan' => $ijin->user->karyawan->jabatan,
                'Divisi' => $ijin->user->karyawan->divisi,
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
