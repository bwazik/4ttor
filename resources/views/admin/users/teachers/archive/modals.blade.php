<!-- Delete Modal -->
<x-modal modalType="delete" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/teachers.teacher')]) }}"
    action="{{ route('admin.teachers.delete') }}" id submitColor="danger" submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.delete-modal-body')
</x-modal>
<!-- Delete Selected Modal -->
<x-modal modalType="delete-selected" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/teachers.selectedTeachers')]) }}"
    action="{{ route('admin.teachers.deleteSelected') }}" submitColor="danger" ids submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.delete-modal-body')
</x-modal>
<!-- Restore Modal -->
<x-modal modalType="restore" modalTitle="{{ trans('main.restoreItem', ['item' => trans('admin/teachers.teacher')]) }}"
    action="{{ route('admin.teachers.restore') }}" id submitButton="{{ trans('main.yes_restore') }}">
    @include('partials.restore-modal-body')
</x-modal>
<!-- Restore Selected Modal -->
<x-modal modalType="restore-selected" modalTitle="{{ trans('main.restoreItem', ['item' => trans('admin/teachers.selectedTeachers')]) }}"
    action="{{ route('admin.teachers.restoreSelected') }}" ids submitButton="{{ trans('main.yes_restore') }}">
    @include('partials.restore-modal-body')
</x-modal>
