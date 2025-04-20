<!-- Add Modal -->
<x-offcanvas offcanvasType="add" offcanvasTitle="{{ trans('main.addItem', ['item' => trans('admin/subjects.subject')]) }}" action="{{ route('admin.subjects.insert') }}">
    <x-basic-input context="offcanvas" type="text" name="name_ar" label="{{ trans('main.name_ar') }}" placeholder="{{ trans('admin/subjects.placeholders.name_ar') }}" required/>
    <x-basic-input context="offcanvas" type="text" name="name_en" label="{{ trans('main.name_en') }}" placeholder="{{ trans('admin/subjects.placeholders.name_en') }}" required/>
    <x-select-input context="offcanvas" name="is_active" label="{{ trans('main.status') }}" :options="[1 => trans('main.active'), 0 => trans('main.inactive')]"  required/>
</x-offcanvas>
<!-- Edit Modal -->
<x-offcanvas offcanvasType="edit" offcanvasTitle="{{ trans('main.editItem', ['item' => trans('admin/subjects.subject')]) }}" action="{{ route('admin.subjects.update') }}" id>
    <x-basic-input context="offcanvas" type="text" name="name_ar" label="{{ trans('main.name_ar') }}" placeholder="{{ trans('admin/subjects.placeholders.name_ar') }}" required/>
    <x-basic-input context="offcanvas" type="text" name="name_en" label="{{ trans('main.name_en') }}" placeholder="{{ trans('admin/subjects.placeholders.name_en') }}" required/>
    <x-select-input context="offcanvas" name="is_active" label="{{ trans('main.status') }}" :options="[1 => trans('main.active'), 0 => trans('main.inactive')]"  required/>
</x-offcanvas>
<!-- Delete Modal -->
<x-modal modalType="delete" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/subjects.subject')]) }}"
    action="{{ route('admin.subjects.delete') }}" id submitColor="danger" submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.delete-modal-body')
</x-modal>
<!-- Delete Selected Modal -->
<x-modal modalType="delete-selected" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/subjects.selectedSubjects')]) }}"
    action="{{ route('admin.subjects.deleteSelected') }}" ids submitColor="danger" submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.delete-modal-body')
</x-modal>
