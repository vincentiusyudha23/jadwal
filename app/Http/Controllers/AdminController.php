<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        return view('admin.dashboard.index');
    }

    public function karyawan()
    {
        $karyawans = User::where('role', 'karyawan')->latest()->get();

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
            User::create([
                'name' => $request->nama,
                'id_karyawan' => $request->id_karyawan,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'enc_password' => Crypt::encryptString($request->password),
                'role' => 'karyawan'
            ]);

            return redirect()->back()->with('success', 'Berhasil Menambah Data.');

        }catch(\Exception $e){
            return redirect()->back()->with('errors', 'Terjadi Kesalahan.');
        }
    }

    public function jadwal()
    {
        return view('admin.karyawan.jadwal');
    }

    public function profile()
    {
        return view('admin.auth.profile');
    }

    public function history()
    {
        return view('admin.history.index');
    }
}
