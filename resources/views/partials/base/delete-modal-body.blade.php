<div>
    <label for="itemToDelete" class="form-label">{{ trans('main.delete_warning') }}</label>
    <input type="text" id="itemToDelete" class="form-control" value="{{ trans("main.items") }}: 0" disabled />
</div>
<i class="bi bi-exclamation-triangle-fill fs-1 text-warning"></i>
<p class="mt-3">{{ trans('main.confirm_deletion') }}</p>
<p class="text-muted">{{ trans('main.cannot_undo') }}</p>
