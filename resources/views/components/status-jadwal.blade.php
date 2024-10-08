@props(['status', 'type'])

@if ($type == 'button')
    @if ($status == 0)
        <a href="javascript:void(0)" class="fw-bold btn btn-sm btn-warning">
            {{ statusJadwal($status) }}
        </a>
    @endif

    @if ($status == 1)
        <a href="javascript:void(0)" class="fw-bold btn btn-sm btn-success">
            {{ statusJadwal($status) }}
        </a>
    @endif
@endif
