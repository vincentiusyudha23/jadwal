<div class="container-sidebar">
    <div class="title-sidebar">
        <p class="border-bottom border-light pb-1 d-flex align-items-center">
            <img src="{{ assets('img/logo-1.png') }}" width="30" height="30" alt="img">
            <span class="px-2 fw-bold text-white fs-4">PT. WIRA GRIYA</span>
        </p>
    </div>
    {{-- <div class="w-100 border-top border-primary-subtle"></div> --}}

    <div class="sidebar">
        
        @if (Auth::user()->hasRole('admin'))
            <a href="{{ route('admin.dashboard') }}" class="text-white fs-5 sidebar-item {{ Request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="las la-home mx-2 fs-3"></i>
                <span class="fw-semibold">Halaman Utama</span>
            </a>
            <a href="{{ route('admin.karyawan') }}" class="text-white fs-5 sidebar-item {{ Request()->routeIs('admin.karyawan') ? 'active' : '' }}">
                <i class="las la-user-circle mx-2 fs-3"></i>
                <span class="fw-semibold">Data Karyawan</span>
            </a>
            <a href="{{ route('admin.absen.riwayat') }}" class="text-white fs-5 sidebar-item {{ Request()->routeIs('admin.absen.riwayat') ? 'active' : '' }}">
                <i class="las la-camera mx-2 fs-3"></i>
                <span class="fw-semibold">Data Absensi</span>
            </a>
            <a href="{{ route('admin.karyawan.jadwal') }}" class="text-white fs-5 sidebar-item {{ Request()->routeIs('admin.karyawan.jadwal') ? 'active' : '' }}">
                <i class="las la-clock mx-2 fs-3"></i>
                <span class="fw-semibold">Jadwal Karyawan</span>
            </a>
            <a href="{{ route('admin.history') }}" class="text-white fs-5 sidebar-item {{ Request()->routeIs('admin.history') ? 'active' : '' }}">
                <i class="las la-history mx-2 fs-3"></i>
                <span class="fw-semibold">Riwayat Jadwal</span>
            </a>
            <a href="{{ route('admin.penggajian') }}" class="text-white fs-5 sidebar-item {{ Request()->routeIs('admin.penggajian') ? 'active' : '' }}">
                <i class="las la-money-check mx-2 fs-3"></i>
                <span class="fw-semibold">Penggajian</span>
            </a>
            <a href="{{ route('admin.gaji.riwayat') }}" class="text-white fs-5 sidebar-item {{ Request()->routeIs('admin.gaji.riwayat') ? 'active' : '' }}">
                <i class="las la-history mx-2 fs-3"></i>
                <span class="fw-semibold">Riwayat Gaji</span>
            </a>
            <a href="{{ route('admin.ijin') }}" class="text-white fs-5 sidebar-item {{ Request()->routeIs('admin.ijin') ? 'active' : '' }}">
                <i class="lab la-telegram mx-2 fs-3"></i>
                <span class="fw-semibold">Riwayat Izin</span>
            </a>
            <a href="{{ route('admin.profile') }}" class="text-white fs-5 sidebar-item {{ Request()->routeIs('admin.profile') ? 'active' : '' }}">
                <i class="las la-user-lock mx-2 fs-3"></i>
                <span class="fw-semibold">Kelola Akun</span>
            </a>
        @endif

        @if (Auth::user()->hasRole('karyawan'))
            <a href="{{ route('karyawan.dashboard') }}" class="text-white fs-5 sidebar-item {{ Request()->routeIs('karyawan.dashboard') ? 'active' : '' }}">
                <i class="las la-home mx-2 fs-3"></i>
                <span class="fw-semibold">Halaman Utama</span>
            </a>
            <a href="{{ route('karyawan.absen.view') }}" class="text-white fs-5 sidebar-item {{ Request()->routeIs('karyawan.absen.view') ? 'active' : '' }}">
                <i class="las la-camera mx-2 fs-3"></i>
                <span class="fw-semibold">Absensi Karyawan</span>
            </a>
            <a href="{{ route('karyawan.absen.riwayat') }}" class="text-white fs-5 sidebar-item {{ Request()->routeIs('karyawan.absen.riwayat') ? 'active' : '' }}">
                <i class="las la-clock mx-2 fs-3"></i>
                <span class="fw-semibold">Riwayat Absensi</span>
            </a>
            <a href="{{ route('karyawan.jadwal.riwayat') }}" class="text-white fs-5 sidebar-item {{ Request()->routeIs('karyawan.jadwal.riwayat') ? 'active' : '' }}">
                <i class="las la-history mx-2 fs-3"></i>
                <span class="fw-semibold">Riwayat Jadwal</span>
            </a>
            <a href="{{ route('karyawan.gaji.riwayat') }}" class="text-white fs-5 sidebar-item {{ Request()->routeIs('karyawan.gaji.riwayat') ? 'active' : '' }}">
                <i class="las la-file-invoice mx-2 fs-3"></i>
                <span class="fw-semibold">Riwayat Gaji</span>
            </a>
            <a href="{{ route('karyawan.ijin.view') }}" class="text-white fs-5 sidebar-item {{ Request()->routeIs('karyawan.ijin.view') ? 'active' : '' }}">
                <i class="lab la-telegram mx-2 fs-3"></i>
                <span class="fw-semibold">Pengajuan Izin</span>
            </a>
            <a href="{{ route('karyawan.ijin.riwayat') }}" class="text-white fs-5 sidebar-item {{ Request()->routeIs('karyawan.ijin.riwayat') ? 'active' : '' }}">
                <i class="las la-history mx-2 fs-3"></i>
                <span class="fw-semibold">Riwayat Izin</span>
            </a>
            <a href="{{ route('karyawan.profile') }}" class="text-white fs-5 sidebar-item {{ Request()->routeIs('karyawan.profile') ? 'active' : '' }}">
                <i class="las la-user-lock mx-2 fs-3"></i>
                <span class="fw-semibold">Kelola Akun</span>
            </a>
        @endif
        
        <div class="toggle-btn text-primary rounded border border-2 border-primary shadow-lg">
            <i class="fa-solid fa-arrow-right-from-bracket fa-lg"></i>
        </div>
    </div>
</div>