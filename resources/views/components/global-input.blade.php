<div class="mb-3">
    @if (isset($label) || isset($placeHolder))
        <label class="form-label fw-bold text-secondary m-1 p-0" for="{{ $name }}">{{ $label ?? $placeHolder ?? '' }}</label>
    @endif
    <div class="input-group">
        <input type="{{ $type ?? 'text' }}" placeholder="{{ ($placeHolder ?? '') ? '' : '' }}" name="{{ $name }}" id="{{ $name }}" value="{{ $value ?? '' }}" {{ $attributes->merge(['class' => 'form-control']) }}>
    </div>
</div>