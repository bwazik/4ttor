<!-- Delete Modal -->
<x-modal modalType="delete" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/assistants.assistant')]) }}"
    action="{{ route('admin.assistants.delete') }}" id submitColor="danger" submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.delete-modal-body')
</x-modal>
<!-- Delete Selected Modal -->
<x-modal modalType="delete-selected" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/assistants.selectedAssistants')]) }}"
    action="{{ route('admin.assistants.deleteSelected') }}" submitColor="danger" ids submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.delete-modal-body')
</x-modal>
<!-- Restore Modal -->
<x-modal modalType="restore" modalTitle="{{ trans('main.restoreItem', ['item' => trans('admin/assistants.assistant')]) }}"
    action="{{ route('admin.assistants.restore') }}" id submitButton="{{ trans('main.yes_restore') }}">
    @include('partials.restore-modal-body')
</x-modal>
<!-- Restore Selected Modal -->
<x-modal modalType="restore-selected" modalTitle="{{ trans('main.restoreItem', ['item' => trans('admin/assistants.selectedAssistants')]) }}"
    action="{{ route('admin.assistants.restoreSelected') }}" ids submitButton="{{ trans('main.yes_restore') }}">
    @include('partials.restore-modal-body')
</x-modal>
