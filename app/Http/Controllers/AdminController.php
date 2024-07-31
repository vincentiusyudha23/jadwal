<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
