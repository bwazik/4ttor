<!-- Add Modal -->
<x-offcanvas offcanvasType="add" offcanvasTitle="{{ trans('main.addItem', ['item' => trans('admin/resources.resource')]) }}" action="{{ route('teacher.resources.insert') }}">
    <x-basic-input context="offcanvas" type="text" name="title_ar" label="{{ trans('main.title_ar') }}" placeholder="{{ trans('admin/resources.placeholders.title_ar') }}" required/>
    <x-basic-input context="offcanvas" type="text" name="title_en" label="{{ trans('main.title_en') }}" placeholder="{{ trans('admin/resources.placeholders.title_en') }}" required/>
    <x-select-input context="offcanvas" name="grade_id" label="{{ trans('main.grade') }}" :options="$grades" required/>
    <x-basic-input context="offcanvas" type="text" name="video_url" label="{{ trans('main.video_url') }}" placeholder="{{ trans('admin/resources.placeholders.video_url') }}"/>
    <x-text-area context="offcanvas" name="description" label="{{ trans('main.description') }}" placeholder="{{ trans('admin/resources.placeholders.description') }}" maxlength="500"/>
</x-offcanvas>
<!-- Edit Modal -->
<x-offcanvas offcanvasType="edit" offcanvasTitle="{{ trans('main.editItem', ['item' => trans('admin/resources.resource')]) }}" action="{{ route('teacher.resources.update') }}" id>
    <x-basic-input context="offcanvas" type="text" name="title_ar" label="{{ trans('main.title_ar') }}" placeholder="{{ trans('admin/resources.placeholders.title_ar') }}" required/>
    <x-basic-input context="offcanvas" type="text" name="title_en" label="{{ trans('main.title_en') }}" placeholder="{{ trans('admin/resources.placeholders.title_en') }}" required/>
    <x-select-input context="offcanvas" name="grade_id" label="{{ trans('main.grade') }}" :options="$grades" required/>
    <x-basic-input context="offcanvas" type="text" name="video_url" label="{{ trans('main.video_url') }}" placeholder="{{ trans('admin/resources.placeholders.video_url') }}"/>
    <x-text-area context="offcanvas" name="description" label="{{ trans('main.description') }}" placeholder="{{ trans('admin/resources.placeholders.description') }}" maxlength="500"/>
    <x-select-input context="offcanvas" name="is_active" label="{{ trans('main.status') }}" :options="[1 => trans('main.active'), 0 => trans('main.inactive')]"  required/>
</x-offcanvas>
<!-- Delete Modal -->
<x-modal modalType="delete" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/resources.resource')]) }}"
    action="{{ route('teacher.resources.delete') }}" id submitColor="danger" submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.delete-modal-body')
</x-modal>
