<!-- Add Modal -->
<x-offcanvas offcanvasType="add" offcanvasTitle="{{ trans('main.addItem', ['item' => trans('admin/lessons.lesson')]) }}" action="{{ route('teacher.lessons.insert') }}">
    <x-basic-input context="offcanvas" type="text" name="title_ar" label="{{ trans('main.title_ar') }}" placeholder="{{ trans('admin/lessons.placeholders.title_ar') }}" required/>
    <x-basic-input context="offcanvas" type="text" name="title_en" label="{{ trans('main.title_en') }}" placeholder="{{ trans('admin/lessons.placeholders.title_en') }}" required/>
    <x-select-input context="offcanvas" name="grade_id" label="{{ trans('main.grade') }}" :options="$grades"/>
    <x-select-input context="offcanvas" name="group_id" label="{{ trans('main.group') }}" required/>
    <x-basic-input context="offcanvas" type="text" name="date" classes="flatpickr-date" label="{{ trans('main.date') }}" placeholder="YYYY-MM-DD" value="{{ now()->format('Y-m-d') }}" required/>
    <x-basic-input context="offcanvas" type="text" name="time" classes="flatpickr-timeB" label="{{ trans('main.time') }}" placeholder="1:00" required/>
    <x-select-input context="offcanvas" name="status" label="{{ trans('main.status') }}" :options="[1 => trans('main.scheduled'), 2 => trans('main.completed'), 3 => trans('main.canceled')]"  required/>
</x-offcanvas>
<!-- Edit Modal -->
<x-offcanvas offcanvasType="edit" offcanvasTitle="{{ trans('main.editItem', ['item' => trans('admin/lessons.lesson')]) }}" action="{{ route('teacher.lessons.update') }}" id>
    <x-basic-input context="offcanvas" type="text" name="title_ar" label="{{ trans('main.title_ar') }}" placeholder="{{ trans('admin/lessons.placeholders.title_ar') }}" required/>
    <x-basic-input context="offcanvas" type="text" name="title_en" label="{{ trans('main.title_en') }}" placeholder="{{ trans('admin/lessons.placeholders.title_en') }}" required/>
    <x-select-input context="offcanvas" name="group_id" label="{{ trans('main.group') }}" :options="$groups" required/>
    <x-basic-input context="offcanvas" type="text" name="date" classes="flatpickr-date" label="{{ trans('main.date') }}" placeholder="YYYY-MM-DD" value="{{ now()->format('Y-m-d') }}" required/>
    <x-basic-input context="offcanvas" type="text" name="time" classes="flatpickr-timeB" label="{{ trans('main.time') }}" placeholder="1:00" required/>
    <x-select-input context="offcanvas" name="status" label="{{ trans('main.status') }}" :options="[1 => trans('main.scheduled'), 2 => trans('main.completed'), 3 => trans('main.canceled')]"  required/>
</x-offcanvas>
<!-- Delete Modal -->
<x-modal modalType="delete" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/lessons.lesson')]) }}"
    action="{{ route('teacher.lessons.delete') }}" id submitColor="danger" submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.delete-modal-body')
</x-modal>
<!-- Delete Selected Modal -->
<x-modal modalType="delete-selected" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/lessons.selectedLessons')]) }}"
    action="{{ route('teacher.lessons.deleteSelected') }}" ids submitColor="danger" submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.delete-modal-body')
</x-modal>
