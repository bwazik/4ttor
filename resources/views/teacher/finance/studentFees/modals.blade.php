<!-- Add Modal -->
<x-offcanvas offcanvasType="add" offcanvasTitle="{{ trans('main.addItem', ['item' => trans('admin/studentFees.studentFee')]) }}"
    action="{{ route('teacher.student-fees.insert') }}">
    <x-select-input context="offcanvas" name="student_id" label="{{ trans('main.student') }}" :options="$students" required/>
    <x-select-input context="offcanvas" name="fee_id" label="{{ trans('main.fee') }}" required/>
    <x-basic-input context="offcanvas" type="number" name="amount" label="{{ trans('main.amount') }}"/>
    <x-basic-input context="offcanvas" type="number" name="discount" label="{{ trans('main.discount') }}" placeholder="50"/>
    <x-select-input context="offcanvas" name="is_exempted" label="{{ trans('main.is_exempted') }}" :options="[1 => trans('main.exempted'), 0 => trans('main.notexempted')]"/>
</x-offcanvas>
<!-- Edit Modal -->
<x-offcanvas offcanvasType="edit" offcanvasTitle="{{ trans('main.editItem', ['item' => trans('admin/studentFees.studentFee')]) }}"
    action="{{ route('teacher.student-fees.update') }}" id>
    <x-select-input context="offcanvas" name="student_id" label="{{ trans('main.student') }}" :options="$students" required/>
    <x-select-input context="offcanvas" name="fee_id" label="{{ trans('main.fee') }}" :options="$fees" required/>
    <x-basic-input context="offcanvas" type="number" name="amount" label="{{ trans('main.amount') }}" disabled/>
    <x-basic-input context="offcanvas" type="number" name="discount" label="{{ trans('main.discount') }}" placeholder="50"/>
    <x-select-input context="offcanvas" name="is_exempted" label="{{ trans('main.is_exempted') }}" :options="[1 => trans('main.exempted'), 0 => trans('main.notexempted')]"/>
</x-offcanvas>
<!-- Delete Modal -->
<x-modal modalType="delete" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/studentFees.studentFee')]) }}"
    action="{{ route('teacher.student-fees.delete') }}" id submitColor="danger" submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.delete-modal-body')
</x-modal>
<!-- Delete Selected Modal -->
<x-modal modalType="delete-selected" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/studentFees.selectedStudentFees')]) }}"
    action="{{ route('teacher.student-fees.deleteSelected') }}" submitColor="danger" ids submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.delete-modal-body')
</x-modal>
