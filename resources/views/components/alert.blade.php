@props([
    'type' => 'info',
    'dismissible' => true,
    'icon' => 'openai',
    'message' => null,
])

<div class="alert alert-{{ $type }} {{ $dismissible ? 'alert-dismissible' : '' }}" role="alert">
    <div class="d-flex align-items-center">
        <i class="ri ri-{{ $icon }}-line me-2"></i>
        @if($message)
            <span>{{ $message }}</span>
        @endif
        {{ $slot }}
        @if($icon == 'openai')
            <button type="button" class="btn btn-sm btn-outline-{{ $type }} ms-2" data-bs-toggle="tooltip" data-bs-original-title="{{ trans('main.reportIssueTooltip') }}">
                <i class="ri ri-error-warning-line"></i>
                <span class="d-none d-sm-inline-block ms-1">{{ trans('main.reportIssue') }}</span>
            </button>
        @endif
    </div>
    @if ($dismissible)
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ trans('quiz.close') }}"></button>
    @endif
</div>
