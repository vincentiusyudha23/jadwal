<table class="table" id="datatable">
    <thead>
        <tr>
            <th>No.</th>
            <th>Nama</th>
            <th>ID</th>
            <th>Username</th>
            <th>Password</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @if (!empty($karyawans))
            @foreach ($karyawans as $karyawan)
                <tr>
                    <th>{{ $loop->index + 1 }}</th>
                    <td>{{ $karyawan->name }}</td>
                    <td>{{ $karyawan->id_karyawan }}</td>
                    <td>{{ $karyawan->username }}</td>
                    <td>{{ decryptPassword($karyawan->enc_password) }}</td>
                    <td>
                        <div class="d-flex gap-2 justify-content-center">
                            <button id="btn-edit-karyawan" class="btn btn-sm btn-warning" type="button"
                                data-bs-toggle="modal" data-bs-target="#edit-karyawan-form">
                                <i class="fa-solid fa-pen-to-square text-white"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" type="button">
                                <i class="fa-solid fa-trash text-white"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
