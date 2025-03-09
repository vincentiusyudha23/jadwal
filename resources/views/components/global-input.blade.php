<div class="form-group">
    @if (isset($label))
        <label class="form-label" for="{{ $name }}">{{ $label }}</label>
    @endif
    <input type="{{ $type ?? 'text' }}" name="{{ $name }}" id="{{ $name }}" placeholder="{{ $placeHolder ?? '' }}" class="form-control" value="{{ $value ?? '' }}">
</div>