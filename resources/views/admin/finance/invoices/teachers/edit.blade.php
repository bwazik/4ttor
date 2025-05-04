@extends('layouts.admin.master')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-invoice.css') }}">
@endsection

@section('title', pageTitle(trans('main.editItem', ['item' => trans('admin/invoices.invoice')])))

@section('content')
    <form id="edit-form" action="{{ route('admin.invoices.teachers.update', $invoice->id) }}" method="POST" autocomplete="off">
        @csrf
        <div class="row invoice-add">
            <div class="col-lg-9 col-12 mb-lg-0 mb-6">
                <div class="card invoice-preview-card p-sm-12 p-6">
                    <div class="card-body invoice-preview-header rounded-4 text-heading p-6 px-3">
                        <div class="row mx-0 px-3 row-gap-6">
                            <div class="col-md-8 ps-0">
                                <div class="d-flex svg-illustration align-items-center gap-2 mb-6">
                                    <span class="app-brand-logo demo">
                                        <img width="60" height="60" src="{{ asset('assets/img/brand/navbar.png') }}"
                                            alt="Shattor">
                                    </span>
                                    <span
                                        class="mb-0 app-brand-text demo fw-semibold">{{ trans('layouts/sidebar.platformName') }}</span>
                                </div>
                                <p class="mb-1">01098617164</p>
                                <p class="mb-0">bwazik@outlook.com</p>
                            </div>
                            <div class="col-md-4 col-8 pe-0 ps-0 ps-md-2">
                                <dl class="row mb-0 gx-4">
                                    <dt class="col-sm-5 mb-2 d-md-flex align-items-center justify-content-start">
                                        <span
                                            class="h5 text-capitalize mb-0 text-nowrap">{{ trans('admin/invoices.theInvoice') }}</span>
                                    </dt>
                                    <dd class="col-sm-7">
                                        <div class="input-group input-group-merge input-group-sm">
                                            <span class="input-group-text">#</span>
                                            <input type="text" class="form-control" placeholder="{{ $invoice->id }}"
                                                value="{{ $invoice->id }}" disabled />
                                        </div>
                                    </dd>
                                    <dt class="col-sm-5 mb-2 d-md-flex align-items-center justify-content-start">
                                        <span class="fw-normal">{{ trans('main.status') }}: </span>
                                    </dt>
                                    <dd class="col-sm-7">
                                        <input type="text" id="status"
                                            class="form-control form-control-sm"
                                            name="status"
                                            placeholder="{{ trans('main.status') }}"
                                            value="{{ trans('main.' . match($invoice->status) {
                                                1 => 'pending',
                                                2 => 'paid',
                                                3 => 'overdue',
                                                4 => 'canceled',
                                                default => 'N/A'
                                            }) }}"
                                            disabled/>
                                    </dd>
                                    <dt class="col-sm-5 mb-2 d-md-flex align-items-center justify-content-start">
                                        <span class="fw-normal">{{ trans('main.date') }}: </span>
                                    </dt>
                                    <dd class="col-sm-7">
                                        <input type="text" id="date"
                                            class="form-control form-control-sm flatpickr-date" name="date"
                                            placeholder="YYYY-MM-DD" value="{{ $invoice->date }}" />
                                        <span class="invalid-feedback" id="date_error" role="alert"></span>
                                    </dd>
                                    <dt class="col-sm-5 mb-2 d-md-flex align-items-center justify-content-start">
                                        <span class="fw-normal text-nowrap">{{ trans('main.due_date') }}:</span>
                                    </dt>
                                    <dd class="col-sm-7 mb-0">
                                        <input type="text" id="due_date"
                                            class="form-control form-control-sm flatpickr-date" name="due_date"
                                            placeholder="YYYY-MM-DD" value="{{ $invoice->due_date }}" />
                                        <span class="invalid-feedback" id="due_date_error" role="alert"></span>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-6 px-0">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-12 mb-sm-0 mb-6">
                                <x-select-input divClasses="mb-4" name="teacher_id" label="{{ trans('main.teacher') }}"
                                    :options="$teachers" required />
                                <p id="teacher_phone" class="mb-0">{{ $invoice->teacher->phone }}</p>
                                <p id="teacher_email" class="mb-1">{{ $invoice->teacher->email }}</p>
                            </div>
                        </div>
                    </div>
                    <hr class="mt-0 mb-6" />
                    <div class="card-body p-0 pb-6">
                        <div class="mb-4">
                            <div class="pt-0 pt-md-9">
                                <div class="d-flex border rounded position-relative pe-0">
                                    <div class="row w-100 p-5 gx-5">
                                        <div class="col-md-6 col-12 mb-md-0 mb-4">
                                            <p class="h6 repeater-title">{{ trans('main.plan') }}</p>
                                            <x-select-input divClasses="mb-5" name="subscription_id"
                                                label="{{ trans('main.plan') }}" :options="$teacherSubscriptions" required />
                                        </div>
                                        <div class="col-md-3 col-12 mb-md-0 mb-4">
                                            <p class="h6 repeater-title">{{ trans('main.amount') }}</p>
                                            <x-basic-input divClasses="mb-5" price type="number" name="amount"
                                                label="{{ trans('main.amount') }}" value="{{ $invoice->subscription->amount }}"
                                                placeholder="0.00" disabled />
                                        </div>
                                        <div class="col-md-3 col-12 mb-md-0 mb-4">
                                            <p class="h6 repeater-title">{{ trans('main.discount') }}</p>
                                            <x-basic-input divClasses="mb-5" type="number" name="discount"
                                                label="{{ trans('main.discount') }}"
                                                value="0" placeholder="0" disabled />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="my-1" />
                    <div class="card-body px-0 pb-6">
                        <div class="row row-gap-4">
                            <div class="col-md-6 mb-md-0 mb-3">
                                <x-basic-input divClasses="mb-4" type="text" name="founder_name"
                                    label="{{ trans('main.founder') }}"
                                    placeholder="{{ trans('main.placeholders.realName') }}"
                                    value="عبدالله محمد فتحي" disabled />
                                    <p id="founder_phone" class="mb-1">01098617164</p>
                                    <p id="founder_email" class="mb-0">bwazik@outlook.com</p>
                            </div>
                            <div class="col-md-6 d-flex justify-content-md-end mt-2">
                                <div class="invoice-calculations">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="w-px-100">{{ trans('main.amount') }}:</span>
                                        <h6 class="mb-0"><span id="plan_amount">{{ $invoice->subscription->amount }}</span>
                                            {{ trans('main.currency') }}</h6>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="w-px-100">{{ trans('main.discount') }}:</span>
                                        <h6 class="mb-0"><span
                                                id="plan_discount">0</span> %</h6>
                                    </div>
                                    <hr class="my-2" />
                                    <div class="d-flex justify-content-between">
                                        <span class="w-px-100">{{ trans('main.total') }}:</span>
                                        <h6 class="mb-0"><span
                                                id="plan_total">{{ $invoice->subscription->amount }}</span>
                                            {{ trans('main.currency') }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="my-0" />
                    <div class="card-body py-6 px-0">
                        <div class="row">
                            <div class="col-12">
                                <x-text-area divClasses="" name="description" label="{{ trans('main.description') }}"
                                    value="{{ $invoice->description }}"
                                    placeholder="{{ trans('main.placeholders.description') }}" maxlength=500 />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-12 invoice-actions">
                <div class="card mb-6">
                    <div class="card-body">
                        <button type="submit" id="submit" class="btn btn-primary d-grid w-100 mb-4">
                            <span class="d-flex align-items-center justify-content-center text-nowrap">{{ trans('main.submit') }}</span>
                        </button>
                        <button type="button" class="btn btn-success d-grid w-100 mb-4" data-bs-toggle="offcanvas" data-bs-target="#payment-modal" >
                            <span class="d-flex align-items-center justify-content-center text-nowrap">{{ trans('main.addItem', ['item' => trans('main.payment')]) }}</span>
                        </button>
                        <button type="button" class="btn btn-danger d-grid w-100 mb-4" data-bs-toggle="offcanvas" data-bs-target="#refund-modal" >
                            <span class="d-flex align-items-center justify-content-center text-nowrap">{{ trans('main.addItem', ['item' => trans('main.refund')]) }}</span>
                        </button>
                        <button type="button" class="btn btn-outline-secondary d-grid w-100 mb-4" id="cancel-button"
                            data-id="{{ $invoice->id }}" data-plan="{{ $invoice->subscription->plan->name }}" data-teacher="{{ $invoice->teacher->name }}"
                            data-bs-target="#cancel-modal" data-bs-toggle="modal" data-bs-dismiss="modal">
                            <span class="d-flex align-items-center justify-content-center text-nowrap">{{ trans('main.cancelItem', ['item' => trans('admin/invoices.theInvoice')]) }}</span>
                        </button>
                        <div class="d-flex">
                            <a href="{{ route('admin.invoices.teachers.preview', $invoice->id) }}" class="btn btn-outline-secondary d-grid w-100 me-4 waves-effect text-nowrap">
                                {{ trans('main.previewItem', ['item' => trans('admin/invoices.theInvoice')]) }}
                            </a>
                            <a href="{{ route('admin.invoices.teachers.index') }}" class="btn btn-outline-secondary d-grid w-100 waves-effect text-nowrap">
                                {{ trans('admin/invoices.invoices') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Payment Modal -->
    <x-offcanvas offcanvasType="payment" offcanvasTitle="{{ trans('main.addItem', ['item' => trans('main.payment')]) }}"
        action="{{ route('admin.invoices.teachers.payment', $invoice->id) }}">
        <div class="d-flex justify-content-between bg-lighter p-2 mb-5 rounded">
            <p class="mb-0">{{ trans('main.due_amount') }}:</p>
            <p class="fw-medium mb-0">{{ $dueAmount }} {{ trans('main.currency') }}</p>
        </div>
        <x-basic-input context="offcanvas" price type="number" name="amount" label="{{ trans('main.amount') }}" placeholder="0.00" required/>
        <x-basic-input context="offcanvas" type="text" name="date" classes="flatpickr-date" label="{{ trans('main.date') }}" placeholder="YYYY-MM-DD" value="{{ now()->format('Y-m-d') }}" disabled/>
        <x-select-input context="offcanvas" name="payment_method" label="{{ trans('main.paymentMethod') }}" :options="[1 => trans('main.cash'), 2 => trans('main.vodafoneCash'), 3 => trans('main.instapay')]" required/>
        <x-text-area context="offcanvas" name="description" label="{{ trans('main.description') }}" placeholder="{{ trans('main.placeholders.description') }}"/>
    </x-offcanvas>
    <!-- Refund Modal -->
    <x-offcanvas offcanvasType="refund" offcanvasTitle="{{ trans('main.addItem', ['item' => trans('main.refund')]) }}"
        action="{{ route('admin.invoices.teachers.refund', $invoice->id) }}">
        <div class="d-flex justify-content-between bg-lighter p-2 mb-5 rounded">
            <p class="mb-0">{{ trans('main.due_amount') }}:</p>
            <p class="fw-medium mb-0">{{ $dueAmount }} {{ trans('main.currency') }}</p>
        </div>
        <x-basic-input context="offcanvas" price type="number" name="amount" label="{{ trans('main.amount') }}" placeholder="0.00" required/>
        <x-basic-input context="offcanvas" type="text" name="date" classes="flatpickr-date" label="{{ trans('main.date') }}" placeholder="YYYY-MM-DD" value="{{ now()->format('Y-m-d') }}" disabled/>
        <x-select-input context="offcanvas" name="payment_method" label="{{ trans('main.paymentMethod') }}" :options="[1 => trans('main.cash'), 2 => trans('main.vodafoneCash'), 3 => trans('main.instapay')]" required/>
        <x-text-area context="offcanvas" name="description" label="{{ trans('main.description') }}" placeholder="{{ trans('main.placeholders.description') }}"/>
    </x-offcanvas>
    <!-- Cancel Modal -->
    <x-modal modalType="cancel" modalTitle="{{ trans('main.cancelItem', ['item' => trans('admin/invoices.invoice')]) }}"
        action="{{ route('admin.invoices.teachers.cancel') }}" id submitColor="danger" submitButton="{{ trans('main.yes_cancel') }}">
        @include('partials.cancel-modal-body')
    </x-modal>
@endsection

@section('page-js')
    <script>
        initializeSelect2('edit-form', 'teacher_id', '{{ $invoice->teacher->id }}');
        initializeSelect2('edit-form', 'subscription_id', '{{ $invoice->subscription->id }}');
        initializeSelect2('payment-form', 'payment_method', 1);

        fetchMultipleDataByAjax('#edit-form #teacher_id', "{{ route('admin.fetch.teachers.teacher-subscriptions', '__ID__') }}",
            '#edit-form #subscription_id', 'teacher_id', 'GET');
        fetchSingleDataByAjax('#edit-form #teacher_id', "{{ route('admin.fetch.teachers.data', '__ID__') }}", [
            { targetSelector: '#edit-form #teacher_phone', dataKey: 'phone' },
            { targetSelector: '#edit-form #teacher_email', dataKey: 'email' },
        ], 'teacher_id');
        fetchSingleDataByAjax('#edit-form #subscription_id', "{{ route('admin.fetch.teacher-subscriptions.data', '__ID__') }}", [
            { targetSelector: '#edit-form #amount', dataKey: 'plan.amount' },
            { targetSelector: '#edit-form #plan_amount', dataKey: 'plan.amount' },
            { targetSelector: '#edit-form #plan_total', dataKey: 'amount' },
        ], 'subscription_id');

        // Setup cancel modal
        setupModal({
            buttonId: '#cancel-button',
            modalId: '#cancel-modal',
            fields: {
                id: button => button.data('id'),
                itemToCancel: button => `${button.data('plan')} - ${button.data('teacher')}`
            }
        });

        let fields = ['teacher_id', 'subscription_id', 'date', 'due_date', 'description'];
        let paymentFields = ['amount', 'payment_method', 'description'];
        handleFormSubmit('#edit-form', fields, '#add-modal');
        handleFormSubmit('#payment-form', paymentFields, '#payment-modal');
        handleFormSubmit('#refund-form', paymentFields, '#refund-modal');
        handleDeletionFormSubmit('#cancel-form', '#cancel-modal')
    </script>
@endsection
