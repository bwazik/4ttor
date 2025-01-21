@props([
    'type',
    'name',
    'label',
    'placeholder' => '',
    'required' => false,
    'readonly' => false,
    'date' => false,
    'id' => null,
    'classes' => null,
    'context' => null,
    'divClasses' => null,
    'price' => false,
    'value' => null,
])

@php
    $defaultDivClasses = match ($context) {
        'modal' => 'col-12 col-md-6',
        'offcanvas' => 'col-sm-12 mt-4',
        default => 'col-sm-12',
    };

    $divClasses = $divClasses ?? $defaultDivClasses;
@endphp

@if ($type === 'password')
    <div class="form-password-toggle {{ $divClasses }}">
        <div class="input-group input-group-merge">
            <div class="form-floating form-floating-outline">
                <input type="{{ $type }}" id="{{ $id ?? $name }}" class="form-control"
                    name="{{ $name }}" value="{{ e($value) }}" {{ $required ? 'required' : '' }} {{ $readonly ? 'readonly' : '' }} placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                    aria-label="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                <label for="{{ $id ?? $name }}">{{ $label }}</label>
            </div>
            <span class="input-group-text cursor-pointer"><i class="ri-eye-off-line"></i></span>
        </div>
        <span class="invalid-feedback" id="{{ $id ?? $name }}_error" role="alert"></span>
    </div>
@elseif($name === 'phone')
    <div class="{{ $divClasses }}">
        <div class="input-group input-group-merge">
            @if(app()->getLocale() == 'ar')
            <div class="form-floating form-floating-outline">
                <input type="{{ $type }}" id="{{ $id ?? $name }}" class="form-control"
                    name="{{ $name }}" value="{{ e($value) }}" {{ $required ? 'required' : '' }} {{ $readonly ? 'readonly' : '' }} placeholder="{{ $placeholder }}"
                    aria-label="{{ $placeholder }}" @if($name === 'phone') step="1" @endif/>
                <label for="{{ $id ?? $name }}">{{ $label }}</label>
            </div>
            <span class="input-group-text">EG (+2)</span>
            @else
            <span class="input-group-text">EG (+2)</span>
            <div class="form-floating form-floating-outline">
                <input type="{{ $type }}" id="{{ $id ?? $name }}" class="form-control"
                    name="{{ $name }}" value="{{ e($value) }}" {{ $required ? 'required' : '' }} {{ $readonly ? 'readonly' : '' }} placeholder="{{ $placeholder }}"
                    aria-label="{{ $placeholder }}" step="1"/>
                <label for="{{ $id ?? $name }}">{{ $label }}</label>
            </div>
            @endif
        </div>
        <span class="invalid-feedback" id="{{ $id ?? $name }}_error" role="alert"></span>
        <div class="form-text">لازم يبقي عليه واتساب</div>
    </div>
@elseif($price)
    <div class="{{ $divClasses }}">
        <div class="input-group input-group-merge">
            <div class="form-floating form-floating-outline">
                <input type="{{ $type }}" id="{{ $id ?? $name }}" class="form-control{{ $classes ? ' ' . $classes : '' }}"
                    name="{{ $name }}" value="{{ e($value) }}" {{ $required ? 'required' : '' }} {{ $readonly ? 'readonly' : '' }} placeholder="{{ $placeholder }}"
                    aria-label="{{ $placeholder }}"/>
                <label for="{{ $id ?? $name }}">{{ $label }}</label>
            </div>
            <span class="input-group-text">{{ trans('main.currency') }}</span>
        </div>
        <span class="invalid-feedback" id="{{ $id ?? $name }}_error" role="alert"></span>
    </div>
@elseif($name === 'username')
    <div class="{{ $divClasses }}">
        <div class="input-group input-group-merge">
            <span class="input-group-text">@</span>
            <div class="form-floating form-floating-outline">
                <input type="{{ $type }}" id="{{ $id ?? $name }}" class="form-control{{ $classes ? ' ' . $classes : '' }}"
                    name="{{ $name }}" value="{{ e($value) }}" {{ $required ? 'required' : '' }} {{ $readonly ? 'readonly' : '' }} placeholder="{{ $placeholder }}"
                    aria-label="{{ $placeholder }}"/>
                <label for="{{ $id ?? $name }}">{{ $label }}</label>
            </div>
        </div>
        <span class="invalid-feedback" id="{{ $id ?? $name }}_error" role="alert"></span>
    </div>
@else
    <div class="{{ $divClasses }}">
        <div class="form-floating form-floating-outline">
            <input type="{{ $type }}" id="{{ $id ?? $name }}" class="form-control{{ $classes ? ' ' . $classes : '' }}"
                name="{{ $name }}" value="{{ e($value) }}" {{ $required ? 'required' : '' }} {{ $readonly ? 'readonly' : '' }} placeholder="{{ $placeholder }}"
                aria-label="{{ $placeholder }}"/>
            <label for="{{ $id ?? $name }}">{{ $label }}</label>
        </div>
        <span class="invalid-feedback" id="{{ $id ?? $name }}_error" role="alert"></span>
    </div>
@endif
