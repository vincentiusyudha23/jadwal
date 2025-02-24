<style>
    .body-card {
        width: 258px;
        padding: 10px;
        font-family:Arial, Helvetica, sans-serif;
    }

    .body-card .card {
        width: 100%;
        display: table;
        text-align: center;
        border: 2px solid #0d6efd;
        border-bottom: none;
        border-radius: 0;
    }

    .body-card .card div {
        display: table-cell;
        vertical-align: center;
        padding-top: 30px;
    }

    .body-card .card img {
        width: 100px;
    }

    .body-card h3, .body-card h2 {
        margin: 5px 0;
        padding: 0;
    }

    .body-card .card-2 {
        width: 100%;
        height: 10%;
        border: 2px solid #0d6efd;
        text-align: center;
        display: table;
    }

    .body-card .card-2 span {
        font-size: 9px;
        display: block;
    }
    .body-card .card-2 div{
        display: table-cell;
        vertical-align: center;
        padding-top: 5px;
    }
</style>
@if (isset($karyawan) && !empty($karyawan))
<div class="body-card" id="idcard-{{ $karyawan->id_karyawan }}">
    <div class="card">
        <div>
            <h4 class="fw-bold text-primary" style="margin-bottom: 20px;">PT. WIRA GRIYA</h4>
            <img style="margin-bottom: 15px;" src="{{ assets('img/logo-1.png') }}" alt="PT. WIRA GRIYA">
            <h5 class="fw-bold">{{ $karyawan->name }}</h5>
            <h5 class="fw-bold">{{ $karyawan->id_karyawan }}</h5>
            <h5 class="fw-bold">{{ \App\Enums\JabatanEnum::getItemJabatan($karyawan->karyawan?->jabatan ?? '') }}</h5>
            <h5 class="fw-bold mb-3">{{ \App\Enums\DivisiEnum::getItemDivisi($karyawan->karyawan?->divisi ?? '') }}</h5>
        </div>
    </div>
    <div class="card-2">
        <div>
            <span>Jl. Kyai Haji Zainul Arifin No.40, RT.1/RW.5, Tanah Sereal,</span>
            <span>Kec. Tambora, Kota Jakarta Barat, DKI Jakarta, 11210</span>
            <span>(021) 6332862</span>
        </div>
    </div>
</div>
@endif