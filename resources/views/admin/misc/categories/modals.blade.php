<!-- Add Modal -->
<x-offcanvas offcanvasType="add" offcanvasTitle="{{ trans('main.addItem', ['item' => trans('admin/categories.category')]) }}"
    action="{{ route('admin.categories.insert') }}">
    <x-basic-input context="offcanvas" type="text" name="name_ar" label="{{ trans('main.name_ar') }}" placeholder="{{ trans('admin/categories.placeholders.name_ar') }}" required/>
    <x-basic-input context="offcanvas" type="text" name="name_en" label="{{ trans('main.name_en') }}" placeholder="{{ trans('admin/categories.placeholders.name_en') }}" required/>
    <x-basic-input context="offcanvas" type="text" name="slug" label="{{ trans('main.slug') }}" placeholder="{{ trans('admin/categories.placeholders.slug') }}" required/>
    <x-basic-input context="offcanvas" type="text" name="icon" label="{{ trans('main.icon') }}" placeholder="{{ trans('admin/categories.placeholders.icon') }}"/>
    <x-basic-input context="offcanvas" type="number" name="order" label="{{ trans('main.order') }}" placeholder="1" required/>
    <x-text-area context="offcanvas" name="description_ar" label="{{ trans('main.description_ar') }}" placeholder="{{ trans('main.placeholders.description_ar') }}"/>
    <x-text-area context="offcanvas" name="description_en" label="{{ trans('main.description_en') }}" placeholder="{{ trans('main.placeholders.description_en') }}"/>
</x-offcanvas>
<!-- Edit Modal -->
<x-offcanvas offcanvasType="edit" offcanvasTitle="{{ trans('main.editItem', ['item' => trans('admin/categories.category')]) }}"
    action="{{ route('admin.categories.update') }}" id>
    <x-basic-input context="offcanvas" type="text" name="name_ar" label="{{ trans('main.name_ar') }}" placeholder="{{ trans('admin/categories.placeholders.name_ar') }}" required/>
    <x-basic-input context="offcanvas" type="text" name="name_en" label="{{ trans('main.name_en') }}" placeholder="{{ trans('admin/categories.placeholders.name_en') }}" required/>
    <x-basic-input context="offcanvas" type="text" name="slug" label="{{ trans('main.slug') }}" placeholder="{{ trans('admin/categories.placeholders.slug') }}" required/>
    <x-basic-input context="offcanvas" type="text" name="icon" label="{{ trans('main.icon') }}" placeholder="{{ trans('admin/categories.placeholders.icon') }}"/>
    <x-basic-input context="offcanvas" type="number" name="order" label="{{ trans('main.order') }}" placeholder="1" required/>
    <x-text-area context="offcanvas" name="description_ar" label="{{ trans('main.description_ar') }}" placeholder="{{ trans('main.placeholders.description_ar') }}"/>
    <x-text-area context="offcanvas" name="description_en" label="{{ trans('main.description_en') }}" placeholder="{{ trans('main.placeholders.description_en') }}"/>
</x-offcanvas>
<!-- Delete Modal -->
<x-modal modalType="delete" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/categories.category')]) }}"
    action="{{ route('admin.categories.delete') }}" id submitColor="danger" submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.delete-modal-body')
</x-modal>
<!-- Delete Selected Modal -->
<x-modal modalType="delete-selected" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/categories.selectedCategories')]) }}"
    action="{{ route('admin.categories.deleteSelected') }}" submitColor="danger" ids submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.delete-modal-body')
</x-modal>
