<!-- Add Offcanvas -->
<x-offcanvas offcanvasType="add" offcanvasTitle="{{ trans('main.addItem', ['item' => trans('admin/invoices.invoice')]) }}" action="{{ route('admin.invoices.teachers.insert') }}">
    <x-select-input context="offcanvas" name="teacher_id" label="{{ trans('main.teacher') }}" :options="$teachers"  required/>
    <x-select-input context="offcanvas" name="plan_id" label="{{ trans('main.plan') }}" :options="$plans"  required/>
    <x-basic-input context="offcanvas" price type="number" name="amount" label="{{ trans('main.amount') }}" placeholder="0.00" readonly/>
</x-offcanvas>
<!-- Delete Modal -->
<x-modal modalType="delete" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/invoices.invoice')]) }}"
    action="{{ route('admin.invoices.teachers.delete') }}" id submitColor="danger" submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.delete-modal-body')
</x-modal>
