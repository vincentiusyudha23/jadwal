<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Document</title>
    <style>
        body {
            width: 248px;
            height: 400px;
            font-family:Arial, Helvetica, sans-serif;
        }

        .card {
            width: 100%;
            height: 90%;
            display: table;
            text-align: center;
            border: 2px solid #0d6efd;
            border-bottom: none;
        }

        .card div {
            display: table-cell;
            vertical-align: center;
            padding-top: 30px;
        }

        .card img {
            width: 100px;
        }

        h3, h2 {
            margin: 5px 0;
            padding: 0;
        }

        .card-2 {
            width: 100%;
            height: 10%;
            border: 2px solid #0d6efd;
            text-align: center;
            display: table;
        }

        .card-2 span {
            font-size: 9px;
            display: block;
        }
        .card-2 div{
            display: table-cell;
            vertical-align: center;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div>
            <h2 style="margin-bottom: 20px; color: #0d6efd;">PT. WIRA GRIYA</h2>
            <img style="margin-bottom: 15px;" src="{{ $image }}" alt="PT. WIRA GRIYA">
            <h3>{{ $karyawan->name }}</h3>
            <h3>{{ $karyawan->id_karyawan }}</h3>
            <h3>{{ \App\Enums\JabatanEnum::getItemJabatan($karyawan->karyawan?->jabatan ?? '') }}</h3>
            <h3>{{ \App\Enums\DivisiEnum::getItemDivisi($karyawan->karyawan?->divisi ?? '') }}</h3>
        </div>
    </div>
    <div class="card-2">
        <div>
            <span>Jl. Kyai Haji Zainul Arifin No.40, RT.1/RW.5, Tanah Sereal,</span>
            <span>Kec. Tambora, Kota Jakarta Barat, DKI Jakarta, 11210</span>
            <span>(021) 6332862</span>
        </div>
    </div>
</body>
</html>