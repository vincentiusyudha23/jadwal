<div class="table-responsive">
    <table class="table tabel-absen" id="datatable">
        <thead>
            <tr>
                <th>Hari</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th class="text-center">Tipe</th>
                <th>Nama</th>
                <th>Lokasi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @if (isset($absens) && !empty($absens))
                @foreach ($absens as $absen)
                    <tr>
                        <td>{{ $absen->tanggal->translatedFormat('l') }}</td>
                        <td>{{ $absen->tanggal->format('d/m/Y') }}</td>
                        <td>{{ $absen->waktuFormat }}</td>
                        <td class="text-center">{!! \App\Enums\AbsenEnum::getType($absen->type) !!}</td>
                        <td>{{ $absen->user->name }}</td>
                        <td>{{ $absen->lokasi }}</td>
                        <td>
                            <div>
                                <a href="{{ route('karyawan.absen.details', $absen->id) }}" class="btn btn-sm btn-success">
                                    <i class="fa-solid fa-eye text-white"></i>
                                </a>
                                <x-button-delete table="tabel-absen" :data_id="$absen->id" :route="$routeDelete" method="POST" />
                            </div>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>