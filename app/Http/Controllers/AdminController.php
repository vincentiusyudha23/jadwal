<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class AdminController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view('admin.dashboard.index');
    }

    public function karyawan()
    {
        return view('admin.karyawan.index');
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
