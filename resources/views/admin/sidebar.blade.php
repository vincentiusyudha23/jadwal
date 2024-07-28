<div class="container-sidebar">
    <div class="title-sidebar">
        <p class="border-bottom border-light pb-1 d-flex align-items-center">
            <img src="{{ assets('img/logo-1.png') }}" width="30" height="30" alt="img">
            <span class="px-2 fw-bold text-white fs-4">PT. WIRA GRIYA</span>
        </p>
    </div>
    {{-- <div class="w-100 border-top border-primary-subtle"></div> --}}

    <div class="sidebar">
        <a class="text-white fs-5 sidebar-item {{ Request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="las la-home mx-2 fs-3"></i>
            <span class="fw-semibold">Halaman Utama</span>
        </a>
        <a class="text-white fs-5 sidebar-item">
            <i class="las la-user-circle mx-2 fs-3"></i>
            <span class="fw-semibold">Data Karyawan</span>
        </a>
        <a class="text-white fs-5 sidebar-item">
            <i class="las la-clock mx-2 fs-3"></i>
            <span class="fw-semibold">Jadwal Karyawan</span>
        </a>
        <a class="text-white fs-5 sidebar-item">
            <i class="las la-user-lock mx-2 fs-3"></i>
            <span class="fw-semibold">Ubah Password</span>
        </a>
        <a class="text-white fs-5 sidebar-item">
            <i class="las la-history mx-2 fs-3"></i>
            <span class="fw-semibold">Riwayat Jadwal</span>
        </a>
        
        <div class="toggle-btn text-primary rounded border border-2 border-primary shadow-lg">
            <i class="fa-solid fa-arrow-right-from-bracket fa-lg"></i>
        </div>
    </div>
</div>