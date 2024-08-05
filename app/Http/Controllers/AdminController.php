<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
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
        $id_karyawan = $request->id_karyawan;

        $user = User::find($id_karyawan);

        if($user){
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
        $karyawans = User::where('role', 'karyawan')->select('name', 'id')->latest()->get();

        return view('admin.karyawan.jadwal', compact('karyawans'));
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
