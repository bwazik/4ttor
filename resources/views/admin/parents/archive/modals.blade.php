<!-- Delete Modal -->
<x-modal modalType="delete" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/parents.parent')]) }}"
    action="{{ route('admin.parents.delete') }}" id submitColor="danger" submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.base.delete-modal-body')
</x-modal>
<!-- Delete Selected Modal -->
<x-modal modalType="delete-selected" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/parents.selectedParents')]) }}"
    action="{{ route('admin.parents.deleteSelected') }}" submitColor="danger" ids submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.base.delete-modal-body')
</x-modal>
<!-- Restore Modal -->
<x-modal modalType="restore" modalTitle="{{ trans('main.restoreItem', ['item' => trans('admin/parents.parent')]) }}"
    action="{{ route('admin.parents.restore') }}" id submitButton="{{ trans('main.yes_restore') }}">
    @include('partials.base.restore-modal-body')
</x-modal>
<!-- Restore Selected Modal -->
<x-modal modalType="restore-selected" modalTitle="{{ trans('main.restoreItem', ['item' => trans('admin/parents.selectedParents')]) }}"
    action="{{ route('admin.parents.restoreSelected') }}" ids submitButton="{{ trans('main.yes_restore') }}">
    @include('partials.base.restore-modal-body')
</x-modal>
