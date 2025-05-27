<div class="table-responsive">
    <table class="table tabel-absen table-bordered table-striped table-hover" id="datatable">
        <thead>
            <tr>
                <th>Hari</th>
                <th>Tanggal</th>
                <th>Nama</th>
                <th class="text-center">Waktu Masuk</th>
                <th class="text-center">Waktu Pulang</th>
                <th class="text-center">
                    Total
                </th>
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
                        <td>{{ $absen->user->name }}</td>
                        <td class="text-center">{{ $absen->waktu_masuk }}</td>
                        <td class="text-center">{{ $absen->waktu_pulang }}</td>
                        <td>
                            <p class="text-center">{{ $absen->total }}</p>
                        </td>
                        <td>{{ $absen->lokasi }}</td>
                        <td>
                            <div class="d-flex justify-content-center align-items-center gap-2">
                                <a href="{{ route(route_prefix() . 'absen.details', $absen->id) }}" class="btn btn-sm btn-success">
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