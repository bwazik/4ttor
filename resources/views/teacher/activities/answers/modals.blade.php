<!-- Add Offcanvas -->
<div class="offcanvas offcanvas-end" id="add-answer-modal">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title">{{ trans('main.addItem', ['item' => trans('admin/answers.answer')]) }}</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body flex-grow-1">
        <form id="add-answer-form" class="pt-0 row g-3" action="{{ route('admin.answers.insert', 555555555) }}" method="POST" autocomplete="off">
            @csrf
            <x-text-area context="offcanvas" name="answer_text_ar" label="{{ trans('admin/answers.answer_text_ar') }}" placeholder="{{ trans('admin/answers.placeholders.answer_text_ar') }}" maxlength=500 required/>
            <x-text-area context="offcanvas" name="answer_text_en" label="{{ trans('admin/answers.answer_text_en') }}" placeholder="{{ trans('admin/answers.placeholders.answer_text_en') }}" maxlength=500 required/>
            <x-select-input context="offcanvas" name="is_correct" label="{{ trans('main.is_correct') }}" :options="[1 => trans('main.correct'), 0 => trans('main.wrong')]" required/>
            <x-basic-input context="offcanvas" type="number" name="score" label="{{ trans('main.score') }}" placeholder="5" required/>
            <div class="col-sm-12">
                <button type="submit" id="submit" class="btn btn-primary me-sm-4 me-1">{{ trans('main.submit') }}</button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">{{ trans('main.cancel') }}</button>
            </div>
        </form>
    </div>
</div>
<!-- Edit Offcanvas -->
<div class="offcanvas offcanvas-end" id="edit-answer-modal">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title">{{ trans('main.editItem', ['item' => trans('admin/answers.answer')]) }}</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body flex-grow-1">
        <form id="edit-answer-form" class="pt-0 row g-3" action="{{ route('admin.answers.update') }}" method="POST" autocomplete="off">
            @csrf
            <input type="hidden" id="id" name="id">
            <x-text-area context="offcanvas" name="answer_text_ar" label="{{ trans('admin/answers.answer_text_ar') }}" placeholder="{{ trans('admin/answers.placeholders.answer_text_ar') }}" maxlength=500 required/>
            <x-text-area context="offcanvas" name="answer_text_en" label="{{ trans('admin/answers.answer_text_en') }}" placeholder="{{ trans('admin/answers.placeholders.answer_text_en') }}" maxlength=500 required/>
            <x-select-input context="offcanvas" name="is_correct" label="{{ trans('main.is_correct') }}" :options="[1 => trans('main.correct'), 0 => trans('main.wrong')]" required/>
            <x-basic-input context="offcanvas" type="number" name="score" label="{{ trans('main.score') }}" placeholder="5" required/>
            <div class="col-sm-12">
                <button type="submit" id="submit" class="btn btn-primary me-sm-4 me-1">{{ trans('main.submit') }}</button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">{{ trans('main.cancel') }}</button>
            </div>
        </form>
    </div>
</div>
<<!-- Delete Modal -->
<div class="modal fade" id="delete-answer-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ trans('main.deleteItem', ['item' => trans('admin/answers.answer')]) }}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="delete-answer-form" action="{{ route('admin.answers.delete') }}" method="POST" autocomplete="off">
                @csrf
                <input type="hidden" id="id" name="id">
                <div class="modal-body">
                    @include('partials.delete-modal-body')
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ trans('main.cancel') }}</button>
                    <button type="submit" id="submit" class="btn btn-danger">{{ trans('main.yes_delete') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
