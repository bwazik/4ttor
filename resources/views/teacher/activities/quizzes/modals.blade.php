<!-- Add Offcanvas -->
<x-offcanvas offcanvasType="add" offcanvasTitle="{{ trans('main.addItem', ['item' => trans('admin/quizzes.quiz')]) }}" action="{{ route('teacher.quizzes.insert') }}">
    <x-basic-input context="offcanvas" type="text" name="name_ar" label="{{ trans('main.name_ar') }}" placeholder="{{ trans('admin/quizzes.placeholders.name_ar') }}" required/>
    <x-basic-input context="offcanvas" type="text" name="name_en" label="{{ trans('main.name_en') }}" placeholder="{{ trans('admin/quizzes.placeholders.name_en') }}" required/>
    <x-select-input context="offcanvas" name="grade_id" label="{{ trans('main.grade') }}" :options="$grades" required/>
    <x-select-input context="offcanvas" name="groups" label="{{ trans('main.group') }}" multiple required/>
    <x-basic-input context="offcanvas" type="number" name="duration" label="{{ trans('main.duration') }}" placeholder="60" required/>
    <x-basic-input context="offcanvas" type="text" name="start_time" classes="flatpickr-date-time" label="{{ trans('main.start_time') }}" placeholder="YYYY-MM-DD" required/>
</x-offcanvas>
<!-- Edit Offcanvas -->
<x-offcanvas offcanvasType="edit" offcanvasTitle="{{ trans('main.editItem', ['item' => trans('admin/quizzes.quiz')]) }}" action="{{ route('teacher.quizzes.update') }}" id>
    <x-basic-input context="offcanvas" type="text" name="name_ar" label="{{ trans('main.name_ar') }}" placeholder="{{ trans('admin/quizzes.placeholders.name_ar') }}" required/>
    <x-basic-input context="offcanvas" type="text" name="name_en" label="{{ trans('main.name_en') }}" placeholder="{{ trans('admin/quizzes.placeholders.name_en') }}" required/>
    <x-select-input context="offcanvas" name="grade_id" label="{{ trans('main.grade') }}" :options="$grades" required/>
    <x-select-input context="offcanvas" name="groups" label="{{ trans('main.group') }}" :options="$groups" multiple required/>
    <x-basic-input context="offcanvas" type="number" name="duration" label="{{ trans('main.duration') }}" placeholder="60" required/>
    <x-basic-input context="offcanvas" type="text" name="start_time" classes="flatpickr-date-time" label="{{ trans('main.start_time') }}" placeholder="YYYY-MM-DD" required/>
</x-offcanvas>
<!-- Delete Modal -->
<x-modal modalType="delete" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/quizzes.quiz')]) }}"
    action="{{ route('teacher.quizzes.delete') }}" id submitColor="danger" submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.delete-modal-body')
</x-modal>
<!-- Delete Selected Modal -->
<x-modal modalType="delete-selected" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/quizzes.selectedQuizzes')]) }}"
    action="{{ route('teacher.quizzes.deleteSelected') }}" ids submitColor="danger" submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.delete-modal-body')
</x-modal>
