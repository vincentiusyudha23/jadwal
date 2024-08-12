<table class="table tabel-data-jadwal" id="datatable">
    <thead>
        <tr>
            <th>Hari</th>
            <th>Tanggal</th>
            <th>Nama</th>
            <th>Tujuan</th>
            <th>Tugas</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($jadwals ?? [] as $jadwal)
            <tr>
                <th>{{ $jadwal->tanggal->translatedFormat('l') }}</th>
                <td>{{ $jadwal->tanggal->format('d/m/Y') }}</td>
                <td>{{ $jadwal->user->name }}</td>
                <td>{{ $jadwal?->tujuan ?? '' }}</td>
                <td>{{ $jadwal?->tugas ?? '' }}</td>
                <td>
                    <x-status-jadwal type="button" :status="$jadwal->status"/>
                </td>
                <td>
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="{{ route('admin.karyawan.jadwal.show', ['id' => $jadwal->id]) }}" class="btn btn-sm btn-success" type="button">
                            <i class="fa-solid fa-eye text-white"></i>
                        </a>
                        <button class="btn btn-sm btn-warning" type="button" data-bs-toggle="modal"
                            data-bs-target="#edit-jadwal-form-{{ $jadwal->id }}">
                            <i class="fa-solid fa-pen-to-square text-white"></i>
                        </button>
                        <div class="modal fade" id="edit-jadwal-form-{{ $jadwal->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Jadwal</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('admin.karyawan.jadwal.update') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="jadwal" value="{{ $jadwal?->id ?? '' }}">
                                        <div class="modal-body">
                                            <div class="form-group mb-3">
                                                <input class="form-control" name="tanggal" type="date"
                                                    placeholder="Tanggal" value="{{ $jadwal?->tanggal?->format('Y-m-d') ?? '' }}">
                                            </div>
                                            <div class="form-group mb-3">
                                                <select class="form-select" name="karyawan"
                                                    aria-label="Default select example">
                                                    <option selected disabled>Nama Karyawan</option>
                                                    @foreach ($karyawans ?? [] as $karyawan)
                                                          <option value="{{ $karyawan->id }}" {{ $jadwal->id_karyawan == $karyawan->id ? 'selected' : '' }}>{{ $karyawan->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group mb-3">
                                                <input class="form-control" name="tujuan" type="text"
                                                    placeholder="Tujuan" value="{{ $jadwal?->tujuan ?? '' }}">
                                            </div>
                                            <div class="form-group mb-3">
                                                <textarea class="form-control" name="tugas" placeholder="Tugas">{{ $jadwal?->tugas ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-success w-100" type="submit" id="simpan_edit">
                                                <span class="fw-bold fs-6">Simpan</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <x-button-delete table="tabel-data-jadwal" :data_id="$jadwal->id" :route="route('admin.karyawan.jadwal.delete')" method="POST" />
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        $(document).on('click', '#simpan_edit', function(){
            var el = $(this);
            el.html('<i class="fa-solid fa-spinner fa-spin"></i>');
            el.addClass('disabled');
        });
    </script>
@endpush
