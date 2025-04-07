<!-- Add Modal -->
<x-offcanvas offcanvasType="add" offcanvasTitle="{{ trans('main.addItem', ['item' => trans('admin/assistants.assistant')]) }}"
    action="{{ route('teacher.assistants.insert') }}">
    <x-basic-input context="offcanvas" type="text" name="name_ar" label="{{ trans('main.realName_ar') }}" placeholder="{{ trans('admin/assistants.placeholders.name_ar') }}" required/>
    <x-basic-input context="offcanvas" type="text" name="name_en" label="{{ trans('main.realName_en') }}" placeholder="{{ trans('admin/assistants.placeholders.name_en') }}" required/>
    <x-basic-input context="offcanvas" type="text" name="username" label="{{ trans('main.username') }}" placeholder="{{ trans('admin/assistants.placeholders.username') }}" required/>
    <x-basic-input context="offcanvas" type="password" name="password" label="{{ trans('main.password') }}" required/>
    <x-basic-input context="offcanvas" type="number" name="phone" label="{{ trans('main.phone') }}" placeholder="{{ trans('admin/assistants.placeholders.phone') }}" required/>
    <x-basic-input context="offcanvas" type="email" name="email" label="{{ trans('main.email') }}" placeholder="{{ trans('admin/assistants.placeholders.email') }}"/>
</x-offcanvas>
<!-- Edit Modal -->
<x-offcanvas offcanvasType="edit" offcanvasTitle="{{ trans('main.editItem', ['item' => trans('admin/assistants.assistant')]) }}"
    action="{{ route('teacher.assistants.update') }}" id>
    <x-basic-input context="offcanvas" type="text" name="name_ar" label="{{ trans('main.realName_ar') }}" placeholder="{{ trans('admin/assistants.placeholders.name_ar') }}" required/>
    <x-basic-input context="offcanvas" type="text" name="name_en" label="{{ trans('main.realName_en') }}" placeholder="{{ trans('admin/assistants.placeholders.name_en') }}" required/>
    <x-basic-input context="offcanvas" type="text" name="username" label="{{ trans('main.username') }}" placeholder="{{ trans('admin/assistants.placeholders.username') }}" required/>
    <x-basic-input context="offcanvas" type="password" name="password" label="{{ trans('main.password') }}"/>
    <x-basic-input context="offcanvas" type="number" name="phone" label="{{ trans('main.phone') }}" placeholder="{{ trans('admin/assistants.placeholders.phone') }}" required/>
    <x-basic-input context="offcanvas" type="email" name="email" label="{{ trans('main.email') }}" placeholder="{{ trans('admin/assistants.placeholders.email') }}"/>
    <x-select-input context="offcanvas" name="is_active" label="{{ trans('main.status') }}" :options="[1 => trans('main.active'), 0 => trans('main.inactive')]"/>
</x-offcanvas>
<!-- Delete Modal -->
<x-modal modalType="delete" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/assistants.assistant')]) }}"
    action="{{ route('teacher.assistants.delete') }}" id submitColor="danger" submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.delete-modal-body')
</x-modal>
<!-- Delete Selected Modal -->
<x-modal modalType="delete-selected" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/assistants.selectedAssistants')]) }}"
    action="{{ route('teacher.assistants.deleteSelected') }}" submitColor="danger" ids submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.delete-modal-body')
</x-modal>
