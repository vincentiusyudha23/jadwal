@props(['image'])

<style>
    .upload-box {
        width: 200px;
        height: 200px;
        border-radius: 20px;
        border: 2px dashed var(--bs-gray-500);
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f8f8;
        position: relative;
        cursor: pointer;
        overflow: hidden;
    }

    .upload-box img {
        max-width: 100%;
        max-height: 100%;
        border-radius: 20px;
        object-fit: cover;
    }

    .upload-box input[type="file"] {
        display: none;
    }

    .remove-icon {
        position: absolute;
        top: 10px;
        right: 10px;
    }

    .progress-bar {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 5px;
        width: 100%;
        background-color: rgba(0, 0, 0, 0.2);
    }

    .progress-bar span {
        display: block;
        height: 100%;
        width: 0;
        background-color: #007bff;
        transition: width 0.4s ease;
    }
</style>
@php
    $random_id = Str::random(3);
    $images = get_data_image($image ?? '');
@endphp
<div class="upload-box" id="upload-box-{{ $random_id }}">
    @if($images)
        <img src="{{ $images['img_url'] ?? '' }}" alt="{{ $images['alt'] }}">
        <span class="remove-icon remove-icon-{{ $random_id }}"><i class="fas fa-times"></i></span>
    @else
        <input type="file" id="file-input-{{ $random_id }}" accept="image/*">
        <span class="btn btn-sm btn-primary" id="btn-file-{{ $random_id }}">Choose File</span>
    @endif
    <div class="progress-bar" id="progress-bar-{{ $random_id }}">
        <span></span>
    </div>
    
    <input type="hidden" name="image[]" value="{{ $image ?? '' }}">
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            function setupEventHandlers(id) {
                var $uploadBox = $('#upload-box-' + id);
                var $fileInput = $('#file-input-' + id);
                var $btnChoose = $('#btn-file-' + id);
                var $progressBar = $('#progress-bar-' + id + ' span');

                // Click event for Choose File button
                $btnChoose.on('click', function() {
                    $fileInput.click();
                });

                // Change event for file input
                $fileInput.on('change', function(event) {
                    var file = event.target.files[0];
                    if (file) {
                        var formData = new FormData();
                        formData.append('file', file);

                        var reader = new FileReader();
                        reader.onload = function(e) {
                            $btnChoose.addClass('d-none');
                            $uploadBox.append(
                                '<img src="' + e.target.result + '" alt="Uploaded Image">' +
                                '<span class="remove-icon remove-icon-' + id +
                                '"><i class="fas fa-times"></i></span>'
                            );
                            setupEventHandlers(id); // Re-setup event handlers
                        };
                        reader.readAsDataURL(file);

                        $.ajax({
                            url: "{{ route('karyawan.upload.image') }}",
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            xhr: function() {
                                var xhr = new XMLHttpRequest();
                                xhr.upload.addEventListener('progress', function(e) {
                                    if (e.lengthComputable) {
                                        var percentComplete = (e.loaded / e.total) * 100;
                                        $progressBar.css('width', percentComplete + '%');
                                    }
                                }, false); 
                                return xhr;
                            },
                            success: function(response) {
                                if(response.type == 'success'){
                                    $progressBar.css('width', '100%'); // Set progress bar to full width
                                    $uploadBox.find('input[name="image[]"]').val(response.id);
                                }
                            },
                            error: function(response) {
                                alert('Error uploading image.');
                                $progressBar.css('width', '0%'); // Reset progress bar
                            }
                        });
                    }
                });

                // Click event for remove icon
                $(document).on('click', '.remove-icon-' + id, function() {
                     $uploadBox.empty().append(
                        '<input type="file" id="file-input-' + id + '" accept="image/*">' +
                        '<span class="btn btn-sm btn-primary" id="btn-file-' + id + '">Choose File</span>' +
                        '<div class="progress-bar" id="progress-bar-' + id + '"><span></span></div>'+
                        '<input type="hidden" name="image" value="">'
                    );
                    setupEventHandlers(id); // Re-setup event handlers
                });
            }

            var randomId = '{{ $random_id }}';
            setupEventHandlers(randomId);
        });
    </script>
@endpush
