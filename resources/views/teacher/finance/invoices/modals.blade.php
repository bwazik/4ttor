<!-- Payment Modal -->
<x-offcanvas offcanvasType="payment" offcanvasTitle="{{ trans('main.addItem', ['item' => trans('main.payment')]) }}"
    action="{{ route('teacher.invoices.payment', '__ID__') }}" id>
    <div class="d-flex justify-content-between bg-lighter p-2 mb-5 rounded">
        <p class="mb-0">{{ trans('main.due_amount') }}:</p>
        <p class="fw-medium mb-0"><span id="due_amount">0.00</span> {{ trans('main.currency') }}</p>
    </div>
    <x-basic-input context="offcanvas" price type="number" name="amount" label="{{ trans('main.amount') }}" placeholder="0.00" required/>
    <x-basic-input context="offcanvas" type="text" name="date" classes="flatpickr-date" label="{{ trans('main.date') }}" placeholder="YYYY-MM-DD" value="{{ now()->format('Y-m-d') }}" disabled/>
    <x-select-input context="offcanvas" name="payment_method" label="{{ trans('main.paymentMethod') }}" :options="[1 => trans('main.cash'), 2 => trans('main.vodafoneCash'), 3 => trans('main.instapay')]" required/>
    <x-text-area context="offcanvas" name="description" label="{{ trans('main.description') }}" placeholder="{{ trans('main.placeholders.description') }}"/>
</x-offcanvas>
<!-- Refund Modal -->
<x-offcanvas offcanvasType="refund" offcanvasTitle="{{ trans('main.addItem', ['item' => trans('main.refund')]) }}"
    action="{{ route('teacher.invoices.refund', '__ID__') }}" id>
    <div class="d-flex justify-content-between bg-lighter p-2 mb-5 rounded">
        <p class="mb-0">{{ trans('main.due_amount') }}:</p>
        <p class="fw-medium mb-0"><span id="due_amount">0.00</span> {{ trans('main.currency') }}</p>
    </div>
    <x-basic-input context="offcanvas" price type="number" name="amount" label="{{ trans('main.amount') }}" placeholder="0.00" required/>
    <x-basic-input context="offcanvas" type="text" name="date" classes="flatpickr-date" label="{{ trans('main.date') }}" placeholder="YYYY-MM-DD" value="{{ now()->format('Y-m-d') }}" disabled/>
    <x-select-input context="offcanvas" name="payment_method" label="{{ trans('main.paymentMethod') }}" :options="[1 => trans('main.cash'), 2 => trans('main.vodafoneCash'), 3 => trans('main.instapay')]" required/>
    <x-text-area context="offcanvas" name="description" label="{{ trans('main.description') }}" placeholder="{{ trans('main.placeholders.description') }}"/>
</x-offcanvas>
<!-- Delete Modal -->
<x-modal modalType="delete" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/invoices.invoice')]) }}"
    action="{{ route('teacher.invoices.delete') }}" id submitColor="danger" submitButton="{{ trans('main.yes_delete') }}">
    @include('partials.delete-modal-body')
</x-modal>
<!-- Cancel Modal -->
<x-modal modalType="cancel" modalTitle="{{ trans('main.cancelItem', ['item' => trans('admin/invoices.invoice')]) }}"
    action="{{ route('teacher.invoices.cancel') }}" id submitColor="danger" submitButton="{{ trans('main.yes_cancel') }}">
    @include('partials.cancel-modal-body')
</x-modal>
