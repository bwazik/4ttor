<!-- Add Offcanvas -->
<x-offcanvas offcanvasType="add" offcanvasTitle="{{ trans('main.addItem', ['item' => trans('admin/refunds.refund')]) }}" action="{{ route('admin.refunds.students.insert') }}">
    <x-select-input context="offcanvas" name="student_id" label="{{ trans('main.student') }}" :options="$students" required/>
    <x-basic-input context="offcanvas" price type="text" name="accountBalance" label="{{ trans('main.dependencyDept', ['dependency' => trans('admin/students.student')]) }}" disabled/>
    <x-basic-input context="offcanvas" price type="number" name="amount" label="{{ trans('main.amount') }}" placeholder="0.00" required/>
    <x-text-area context="offcanvas" name="description" label="{{ trans('main.description') }}" placeholder="{{ trans('admin/refunds.placeholder') }}"/>
</x-offcanvas>
<!-- Edit Offcanvas -->
<x-offcanvas offcanvasType="edit" offcanvasTitle="{{ trans('main.editItem', ['item' => trans('admin/refunds.refund')]) }}" action="{{ route('admin.refunds.students.update') }}" id>
    <x-basic-input context="offcanvas" type="text" name="student_id" label="{{ trans('main.student') }}" disabled/>
    <x-basic-input context="offcanvas" price type="text" name="accountBalance" label="{{ trans('main.dependencyDept', ['dependency' => trans('admin/students.student')]) }}" disabled/>
    <x-basic-input context="offcanvas" price type="number" name="amount" label="{{ trans('main.amount') }}" placeholder="0.00" required/>
    <x-text-area context="offcanvas" name="description" label="{{ trans('main.description') }}" placeholder="{{ trans('admin/refunds.placeholder') }}"/>
</x-offcanvas>
<!-- Delete Modal -->
<x-modal modalType="delete" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/refunds.refund')]) }}"
    action="{{ route('admin.refunds.students.delete') }}" id submitColor="danger" submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.delete-modal-body')
</x-modal>
