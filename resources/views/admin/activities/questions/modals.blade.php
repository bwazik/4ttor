<!-- Add Offcanvas -->
<x-offcanvas offcanvasType="add" offcanvasTitle="{{ trans('main.addItem', ['item' => trans('admin/questions.question')]) }}" action="{{ route('admin.questions.insert', $quizId) }}">
    <x-text-area context="offcanvas" name="question_text_ar" label="{{ trans('admin/questions.question_text_ar') }}" placeholder="{{ trans('admin/receipts.placeholder.question_text_ar') }}" maxlength=750 required/>
    <x-text-area context="offcanvas" name="question_text_en" label="{{ trans('admin/questions.question_text_en') }}" placeholder="{{ trans('admin/receipts.placeholder.question_text_en') }}" maxlength=750 required/>
</x-offcanvas>
<!-- Edit Offcanvas -->
<x-offcanvas offcanvasType="edit" offcanvasTitle="{{ trans('main.editItem', ['item' => trans('admin/questions.question')]) }}" action="{{ route('admin.questions.update') }}" id>
    <x-text-area context="offcanvas" name="question_text_ar" label="{{ trans('admin/questions.question_text_ar') }}" placeholder="{{ trans('admin/receipts.placeholder.question_text_ar') }}" maxlength=750 required/>
    <x-text-area context="offcanvas" name="question_text_en" label="{{ trans('admin/questions.question_text_en') }}" placeholder="{{ trans('admin/receipts.placeholder.question_text_en') }}" maxlength=750 required/>
</x-offcanvas>
<!-- Delete Modal -->
<x-modal modalType="delete" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/questions.question')]) }}"
    action="{{ route('admin.questions.delete') }}" id submitColor="danger" submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.delete-modal-body')
</x-modal>
<!-- Delete Selected Modal -->
<x-modal modalType="delete-selected" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/questions.selectedQuestions')]) }}"
    action="{{ route('admin.questions.deleteSelected') }}" ids submitColor="danger" submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.delete-modal-body')
</x-modal>
