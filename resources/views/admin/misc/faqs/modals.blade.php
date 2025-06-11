<!-- Add Modal -->
<x-modal modalType="add" modalSize="modal-lg" modalTitle="{{ trans('main.addItem', ['item' => trans('admin/faqs.faq')]) }}"
    action="{{ route('admin.faqs.insert') }}">
    <div class="row g-5">
        <x-select-input context="modal" name="category_id" label="{{ trans('admin/categories.category') }}" :options="$categoryIds" required/>
        <x-select-input context="modal" name="audience" label="{{ trans('main.audience') }}" :options="
        [
            1 => trans('admin/teachers.teachers'),
            2 => trans('admin/students.students'),
            3 => trans('admin/assistants.assistants'),
            4 => trans('admin/parents.parents'),
            5 => trans('admin/teachers.teachers') .' & '. trans('admin/assistants.assistants'),
            6 => trans('admin/students.students') .' & '. trans('admin/parents.parents'),
            7 => trans('main.all'),
        ]"/>
        <x-select-input context="modal" name="is_active" label="{{ trans('main.status') }}" :options="[1 => trans('main.active'), 0 => trans('main.inactive')]"/>
        <x-select-input context="modal" name="is_at_landing" label="{{ trans('main.is_at_landing') }}" :options="[1 => trans('main.yes'), 0 => trans('main.no')]"/>
        <x-basic-input divClasses="col-12" type="number" name="order" label="{{ trans('main.order') }}" placeholder="1" required/>
        <x-text-area context="modal" name="question_ar" label="{{ trans('admin/faqs.placeholders.question_ar') }}" placeholder="{{ trans('admin/faqs.placeholders.question_ar') }}" maxlength="500"/>
        <x-text-area context="modal" name="question_en" label="{{ trans('admin/faqs.placeholders.question_en') }}" placeholder="{{ trans('admin/faqs.placeholders.question_en') }}" maxlength="500"/>
        <x-text-area context="modal" name="answer_ar" label="{{ trans('admin/faqs.placeholders.answer_ar') }}" placeholder="{{ trans('admin/faqs.placeholders.answer_ar') }}" maxlength="500"/>
        <x-text-area context="modal" name="answer_en" label="{{ trans('admin/faqs.placeholders.answer_en') }}" placeholder="{{ trans('admin/faqs.placeholders.answer_en') }}" maxlength="500"/>
    </div>
</x-modal>
<!-- Edit Modal -->
<x-modal modalType="edit" modalSize="modal-lg" modalTitle="{{ trans('main.editItem', ['item' => trans('admin/faqs.faq')]) }}"
    action="{{ route('admin.faqs.update') }}" id>
    <div class="row g-5">
        <x-select-input context="modal" name="category_id" label="{{ trans('admin/categories.category') }}" :options="$categoryIds" required/>
        <x-select-input context="modal" name="audience" label="{{ trans('main.audience') }}" :options="
        [
            1 => trans('admin/teachers.teachers'),
            2 => trans('admin/students.students'),
            3 => trans('admin/assistants.assistants'),
            4 => trans('admin/parents.parents'),
            5 => trans('admin/teachers.teachers') .' & '. trans('admin/assistants.assistants'),
            6 => trans('admin/students.students') .' & '. trans('admin/parents.parents'),
            7 => trans('main.all'),
        ]"/>
        <x-select-input context="modal" name="is_active" label="{{ trans('main.status') }}" :options="[1 => trans('main.active'), 0 => trans('main.inactive')]"/>
        <x-select-input context="modal" name="is_at_landing" label="{{ trans('main.is_at_landing') }}" :options="[1 => trans('main.yes'), 0 => trans('main.no')]"/>
        <x-basic-input divClasses="col-12" type="number" name="order" label="{{ trans('main.order') }}" placeholder="1" required/>
        <x-text-area context="modal" name="question_ar" label="{{ trans('admin/faqs.placeholders.question_ar') }}" placeholder="{{ trans('admin/faqs.placeholders.question_ar') }}" maxlength="500"/>
        <x-text-area context="modal" name="question_en" label="{{ trans('admin/faqs.placeholders.question_en') }}" placeholder="{{ trans('admin/faqs.placeholders.question_en') }}" maxlength="500"/>
        <x-text-area context="modal" name="answer_ar" label="{{ trans('admin/faqs.placeholders.answer_ar') }}" placeholder="{{ trans('admin/faqs.placeholders.answer_ar') }}" maxlength="500"/>
        <x-text-area context="modal" name="answer_en" label="{{ trans('admin/faqs.placeholders.answer_en') }}" placeholder="{{ trans('admin/faqs.placeholders.answer_en') }}" maxlength="500"/>
    </div>
</x-modal>
<!-- Delete Modal -->
<x-modal modalType="delete" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/faqs.faq')]) }}"
    action="{{ route('admin.faqs.delete') }}" id submitColor="danger" submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.delete-modal-body')
</x-modal>
<!-- Delete Selected Modal -->
<x-modal modalType="delete-selected" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/faqs.selectedFaqs')]) }}"
    action="{{ route('admin.faqs.deleteSelected') }}" submitColor="danger" ids submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.delete-modal-body')
</x-modal>
