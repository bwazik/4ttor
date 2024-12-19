<!-- Add Modal -->
<x-offcanvas offcanvasType="add" offcanvasTitle="{{ trans('main.addItem', ['item' => trans('admin/teachers.teacher')]) }}"
    action="{{ route('admin.teachers.insert') }}">
    <x-basic-input context="offcanvas" type="text" name="name_ar" label="{{ trans('main.realName_ar') }}" placeholder="{{ trans('admin/teachers.placeholders.name_ar') }}" required/>
    <x-basic-input context="offcanvas" type="text" name="name_en" label="{{ trans('main.realName_en') }}" placeholder="{{ trans('admin/teachers.placeholders.name_en') }}" required/>
    <x-basic-input context="offcanvas" type="text" name="username" label="{{ trans('main.username') }}" placeholder="{{ trans('admin/teachers.placeholders.username') }}" required/>
    <x-basic-input context="offcanvas" type="password" name="password" label="{{ trans('main.password') }}" required/>
    <x-basic-input context="offcanvas" type="number" name="phone" label="{{ trans('main.phone') }}" placeholder="{{ trans('admin/teachers.placeholders.phone') }}" required/>
    <x-basic-input context="offcanvas" type="email" name="email" label="{{ trans('main.email') }}" placeholder="{{ trans('admin/teachers.placeholders.email') }}"/>
    <x-select-input context="offcanvas" name="subject_id" label="{{ trans('main.subject') }}" :options="$subjects"  required/>
    <x-select-input context="offcanvas" name="grades" label="{{ trans('main.grades') }}" :options="$grades" multiple required/>
</x-offcanvas>
<!-- Edit Modal -->
<x-offcanvas offcanvasType="edit" offcanvasTitle="{{ trans('main.editItem', ['item' => trans('admin/teachers.teacher')]) }}"
    action="{{ route('admin.teachers.update') }}" id>
    <x-basic-input context="offcanvas" type="text" name="name_ar" label="{{ trans('main.realName_ar') }}" placeholder="{{ trans('admin/teachers.placeholders.name_ar') }}" required/>
    <x-basic-input context="offcanvas" type="text" name="name_en" label="{{ trans('main.realName_en') }}" placeholder="{{ trans('admin/teachers.placeholders.name_en') }}" required/>
    <x-basic-input context="offcanvas" type="text" name="username" label="{{ trans('main.username') }}" placeholder="{{ trans('admin/teachers.placeholders.username') }}" required/>
    <x-basic-input context="offcanvas" type="password" name="password" label="{{ trans('main.password') }}"/>
    <x-basic-input context="offcanvas" type="number" name="phone" label="{{ trans('main.phone') }}" placeholder="{{ trans('admin/teachers.placeholders.phone') }}" required/>
    <x-basic-input context="offcanvas" type="email" name="email" label="{{ trans('main.email') }}" placeholder="{{ trans('admin/teachers.placeholders.email') }}"/>
    <x-select-input context="offcanvas" name="subject_id" label="{{ trans('main.subject') }}" :options="$subjects"  required/>
    <x-select-input context="offcanvas" name="grades" label="{{ trans('main.grades') }}" :options="$grades" multiple required/>
    <x-select-input context="offcanvas" name="is_active" label="{{ trans('main.status') }}" :options="[1 => trans('main.active'), 0 => trans('main.inactive')]"/>
</x-offcanvas>
<!-- Delete Modal -->
<x-modal modalType="delete" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/teachers.teacher')]) }}"
    action="{{ route('admin.teachers.delete') }}" id submitColor="danger" submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.base.delete-modal-body')
</x-modal>
<!-- Delete Selected Modal -->
<x-modal modalType="delete-selected" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/teachers.selectedTeachers')]) }}"
    action="{{ route('admin.teachers.deleteSelected') }}" submitColor="danger" ids submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.base.delete-modal-body')
</x-modal>
<!-- Archive Modal -->
<x-modal modalType="archive" modalTitle="{{ trans('main.archiveItem', ['item' => trans('admin/teachers.teacher')]) }}"
    action="{{ route('admin.teachers.archive') }}" id submitButton="{{ trans('main.yes_archive') }}">
    @include('partials.base.archive-modal-body')
</x-modal>
<!-- Archive Selected Modal -->
<x-modal modalType="archive-selected" modalTitle="{{ trans('main.archiveItem', ['item' => trans('admin/teachers.selectedTeachers')]) }}"
    action="{{ route('admin.teachers.archiveSelected') }}" ids submitButton="{{ trans('main.yes_archive') }}">
    @include('partials.base.archive-modal-body')
</x-modal>
