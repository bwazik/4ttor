<!-- Add Modal -->
<x-offcanvas offcanvasType="add" offcanvasTitle="{{ trans('main.addItem', ['item' => trans('admin/grades.grade')]) }}" action="{{ route('admin.grades.insert') }}">
    <x-basic-input type="text" name="name_ar" label="{{ trans('main.name_ar') }}" placeholder="{{ trans('admin/grades.placeholders.name_ar') }}" required/>
    <x-basic-input type="text" name="name_en" label="{{ trans('main.name_en') }}" placeholder="{{ trans('admin/grades.placeholders.name_en') }}" required/>
    <x-select-input name="is_active" label="{{ trans('main.status') }}" :options="[1 => trans('main.active'), 0 => trans('main.inactive')]"  required/>
    <x-select-input name="stage_id" label="{{ trans('admin/stages.stage') }}" :options="$stages" required/>
</x-offcanvas>
<!-- Edit Modal -->
<x-offcanvas offcanvasType="edit" offcanvasTitle="{{ trans('main.editItem', ['item' => trans('admin/grades.grade')]) }}" action="{{ route('admin.grades.update') }}" id>
    <x-basic-input type="text" name="name_ar" label="{{ trans('main.name_ar') }}" placeholder="{{ trans('admin/grades.placeholders.name_ar') }}" required/>
    <x-basic-input type="text" name="name_en" label="{{ trans('main.name_en') }}" placeholder="{{ trans('admin/grades.placeholders.name_en') }}" required/>
    <x-select-input name="is_active" label="{{ trans('main.status') }}" :options="[1 => trans('main.active'), 0 => trans('main.inactive')]"  required/>
    <x-select-input name="stage_id" label="{{ trans('admin/stages.stage') }}" :options="$stages"  required/>
</x-offcanvas>
<!-- Delete Modal -->
<x-modal modalType="delete" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/grades.grade')]) }}"
    action="{{ route('admin.grades.delete') }}" id submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.base.delete-modal-body')
</x-modal>
<!-- Delete Selected Modal -->
<x-modal modalType="delete-selected" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/grades.selectedGrades')]) }}"
    action="{{ route('admin.grades.deleteSelected') }}" ids submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.base.delete-modal-body')
</x-modal>
