<style>
    .img-absen{
        width: 100%;
        height: 100%;
    }
    @media (min-width: 768px) { 
        .img-absen{
            width: 50%;
            height: 100%;
        }
     }
</style>
<div class="card mb-3">
    <div class="card-body">
        <div class="w-100 rounded text-white text-center py-1 mb-2 {{ $absen->type == \App\Enums\AbsenEnum::MASUK ? 'bg-success' : 'bg-danger' }}">
            {{ \App\Enums\AbsenEnum::getText($absen->type) }}
        </div>
        <div class="row mb-2">
            <div class="col-5 col-sm-4 mb-2">
                <label class="form-label" for="waktu">Waktu</label>
                <div class="input-group">
                    <input class="form-control" type="time" id="waktu" value="{{ $absen->waktu }}" readonly disabled>
                </div>
            </div>
            <div class="col-7 col-sm-8 mb-2">
                <label class="form-label" for="tanggal">Tanggal</label>
                <div class="input-group">
                    <input class="form-control" type="date" id="tanggal" value="{{ $absen->tanggal->format('Y-m-d') }}" readonly disabled>
                </div>
            </div>
            <div class="col-12 mb-2">
                <label class="form-label" for="lokasi">Lokasi</label>
                <div class="input-group">
                    <input class="form-control" type="text" id="lokasi" value="{{ $absen->lokasi }}" readonly disabled>
                </div>
            </div>
        </div>
        <div class="w-100 d-flex justify-content-center align-items-center rounded" style="height: 200px;">
            <img class="img-absen object-fit-contain border rounded" src="{{ get_data_image($absen->image)['img_url'] ?? '' }}">
        </div>
    </div>
</div>