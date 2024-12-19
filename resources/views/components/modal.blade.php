@props([
    'modalType',
    'modalTitle',
    'action',
    'id' => false,
    'ids' => false,
    'submitButton' => trans('main.submit'),
    'submitColor' => 'primary',
    'modalSize' => null,
    'hasFiles' => false,
])

<div class="modal fade" id="{{ $modalType}}-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog {{ $modalSize }}" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ $modalTitle}}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="{{ $modalType}}-form" action="{{ $action }}" method="POST" {{ $hasFiles ? 'enctype=multipart/form-data' : '' }} autocomplete="off">
                @csrf
                @if($id)
                    <input type="hidden" id="id" name="id">
                @elseif($ids)
                    <div id="ids-container"></div>
                @endif
                <div class="modal-body">
                    {{ $slot }}
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ trans('main.cancel') }}</button>
                    <button type="submit" id="submit" class="btn btn-{{ $submitColor }}">{{ $submitButton }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
