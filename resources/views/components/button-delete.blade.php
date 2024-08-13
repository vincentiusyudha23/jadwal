@props(['data_id', 'route', 'method', 'table'])

<button class="btn btn-sm btn-danger" type="button" id="btn-delete-karyawan{{ $data_id ?? '' }}"
    data-id="{{ $data_id ?? '' }}">
    <i class="fa-solid fa-trash text-white"></i>
</button>

@push('scripts')
    <script>
        $(document).on('click', '#btn-delete-karyawan{{ $data_id ?? '' }}', function() {
            Swal.fire({
                title: "Yakin Hapus?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#dc3545",
                confirmButtonText: "Hapus",
            }).then(async (result) => {
                if (result.isConfirmed) {
                    var el = $(this);
                    var data_id = el.data('id');

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
                            data_id: data_id
                        },
                        success: function(response) {
                            Swal.hideLoading();
                            if (response.msg) {
                                Swal.fire({
                                    title: response.msg,
                                    icon: 'success',
                                });
                                
                                location.reload();
                            }
                        }
                    })
                }
            });
        });
    </script>
@endpush
