<!-- Add Modal -->
<x-offcanvas offcanvasType="add" offcanvasTitle="{{ trans('main.addItem', ['item' => trans('admin/coupons.coupon')]) }}"
    action="{{ route('admin.coupons.insert') }}">
    <x-basic-input context="offcanvas" type="text" name="code" label="{{ trans('main.code') }}" placeholder="{{ trans('admin/coupons.placeholders.code') }}" required/>
    <x-basic-input context="offcanvas" price type="number" name="amount" label="{{ trans('main.amount') }}" placeholder="0.00" required/>
    <x-select-input context="offcanvas" name="teacher_id" label="{{ trans('main.teacher') }}" :options="$teachers"/>
    <x-select-input context="offcanvas" name="student_id" label="{{ trans('main.student') }}" :options="$students"/>
</x-offcanvas>
<!-- Edit Modal -->
<x-offcanvas offcanvasType="edit" offcanvasTitle="{{ trans('main.editItem', ['item' => trans('admin/coupons.coupon')]) }}"
    action="{{ route('admin.coupons.update') }}" id>
    <x-basic-input context="offcanvas" type="text" name="code" label="{{ trans('main.code') }}" placeholder="{{ trans('admin/coupons.placeholders.code') }}" required/>
    <x-select-input context="offcanvas" name="is_used" label="{{ trans('main.status') }}" :options="[1 => trans('main.used'), 0 => trans('main.unused')]"/>
    <x-basic-input context="offcanvas" price type="number" name="amount" label="{{ trans('main.amount') }}" placeholder="0.00" required/>
    <x-select-input context="offcanvas" name="teacher_id" label="{{ trans('main.teacher') }}" :options="$teachers"/>
    <x-select-input context="offcanvas" name="student_id" label="{{ trans('main.student') }}" :options="$students"/>
</x-offcanvas>
<!-- Delete Modal -->
<x-modal modalType="delete" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/coupons.coupon')]) }}"
    action="{{ route('admin.coupons.delete') }}" id submitColor="danger" submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.delete-modal-body')
</x-modal>
<!-- Delete Selected Modal -->
<x-modal modalType="delete-selected" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/coupons.selectedCoupons')]) }}"
    action="{{ route('admin.coupons.deleteSelected') }}" submitColor="danger" ids submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.delete-modal-body')
</x-modal>
