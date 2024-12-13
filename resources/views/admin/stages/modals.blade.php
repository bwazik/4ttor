<!-- Add Modal -->
<x-offcanvas offcanvasType="add" offcanvasTitle="{{ trans('main.addItem', ['item' => trans('admin/stages.stage')]) }}" action="{{ route('admin.stages.insert') }}">
    <x-basic-input type="text" name="name_ar" label="{{ trans('main.name_ar') }}" placeholder="{{ trans('admin/stages.placeholders.name_ar') }}" required/>
    <x-basic-input type="text" name="name_en" label="{{ trans('main.name_en') }}" placeholder="{{ trans('admin/stages.placeholders.name_en') }}" required/>
    <x-select-input name="is_active" label="{{ trans('main.status') }}" :options="[1 => trans('main.active'), 0 => trans('main.inactive')]"  required/>
</x-offcanvas>
<!-- Edit Modal -->
<x-offcanvas offcanvasType="edit" offcanvasTitle="{{ trans('main.editItem', ['item' => trans('admin/stages.stage')]) }}" action="{{ route('admin.stages.update') }}" id>
    <x-basic-input type="text" name="name_ar" label="{{ trans('main.name_ar') }}" placeholder="{{ trans('admin/stages.placeholders.name_ar') }}" required/>
    <x-basic-input type="text" name="name_en" label="{{ trans('main.name_en') }}" placeholder="{{ trans('admin/stages.placeholders.name_en') }}" required/>
    <x-select-input name="is_active" label="{{ trans('main.status') }}" :options="[1 => trans('main.active'), 0 => trans('main.inactive')]"  required/>
</x-offcanvas>
<!-- Delete Modal -->
<x-modal modalType="delete" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/stages.stage')]) }}"
    action="{{ route('admin.stages.delete') }}" id submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.base.delete-modal-body')
</x-modal>
<!-- Delete Selected Modal -->
<x-modal modalType="delete-selected" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/stages.selectedStages')]) }}"
    action="{{ route('admin.stages.deleteSelected') }}" ids submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.base.delete-modal-body')
</x-modal>
