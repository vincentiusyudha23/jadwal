<div class="card-content">
    <form wire:submit="loadData" class="w-100 d-flex flex-column flex-sm-row justify-content-end align-items-center mb-3">
        <div class="mx-2 mb-2 mb-sm-0 date-input">
            <div class="input-group">
                <span class="input-group-text" id="basic-addon3">From</span>
                <input type="date" wire:model="date_from" class="form-control" id="basic-url" name="date_from"
                    aria-describedby="basic-addon3 basic-addon4">
            </div>
        </div>
        <div class="btn btn-sm bg-gray-200 d-none d-md-block">
            <span class="fw-bold">-</span>
        </div>
        <div class="mx-2 mb-2 mb-sm-0 date-input">
            <div class="input-group">
                <input type="date" class="form-control" wire:model="date_to" name="date_to" aria-label="Recipient's username"
                    aria-describedby="basic-addon2">
                <span class="input-group-text" id="basic-addon2">To</span>
            </div>
        </div>
        <div class="date-input button">
            <button class="btn btn-sm btn-success">Cari</button>
            <a href="#" wire:click.prevent="firstData" class="btn btn-sm btn-danger">
                <i class="fa-solid fa-rotate-left"></i>
            </a>
        </div>
    </form>
    <div wire:loading class="skeleton skeleton-line" style="--lines: 3; --c-w: 100%; --l-h: 30px;"></div>
    <div class="w-100 d-flex flex-column gap-3 list-riwayat">
        @foreach ($jadwals as $tanggal)
            <a href="{{ route('admin.history.show', ['tanggal' => $tanggal]) }}" wire:loading.remove class="text-gray-700 fw-bold w-100 bg-gray-300 p-2 rounded-2 list-riwayat-item">
                Jadwal Tanggal : {{ \Carbon\Carbon::parse($tanggal)->format('d/m/Y') }}
            </a>
        @endforeach
    </div>
    @if(count($jadwals) < 1)
        <div class="w-100 d-flex justify-content-center align-items-center" style="height: 100px;" wire:loading.class="d-none">
            <span wire:loading.remove>Tidak Ada Jadwal</span>
        </div>
    @endif
</div>
