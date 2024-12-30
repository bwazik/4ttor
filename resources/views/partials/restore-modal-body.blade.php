<div>
    <label for="itemToRestore" class="form-label">{{ trans('main.restore_warning') }}</label>
    <input type="text" id="itemToRestore" class="form-control" value="{{ trans("main.items") }}: 0" disabled />
</div>
<p class="mt-3">{{ trans('main.confirm_restoration') }}</p>
<p class="text-muted">{{ trans('main.restore_note') }}</p>
