@props([
    'name',
    'label',
    'placeholder' => '',
    'required' => false,
    'id' => null,
    'context' => null,
    'divClasses' => null,
    'maxlength' => 255,
])

@php
    $defaultDivClasses = match ($context) {
        'modal' => 'col-12 col-md-6',
        'offcanvas' => 'col-sm-12 mb-4',
        default => 'col-sm-12',
    };

    $divClasses = $divClasses ?? $defaultDivClasses;
@endphp

<div class="{{ $divClasses }}">
    <div class="form-floating form-floating-outline">
        <textarea id="{{ $id ?? $name }}" class="form-control h-px-100" name="{{ $name }}" {{ $required ? 'required' : '' }} placeholder="{{ $placeholder }}" aria-label="{{ $placeholder }}" maxlength="{{ $maxlength }}"></textarea>
        <label for="{{ $id ?? $name }}">{{ $label }}</label>
    </div>
    <span class="invalid-feedback" id="{{ $id ?? $name }}_error" role="alert"></span>
</div>

