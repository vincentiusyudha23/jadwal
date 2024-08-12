<table class="table table-data-karyawan" id="datatable">
    <thead>
        <tr>
            <th class="text-center">No.</th>
            <th class="text-center">Nama</th>
            <th class="text-center">ID</th>
            <th class="text-center">Username</th>
            <th class="text-center">Password</th>
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
                    <td class="text-center">{{ $karyawan->username }}</td>
                    <td class="text-center">{{ decryptPassword($karyawan->enc_password) }}</td>
                    <td class="text-center">
                        <div class="d-flex gap-2 justify-content-center">
                            <button id="btn-edit-karyawan" class="btn btn-sm btn-warning" type="button"
                                data-nama="{{ $karyawan->name }}"
                                data-id="{{ $karyawan->id }}"
                                data-id_karyawan="{{ $karyawan->id_karyawan }}"
                                data-username="{{ $karyawan->username }}"
                                data-password="{{ decryptPassword($karyawan->enc_password) }}"
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
