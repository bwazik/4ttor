<!-- Add Modal -->
<x-modal modalType="add" modalSize="modal-lg" modalTitle="{{ trans('main.addItem', ['item' => trans('admin/parents.parent')]) }}"
    action="{{ route('teacher.parents.insert') }}">
    <div class="row g-5">
        <x-basic-input context="modal" type="text" name="name_ar" label="{{ trans('main.realName_ar') }}" placeholder="{{ trans('admin/parents.placeholders.name_ar') }}" required/>
        <x-basic-input context="modal" type="text" name="name_en" label="{{ trans('main.realName_en') }}" placeholder="{{ trans('admin/parents.placeholders.name_en') }}" required/>
        <x-basic-input context="modal" type="text" name="username" label="{{ trans('main.username') }}" placeholder="{{ trans('admin/parents.placeholders.username') }}" required/>
        <x-basic-input context="modal" type="password" name="password" label="{{ trans('main.password') }}" required/>
        <x-basic-input context="modal" type="number" name="phone" label="{{ trans('main.phone') }}" placeholder="{{ trans('admin/parents.placeholders.phone') }}" required/>
        <x-basic-input context="modal" type="email" name="email" label="{{ trans('main.email') }}" placeholder="{{ trans('admin/parents.placeholders.email') }}"/>
        <x-select-input context="modal" name="gender" label="{{ trans('main.gender') }}" :options="[1 => trans('main.male'), 2 => trans('main.female')]" required/>
        <x-select-input context="modal" name="students" label="{{ trans('main.students') }}" :options="$students" multiple required/>
    </div>
</x-modal>
<!-- Edit Modal -->
<x-modal modalType="edit" modalSize="modal-lg" modalTitle="{{ trans('main.editItem', ['item' => trans('admin/parents.parent')]) }}"
    action="{{ route('teacher.parents.update') }}" id>
    <div class="row g-5">
        <x-basic-input context="modal" type="text" name="name_ar" label="{{ trans('main.realName_ar') }}" placeholder="{{ trans('admin/parents.placeholders.name_ar') }}" required/>
        <x-basic-input context="modal" type="text" name="name_en" label="{{ trans('main.realName_en') }}" placeholder="{{ trans('admin/parents.placeholders.name_en') }}" required/>
        <x-basic-input context="modal" type="text" name="username" label="{{ trans('main.username') }}" placeholder="{{ trans('admin/parents.placeholders.username') }}" required/>
        <x-basic-input context="modal" type="password" name="password" label="{{ trans('main.password') }}"/>
        <x-basic-input context="modal" type="number" name="phone" label="{{ trans('main.phone') }}" placeholder="{{ trans('admin/parents.placeholders.phone') }}" required/>
        <x-basic-input context="modal" type="email" name="email" label="{{ trans('main.email') }}" placeholder="{{ trans('admin/parents.placeholders.email') }}"/>
        <x-select-input context="modal" name="gender" label="{{ trans('main.gender') }}" :options="[1 => trans('main.male'), 2 => trans('main.female')]" required/>
        <x-select-input context="modal" name="students" label="{{ trans('main.students') }}" :options="$students" multiple required/>
        <x-select-input divClasses="col-12" name="is_active" label="{{ trans('main.status') }}" :options="[1 => trans('main.active'), 0 => trans('main.inactive')]" required/>
    </div>
</x-modal>
<!-- Delete Modal -->
<x-modal modalType="delete" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/parents.parent')]) }}"
    action="{{ route('teacher.parents.delete') }}" id submitColor="danger" submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.delete-modal-body')
</x-modal>
<!-- Delete Selected Modal -->
<x-modal modalType="delete-selected" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/parents.selectedParents')]) }}"
    action="{{ route('teacher.parents.deleteSelected') }}" submitColor="danger" ids submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.delete-modal-body')
</x-modal>
