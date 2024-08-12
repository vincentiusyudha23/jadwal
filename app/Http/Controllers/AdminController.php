<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Crypt;
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

        return view('admin.karyawan.index', compact('karyawans'));
    }

    public function store_karyawan(Request $request)
    {
        $this->validate($request, [
            'nama' => ['required'],
            'id_karyawan' => ['required', 'unique:users,id_karyawan'],
            'username' => ['required', 'unique:users,username'],
            'password' => ['required', Password::min(8)]
        ]);

        try{
            $karyawan = User::create([
                'name' => $request->nama,
                'id_karyawan' => $request->id_karyawan,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'enc_password' => Crypt::encryptString($request->password),
                'role' => 'karyawan'
            ]);

            $karyawan->assignRole('karyawan');

            return redirect()->back()->with('success', 'Berhasil Menambah Data.');

        }catch(\Exception $e){
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
            'password' => ['required', Password::min(8)]
        ]);

        try{
            $user = User::find($request->id);

            $user->update([
                'name' => $request->nama,
                'id_karyawan' => $request->id_karyawan,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'enc_password' => Crypt::encryptString($request->password),
            ]);

            $karyawans = User::where('role', 'karyawan')->latest()->get();

            $markup = View::make('admin.karyawan.partials.table', compact('karyawans'))->render();
            
            return response()->json([
                'type' => 'success',
                'markup' => $markup 
            ]);

        }catch(\Exception $e){
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
            'tugas' => ['required']
        ]);

        try{
            $user = User::find($request->karyawan);

            if($user){
                Jadwal::create([
                    'id_karyawan' => $user->id,
                    'tanggal' => $request->tanggal,
                    'tujuan' => $request->tujuan,
                    'tugas' => $request->tugas
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
            'tugas' => ['required']
        ]);

        try{
            $jadwal = Jadwal::find($request->jadwal);

            $user = User::find($request->karyawan);

            if($user && $jadwal){
                $jadwal->update([
                    'id_karyawan' => $user->id,
                    'tanggal' => $request->tanggal,
                    'tujuan' => $request->tujuan,
                    'tugas' => $request->tugas
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

            $markup = View::make('admin.karyawan.partials.tabel-jadwal', compact('jadwals', 'karyawans'))->render();

            return response()->json([
                'type' => 'success',
                'msg' => 'Berhasil Menghapus Jadwal Karyawan',
                'markup' => $markup
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

    public function updatePassword(Request $request): RedirectResponse
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
}
