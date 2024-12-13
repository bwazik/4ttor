@props([
    'type',
    'name',
    'label',
    'placeholder' => '',
    'required' => false,
    'id' => null,
])

<div class="col-sm-12">
    <div class="form-floating form-floating-outline">
        <input type="{{ $type }}" id="{{ $id ?? $name }}" class="form-control" name="{{ $name }}" {{ $required ? "required" : "" }} placeholder="{{ $placeholder }}" aria-label="{{ $placeholder }}"/>
        <label for="{{ $id ?? $name }}">{{ $label }}</label>
    </div>
    <span class="invalid-feedback" id="{{ $id ?? $name }}_error" role="alert"></span>
</div>
