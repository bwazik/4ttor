<!-- Add Offcanvas -->
<x-offcanvas offcanvasType="add" offcanvasTitle="{{ trans('main.addItem', ['item' => trans('admin/invoices.invoice')]) }}" action="{{ route('admin.invoices.students.insert') }}">
    <x-select-input context="offcanvas" name="student_id" label="{{ trans('main.student') }}" :options="$students" required/>
    <x-basic-input context="offcanvas" type="text" name="grade_id" label="{{ trans('main.grade') }}" disabled/>
    <x-select-input context="offcanvas" name="teacher_id" label="{{ trans('main.teacher') }}" required/>
    <x-select-input context="offcanvas" name="fee_id" label="{{ trans('main.fee') }}" required/>
    <x-basic-input context="offcanvas" price type="number" name="amount" label="{{ trans('main.amount') }}" placeholder="0.00" disabled/>
</x-offcanvas>
<!-- Delete Modal -->
<x-modal modalType="delete" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/invoices.invoice')]) }}"
    action="{{ route('admin.invoices.students.delete') }}" id submitColor="danger" submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.delete-modal-body')
</x-modal>
