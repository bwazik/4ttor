<!-- Add Modal -->
<x-modal modalType="add" modalSize="modal-lg"
    modalTitle="{{ trans('main.addItem', ['item' => trans('admin/assignments.assignment')]) }}"
    action="{{ route('admin.assignments.insert') }}" hasFiles>
    <div class="row g-5">
        <x-basic-input context="modal" type="text" name="title_ar" label="{{ trans('main.title_ar') }}" placeholder="{{ trans('admin/assignments.placeholders.title_ar') }}" required/>
        <x-basic-input context="modal" type="text" name="title_en" label="{{ trans('main.title_en') }}" placeholder="{{ trans('admin/assignments.placeholders.title_en') }}" required/>
        <x-select-input context="modal" name="teacher_id" label="{{ trans('main.teacher') }}" :options="$teachers" required/>
        <x-select-input context="modal" name="grade_id" label="{{ trans('main.grade') }}" required/>
        <x-select-input divClasses="col-12" name="groups" label="{{ trans('main.group') }}" multiple required/>
        <x-basic-input context="modal" type="text" name="deadline" classes="flatpickr-date-time" label="{{ trans('main.deadline') }}" placeholder="YYYY-MM-DD" required/>
        <x-basic-input context="modal" type="number" name="score" label="{{ trans('main.score') }}" value="100" placeholder="100" required/>
        <x-text-area divClasses="col-12" name="description" label="{{ trans('main.description') }}" placeholder="{{ trans('admin/assignments.placeholders.description') }}"/>
    </div>
</x-modal>
<!-- Edit Modal -->
<x-modal modalType="edit" modalSize="modal-lg" modalTitle="{{ trans('main.editItem', ['item' => trans('admin/assignments.assignment')]) }}" action="{{ route('admin.assignments.update') }}" id meeting_id>
    <div class="row g-5">
        <x-basic-input context="modal" type="text" name="title_ar" label="{{ trans('main.title_ar') }}" placeholder="{{ trans('admin/assignments.placeholders.title_ar') }}" required/>
        <x-basic-input context="modal" type="text" name="title_en" label="{{ trans('main.title_en') }}" placeholder="{{ trans('admin/assignments.placeholders.title_en') }}" required/>
        <x-select-input context="modal" name="teacher_id" label="{{ trans('main.teacher') }}" :options="$teachers" required/>
        <x-select-input context="modal" name="grade_id" label="{{ trans('main.grade') }}" :options="$grades" required/>
        <x-select-input divClasses="col-12" name="groups" label="{{ trans('main.group') }}" :options="$groups" multiple required/>
        <x-basic-input context="modal" type="text" name="deadline" classes="flatpickr-date-time" label="{{ trans('main.deadline') }}" placeholder="YYYY-MM-DD" required/>
        <x-basic-input context="modal" type="number" name="score" label="{{ trans('main.score') }}" placeholder="100" required/>
        <x-text-area divClasses="col-12" name="description" label="{{ trans('main.description') }}" placeholder="{{ trans('admin/receipts.placeholder') }}"/>
    </div>
</x-modal>
<!-- Delete Modal -->
<x-modal modalType="delete" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/assignments.assignment')]) }}"
    action="{{ route('admin.assignments.delete') }}" id submitColor="danger" submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.delete-modal-body')
</x-modal>
<!-- Delete Selected Modal -->
<x-modal modalType="delete-selected" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/assignments.selectedAssignments')]) }}"
    action="{{ route('admin.assignments.deleteSelected') }}" ids submitColor="danger" submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.delete-modal-body')
</x-modal>
