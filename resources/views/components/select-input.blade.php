@props([
    'name',
    'label',
    'options' => [],
    'required' => false,
    'id' => null,
    'multiple' => false,
    'context' => null,
    'divClasses' => null,
])

@php
$defaultDivClasses = match ($context) {
    'modal' => 'col-12 col-md-6',
    'offcanvas' => 'col-sm-12 mt-4',
    default => 'col-sm-12',
};

$divClasses = $divClasses ?? $defaultDivClasses;
@endphp

<div class="{{ $divClasses }}">
    <div class="form-floating form-floating-outline">
        <select id="{{ $id ?? $name }}" class="form-select" name="{{ $name }}{{ $multiple ? '[]' : '' }}"
            {{ $multiple ? 'multiple' : '' }} {{ $required ? "required" : "" }}>
            @foreach($options as $value => $optionLabel)
                <option value="{{ $value }}">{{ $optionLabel }}</option>
            @endforeach
        </select>
        <label for="{{ $id ?? $name }}">{{ $label }}</label>
    </div>
    <span class="invalid-feedback" id="{{ $id ?? $name }}_error" role="alert"></span>
</div>
