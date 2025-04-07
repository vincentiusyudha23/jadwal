<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover table-data-karyawan" id="datatable">
        <thead>
            <tr>
                <th class="text-center">No.</th>
                <th class="text-center">Nama</th>
                <th class="text-center">ID</th>
                <th class="text-center">Jabatan</th>
                <th class="text-center">Divisi</th>
                <th class="text-center">No. Rek</th>
                <th class="text-center">Gaji Pokok</th>
                <th class="text-center">Email</th>
                {{-- <th class="text-center">Username</th>
                <th class="text-center">Password</th> --}}
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @if (!empty($karyawans))
                @foreach ($karyawans as $karyawan)
                    <tr>
                        <th class="text-center">{{ $loop->index + 1 }}</th>
                        <td class="text-center">{{ $karyawan->name }}</td>
                        <td class="text-center">{{ $karyawan->id_karyawan }}</td>
                        <td class="text-center">{{  \App\Enums\JabatanEnum::getItemJabatan($karyawan->karyawan?->jabatan ?? '') }}</td>
                        <td class="text-center">{{ \App\Enums\DivisiEnum::getItemDivisi($karyawan->karyawan?->divisi ?? '') }}</td>
                        <td class="text-center">{{ $karyawan->karyawan?->nomor_rekening }}</td>
                        <td class="text-center">{{ currency($karyawan->karyawan?->gaji) }}</td>
                        <td class="text-center">{{ $karyawan->email }}</td>
                        {{-- <td class="text-center">{{ $karyawan->username }}</td>
                        <td class="text-center">{{ decryptPassword($karyawan->enc_password) }}</td> --}}
                        <td class="text-center">
                            <div class="d-flex gap-2 justify-content-center">
                                <button class="btn btn-sm btn-success" type="button" id="btn-cardid" data-bs-toggle="modal" data-bs-target="#id-card-karyawan-{{ $karyawan->id }}">
                                    <i class="fa-solid fa-id-card"></i>
                                </button>
    
                                <div class="modal fade" id="id-card-karyawan-{{ $karyawan->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-card-id">
                                        <div class="modal-content">
                                            <div class="modal-body d-flex flex-column justify-content-center align-items-center">
                                                <x-card-id :karyawan="$karyawan"/>
                                                <iframe class="d-none" src="{{ route('admin.karyawan.card-id', $karyawan->id) }}" style="width: 70mm; height: 120mm;" frameborder="0" id="iframe-card-id-{{ $karyawan->id }}"></iframe>
                                                <div class="d-flex gap-2 w-100 mt-3">
                                                    <a class="btn btn-success w-50 btn-download-card" href="javascript:void(0)" data-id_card="{{ $karyawan->id_karyawan }}" data-name="{{ $karyawan->name }}">
                                                        <i class="fa-solid fa-download"></i>
                                                        Unduh
                                                    </a>
                                                    <button type="button" class="btn btn-primary w-50" onclick="printIframe('{{ $karyawan->id }}')">
                                                        <i class="fa-solid fa-print"></i>
                                                        Print
                                                    </button>
                                                </div>
                                                <button type="button" class="btn w-100 btn-secondary mt-2" data-bs-dismiss="modal">
                                                    Cancel
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
    
                                <button class="btn btn-sm btn-warning btn-edit-karyawan" type="button"
                                    data-nama="{{ $karyawan->name }}"
                                    data-id="{{ $karyawan->id }}"
                                    data-id_karyawan="{{ $karyawan->id_karyawan }}"
                                    data-username="{{ $karyawan->username }}"
                                    data-password="{{ decryptPassword($karyawan->enc_password) }}"
                                    data-jabatan="{{ strtolower($karyawan->karyawan?->jabatan ?? '') }}"
                                    data-divisi="{{ strtolower($karyawan->karyawan?->divisi ?? '') }}"
                                    data-norek="{{ $karyawan->karyawan?->nomor_rekening ?? '' }}"
                                    data-email="{{ $karyawan->email }}"
                                    data-gaji="{{ ceil($karyawan->karyawan->gaji) }}"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#edit-karyawan-form">
                                    <i class="fa-solid fa-pen-to-square text-white"></i>
                                </button>
                                <x-button-delete table="table-data-karyawan" :data_id="$karyawan->id" :route="route('admin.karyawan.delete')" method="POST"/>
                                {{-- <button class="btn btn-sm btn-danger" type="button" id="btn-delete-karyawan" data-id="{{ $karyawan->id }}">
                                    <i class="fa-solid fa-trash text-white"></i>
                                </button> --}}
                            </div>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
