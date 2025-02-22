<!-- Add Offcanvas -->
<x-offcanvas offcanvasType="add" offcanvasTitle="{{ trans('main.addItem', ['item' => trans('admin/refunds.refund')]) }}" action="{{ route('admin.refunds.teachers.insert') }}">
    <x-select-input context="offcanvas" name="teacher_id" label="{{ trans('main.teacher') }}" :options="$teachers" required/>
    <x-basic-input context="offcanvas" price type="text" name="accountBalance" label="{{ trans('main.dependencyDept', ['dependency' => trans('admin/teachers.teacher')]) }}" disabled/>
    <x-basic-input context="offcanvas" price type="number" name="amount" label="{{ trans('main.amount') }}" placeholder="0.00" required/>
    <x-text-area context="offcanvas" name="description" label="{{ trans('main.description') }}" placeholder="{{ trans('admin/refunds.placeholder') }}"/>
</x-offcanvas>
<!-- Edit Offcanvas -->
<x-offcanvas offcanvasType="edit" offcanvasTitle="{{ trans('main.editItem', ['item' => trans('admin/refunds.refund')]) }}" action="{{ route('admin.refunds.teachers.update') }}" id>
    <x-basic-input context="offcanvas" type="text" name="teacher_id" label="{{ trans('main.teacher') }}" disabled/>
    <x-basic-input context="offcanvas" price type="text" name="accountBalance" label="{{ trans('main.dependencyDept', ['dependency' => trans('admin/teachers.teacher')]) }}" disabled/>
    <x-basic-input context="offcanvas" price type="number" name="amount" label="{{ trans('main.amount') }}" placeholder="0.00" required/>
    <x-text-area context="offcanvas" name="description" label="{{ trans('main.description') }}" placeholder="{{ trans('admin/refunds.placeholder') }}"/>
</x-offcanvas>
<!-- Delete Modal -->
<x-modal modalType="delete" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/refunds.refund')]) }}"
    action="{{ route('admin.refunds.teachers.delete') }}" id submitColor="danger" submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.delete-modal-body')
</x-modal>
