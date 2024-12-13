@props([
    'offcanvasType',
    'offcanvasTitle',
    'action',
    'id' => false,
])

<div class="offcanvas offcanvas-end" id="{{ $offcanvasType}}-modal">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title">{{ $offcanvasTitle }}</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body flex-grow-1">
        <form id="{{ $offcanvasType}}-form" class="pt-0 row g-3" action="{{ $action }}" method="POST">
            @csrf
            @if($id)
                <input type="hidden" id="id" name="id">
            @endif
            {{ $slot }}
            <div class="col-sm-12">
                <button type="submit" id="submit" class="btn btn-primary me-sm-4 me-1">{{ trans('main.submit') }}</button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">{{ trans('main.cancel') }}</button>
            </div>
        </form>
    </div>
</div>
