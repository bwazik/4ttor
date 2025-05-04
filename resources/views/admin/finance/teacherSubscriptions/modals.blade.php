<!-- Add Modal -->
<x-offcanvas offcanvasType="add" offcanvasTitle="{{ trans('main.addItem', ['item' => trans('admin/teacherSubscriptions.teacherSubscription')]) }}"
    action="{{ route('admin.teacher-subscriptions.insert') }}">
    <x-select-input context="offcanvas" name="teacher_id" label="{{ trans('main.teacher') }}" :options="$teachers" required/>
    <x-select-input context="offcanvas" name="period" label="{{ trans('main.period') }}" :options="[1 => trans('main.monthly'), 2 => trans('main.termly'), 3 => trans('main.yearly')]"/>
    <x-select-input context="offcanvas" name="plan_id" label="{{ trans('main.plan') }}" :options="$plans" required/>
    <x-basic-input context="offcanvas" type="number" name="amount" label="{{ trans('main.amount') }}" disabled/>
    <x-basic-input context="offcanvas" type="text" name="start_date" classes="flatpickr-date" label="{{ trans('main.start_date') }}" placeholder="YYYY-MM-DD" value="{{ now()->format('Y-m-d') }}"/>
    <x-basic-input context="offcanvas" type="text" name="end_date" classes="flatpickr-date" label="{{ trans('main.end_date') }}" placeholder="YYYY-MM-DD" value="{{ now()->addDays(30)->format('Y-m-d') }}"/>
</x-offcanvas>
<!-- Edit Modal -->
<x-offcanvas offcanvasType="edit" offcanvasTitle="{{ trans('main.editItem', ['item' => trans('admin/teacherSubscriptions.teacherSubscription')]) }}"
    action="{{ route('admin.teacher-subscriptions.update') }}" id>
    <x-select-input context="offcanvas" name="teacher_id" label="{{ trans('main.teacher') }}" :options="$teachers" required/>
    <x-select-input context="offcanvas" name="period" label="{{ trans('main.period') }}" :options="[1 => trans('main.monthly'), 2 => trans('main.termly'), 3 => trans('main.yearly')]"/>
    <x-select-input context="offcanvas" name="plan_id" label="{{ trans('main.plan') }}" :options="$plans" required/>
    <x-basic-input context="offcanvas" type="number" name="amount" label="{{ trans('main.amount') }}" disabled/>
    <x-basic-input context="offcanvas" type="text" name="start_date" classes="flatpickr-date" label="{{ trans('main.start_date') }}" placeholder="YYYY-MM-DD" value="{{ now()->format('Y-m-d') }}"/>
    <x-basic-input context="offcanvas" type="text" name="end_date" classes="flatpickr-date" label="{{ trans('main.end_date') }}" placeholder="YYYY-MM-DD" value="{{ now()->addDays(30)->format('Y-m-d') }}"/>
    <x-select-input context="offcanvas" name="status" label="{{ trans('main.status') }}" :options="[1 => trans('main.active'), 2 => trans('main.canceled'), 3 => trans('main.expired')]"/>
</x-offcanvas>
<!-- Delete Modal -->
<x-modal modalType="delete" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/teacherSubscriptions.teacherSubscription')]) }}"
    action="{{ route('admin.teacher-subscriptions.delete') }}" id submitColor="danger" submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.delete-modal-body')
</x-modal>
<!-- Delete Selected Modal -->
<x-modal modalType="delete-selected" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/teacherSubscriptions.selectedTeacherSubscriptions')]) }}"
    action="{{ route('admin.teacher-subscriptions.deleteSelected') }}" submitColor="danger" ids submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.delete-modal-body')
</x-modal>
