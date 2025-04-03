@extends('layouts.app')

@section('title', 'Absen Karyawan')

@push('styles')
    <style>
        .box-image{
            width: 100%;
            height: 300px;
        }

        .btn-change-camera{
            position: absolute;
            bottom: 10px;
            right: 10px;
        }

        canvas, video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: contain; /* Hindari distorsi */
        }

        @media (min-width: 768px){
            .box-image{
                width: 300px;
                height: 300px;
            }
        }
    </style>
@endpush

@section('content')
    <x-navbar-admin :name="Auth::user()->name">
        <div class="py-2">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h4 class="fw-bold">Absensi Karyawan</h4>
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                <ul class="px-4 m-0">
                                    <li>{{ session('success') }}</li>
                                </ul>
                            </div>
                        @endif
                        @if (session('errors'))
                            <div class="alert alert-danger" role="alert">
                                <ul class="px-4 m-0">
                                    <li>{{ session('errors') }}</li>
                                </ul>
                            </div>
                        @endif
                    </div>

                    <div class="w-100 mb-4 d-flex flex-column justify-content-center align-items-center gap-3">
                        <div class="box-image bg-secondary bg-opacity-10 rounded position-relative overflow-hidden">
                            <canvas id="canvas" class="d-none"></canvas>
                            <video id="video" autoplay></video>
                            <button class="btn btn-secondary btn-change-camera">
                                <i class="las la-redo-alt"></i>
                            </button>
                            <div id="loading-spin" class="d-none position-absolute w-100 h-100 bg-secondary bg-opacity-50 z-3 top-0 d-flex justify-content-center align-items-center">
                                <i class="las la-spinner la-spin fs-1"></i>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-sm" id="take-photo">
                            <i class="las la-camera fs-3"></i>
                        </button>
                        <button class="btn btn-danger fw-bold btn-sm d-none" id="retake-photo">Ambil Ulang</button>
                        <button class="btn btn-warning btn-sm fw-bold" id="on-camera">Hidupkan</button>
                    </div>

                    <form action="{{ route('karyawan.absen.store') }}" method="POST" id="formAbsen">
                        @csrf
                        <input type="hidden" id="image_id" name="image_id">
                        <div class="w-100 mb-4">
                            <div class="row">
                                <div class="col-md-2 col-4 mb-2">
                                    <div class="">
                                        <label class="form-label" for="waktu">Waktu</label>
                                        <div class="input-group">
                                            <input type="time" name="waktu" id="waktu" class="form-control" readonly required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-8 mb-2">
                                    <div class="">
                                        <label class="form-label" for="tanggal">Tanggal</label>
                                        <div class="input-group">
                                            <input type="date" name="tanggal" id="tanggal" class="form-control" readonly required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-7 col-12 mb-2">
                                    <div class="">
                                        <label class="form-label" for="lokasi">Lokasi</label>
                                        <div class="input-group">
                                            <input type="text" name="lokasi" id="lokasi" class="form-control" readonly required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
    
                        <div class="w-100">
                            <button class="btn btn-success w-100 disabled" type="submit" id="saveAbsen">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-navbar-admin>
@endsection

@push('scripts')
    <script>
        $(document).ready(function(){
            let video = $("#video")[0];
            let canvas = $("#canvas")[0];
            let takePhoto = $('#take-photo');
            let facingMode = "environment";
            let stream = null;

            function getLocation(){
                if (!navigator.geolocation) {
                    alert('Geolocation tidak didukung oleh browser Anda.');
                    return;
                }

                navigator.geolocation.getCurrentPosition(
                    function (position){
                        let lat = position.coords.latitude;
                        let lon = position.coords.longitude;
                        let now = new Date();
                        
                        let year = now.getFullYear();
                        let month = (now.getMonth() + 1).toString().padStart(2, "0");
                        let day = now.getDate().toString().padStart(2, "0");
                        let formattedDate = `${year}-${month}-${day}`;

                        let hours = now.getHours().toString().padStart(2, "0");
                        let minutes = now.getMinutes().toString().padStart(2, "0");
                        let formattedTime = `${hours}:${minutes}`;
                        
                        $('#waktu').val(formattedTime);
                        $('#tanggal').val(formattedDate);
                        getAddress(lat, lon);
                    },
                    function(error) {
                        let errorMessage = "Error mendapatkan lokasi: ";
                        switch(error.code) {
                            case error.PERMISSION_DENIED:
                                errorMessage += "Pengguna menolak permintaan geolokasi.";
                                break;
                            case error.POSITION_UNAVAILABLE:
                                errorMessage += "Informasi lokasi tidak tersedia.";
                                break;
                            case error.TIMEOUT:
                                errorMessage += "Permintaan untuk mendapatkan lokasi pengguna timeout.";
                                break;
                            case error.UNKNOWN_ERROR:
                                errorMessage += "Error tidak diketahui terjadi.";
                                break;
                        }
                        alert('Nyalakan GPS/Lokasi anda!.');
                    },
                    { enableHighAccuracy: true, timeout: 10000 }
                )
            }

            function getAddress(lat, lon) {
                $.ajax({
                    url: `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lon}`,
                    method: 'GET',
                    headers: {
                        'Accept-Language': 'id'
                    },
                    success: function(data) {
                        if (data.address) {
                            let addressParts = [];
                            
                            if (data.address.road) addressParts.push(data.address.road);
                            if (data.address.village) addressParts.push(data.address.village);
                            if (data.address.suburb) addressParts.push(data.address.suburb);
                            if (data.address.city_district) addressParts.push(data.address.city_district);
                            if (data.address.city) addressParts.push(data.address.city);
                            if (data.address.state) addressParts.push(data.address.state);
                            if (data.address.country) addressParts.push(data.address.country);
                            
                            let address = addressParts.join(', ');
                            
                            $('#lokasi').val(address);
                            $('#saveAbsen').removeClass('disabled');
                        } else {
                            alert('Alamat tidak ditemukan untuk lokasi ini.');
                        }
                    },
                    error: function() {
                        alert('Gagal mendapatkan alamat. Silakan coba lagi!');
                    }
                });
            }

            function onCamera(mode){
                if(stream){
                    stream.getTracks().forEach(track => track.stop());
                }

                navigator.mediaDevices.getUserMedia({ video: { facingMode: mode } })
                    .then( newStream => {
                        stream = newStream;
                        video.srcObject = newStream;
                        $("#canvas").addClass("d-none");
                        $("#video").removeClass("d-none");
                        $('#retake-photo').addClass('d-none');
                        $('#take-photo').removeClass('d-none');
                    })
                    .catch(error => {
                        console.error("Akses kamera ditolak!", error);
                    });
            }

            $('#on-camera').on('click', function(){  
                onCamera(facingMode);
            });

            $('.btn-change-camera').on('click', function(){
                if (!stream || !stream.active) {
                    alert('Kamera belum aktif!');
                    return;
                }

                if(facingMode == 'environment'){
                    facingMode = 'user';
                    onCamera('user');
                    return;
                }
                if(facingMode == 'user'){
                    facingMode = 'environment';
                    onCamera('environment');
                    return;
                }
            });

            takePhoto.on('click', function(){
                if (!stream || !stream.active) {
                    alert('Kamera belum aktif!');
                    return;
                }

                takePicture();
            });

            function takePicture(){
                let context = canvas.getContext('2d');
                let videoWidth = video.videoWidth;
                let videoHeight = video.videoHeight;
                
                canvas.width = videoWidth;
                canvas.height = videoHeight;

                context.drawImage(video, 0, 0, videoWidth, videoHeight);

                canvas.toBlob(function(blob){
                    let formData = new FormData();
                    formData.append('file', blob, 'photo.png');

                    $.ajax({
                        url: "{{ route('karyawan.upload.image') }}",
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        beforeSend: function(){
                            $('#loading-spin').removeClass('d-none');
                        },
                        success: function(res){
                            if(res.type == 'success'){
                                $('#image_id').val(res.id);
                                getLocation();
                                $('#loading-spin').addClass('d-none');
                            }
                        }
                    });
                }, 'image/png');

                $("#canvas").removeClass("d-none");
                $("#video").addClass("d-none");
                $('#retake-photo').removeClass('d-none');
                $('#take-photo').addClass('d-none');
            }

            $('#retake-photo').on('click', function(){
                $("#canvas").addClass("d-none");
                $("#video").removeClass("d-none");
                $('#retake-photo').addClass('d-none');
                $('#take-photo').removeClass('d-none');
                $('#saveAbsen').addClass('disabled');
                $('#formAbsen')[0].reset();
            });
        });
    </script>
@endpush