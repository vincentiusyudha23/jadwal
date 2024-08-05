@props(['id_karyawan', 'route', 'method', 'table'])

<button class="btn btn-sm btn-danger" type="button" id="btn-delete-karyawan{{ $id_karyawan ?? '' }}"
    data-id="{{ $id_karyawan ?? '' }}">
    <i class="fa-solid fa-trash text-white"></i>
</button>

@push('scripts')
    <script>
        $(document).on('click', '#btn-delete-karyawan{{ $id_karyawan ?? '' }}', function() {
            Swal.fire({
                title: "Yakin Hapus?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#dc3545",
                confirmButtonText: "Hapus",
            }).then(async (result) => {
                if (result.isConfirmed) {
                    var el = $(this);
                    var id_karyawan = el.data('id');

                    Swal.fire({
                        title: 'Please Wait...',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    await $.ajax({
                        url: '{{ $route }}',
                        type: '{{ $method }}',
                        data: {
                            _token: '{{ csrf_token() }}',
                            id_karyawan: id_karyawan
                        },
                        success: function(response) {
                            Swal.hideLoading();
                            if (response.msg) {
                                Swal.fire({
                                    title: response.msg,
                                    icon: 'success',
                                });
                                var table = $('.{{ $table }}');
                                table.html(response.markup);
                                table.DataTable();
                            }
                        }
                    })
                }
            });
        });
    </script>
@endpush
