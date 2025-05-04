@props([
    'name',
    'label',
    'options' => [],
    'required' => false,
    'readonly' => false,
    'disabled' => false,
    'id' => null,
    'multiple' => false,
    'context' => null,
    'divClasses' => null,
    'selected' => [],
])

@php
$defaultDivClasses = match ($context) {
    'modal' => 'col-12 col-md-6',
    'offcanvas' => 'col-sm-12 mb-4',
    default => 'col-sm-12',
};

$divClasses = $divClasses ?? $defaultDivClasses;

$selectedValues = is_array($selected) ? $selected : [$selected];
@endphp

<div class="{{ $divClasses }}">
    <div class="form-floating form-floating-outline">
        <div class="select2-primary">
            <select id="{{ $id ?? $name }}" class="form-select" name="{{ $name }}{{ $multiple ? '[]' : '' }}"
            {{ $multiple ? 'multiple' : '' }} {{ $required ? "required" : "" }} {{ $readonly ? 'readonly' : '' }} {{ $disabled ? 'disabled' : '' }}>
            @foreach($options as $value => $optionLabel)
                <option value="{{ $value }}" {{ in_array($value, $selectedValues) ? 'selected' : '' }}>
                    {{ $optionLabel }}
                </option>
            @endforeach
            </select>
        </div>
        <label for="{{ $id ?? $name }}">{{ $label }}</label>
    </div>
    <span class="invalid-feedback" id="{{ $id ?? $name }}_error" role="alert"></span>
    @if($name === 'is_exempted')
        <div class="form-text">لو حضرتك مخترتش هيبقي غير معفي تلقائياٌ</div>
    @endif
</div>
