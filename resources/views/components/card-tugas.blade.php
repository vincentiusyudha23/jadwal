@props(['jadwal', 'karyawans'])

<div class="col-12 col-sm-6 d-lg-none mb-4">
    <div class="card">
        <div class="card-body">
            <div class="w-100 d-flex justify-content-between fs-6 fw-bold" style="color: var(--bs-gray-600);">
                <span>{{ $jadwal->tanggal->translatedFormat('l') }}</span>
                <span>{{ $jadwal->tanggal->format('d/m/Y') }}</span>
            </div>
            <div class="mt-1">
                <span style="font-size: 0.85em;"><strong>Nama : </strong>{{ $jadwal?->user?->name }}</span>
            </div>
            <div class="mt-1">
                <span style="font-size: 0.85em;"><strong>Tujuan : </strong>{{ $jadwal?->tujuan }}</span>
            </div>
            <div class="mt-3 w-100">
                <div class="border border-2 px-2 py-1 rounded-2">
                    <p class="text-center m-0 p-0 w-100">
                        <strong>Tugas</strong>
                    </p>
                    <span>
                        {{ $jadwal?->tugas }}
                    </span>
                </div>
            </div>
            <div class="mt-3 w-100 d-flex justify-content-end gap-1">
                <a href="{{ route('admin.karyawan.jadwal.show', ['id' => $jadwal->id]) }}"
                    class="btn btn-sm btn-success" type="button">
                    <i class="fa-solid fa-eye text-white"></i>
                </a>
                <button class="btn btn-sm btn-warning" type="button" data-bs-toggle="modal"
                    data-bs-target="#edit-jadwal-form-{{ $jadwal->tanggal->format('d-m-y') }}">
                    <i class="fa-solid fa-pen-to-square text-white"></i>
                </button>
                <div class="modal fade" id="edit-jadwal-form-{{ $jadwal->tanggal->format('d-m-y') }}" tabindex="-1"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
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
                                        <input class="form-control" name="tanggal" type="date" placeholder="Tanggal"
                                            value="{{ $jadwal?->tanggal?->format('Y-m-d') ?? '' }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <select class="form-select" name="karyawan" aria-label="Default select example">
                                            <option selected disabled>Nama Karyawan</option>
                                            @foreach ($karyawans ?? [] as $karyawan)
                                                <option value="{{ $karyawan->id }}"
                                                    {{ $jadwal->id_karyawan == $karyawan->id ? 'selected' : '' }}>
                                                    {{ $karyawan->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <input class="form-control" name="tujuan" type="text" placeholder="Tujuan"
                                            value="{{ $jadwal?->tujuan ?? '' }}">
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
        </div>
    </div>
</div>
