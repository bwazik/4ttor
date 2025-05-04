@extends('layouts.admin.master')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-invoice.css') }}">
@endsection

@section('title', pageTitle(trans('main.addItem', ['item' => trans('admin/invoices.invoice')])))

@section('content')
<form id="add-form" action="{{ route('admin.invoices.teachers.insert') }}" method="POST" autocomplete="off">
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
                                        <input type="text" class="form-control" placeholder="0000" disabled/>
                                    </div>
                                </dd>
                                <dt class="col-sm-5 mb-2 d-md-flex align-items-center justify-content-start">
                                    <span class="fw-normal">{{ trans('main.date') }}: </span>
                                </dt>
                                <dd class="col-sm-7">
                                    <input type="text" id="date" class="form-control form-control-sm flatpickr-date"
                                        name="date" placeholder="YYYY-MM-DD" value="{{ now()->format('Y-m-d') }}" />
                                    <span class="invalid-feedback" id="date_error" role="alert"></span>
                                </dd>
                                <dt class="col-sm-5 mb-2 d-md-flex align-items-center justify-content-start">
                                    <span class="fw-normal text-nowrap">{{ trans('main.due_date') }}:</span>
                                </dt>
                                <dd class="col-sm-7 mb-0">
                                    <input type="text" id="due_date" class="form-control form-control-sm flatpickr-date"
                                        name="due_date" placeholder="YYYY-MM-DD" value="{{ now()->addDays(30)->format('Y-m-d') }}"/>
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
                            <p id="teacher_phone" class="mb-0">{{ trans('main.phone') }}</p>
                            <p id="teacher_email" class="mb-1">{{ trans('main.email') }}</p>
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
                                        <x-select-input divClasses="mb-5" name="subscription_id" label="{{ trans('main.plan') }}"
                                            required />
                                    </div>
                                    <div class="col-md-3 col-12 mb-md-0 mb-4">
                                        <p class="h6 repeater-title">{{ trans('main.amount') }}</p>
                                        <x-basic-input divClasses="mb-5" price type="number" name="amount"
                                            label="{{ trans('main.amount') }}" placeholder="0.00" disabled />
                                    </div>
                                    <div class="col-md-3 col-12 mb-md-0 mb-4">
                                        <p class="h6 repeater-title">{{ trans('main.discount') }}</p>
                                        <x-basic-input divClasses="mb-5" type="number" name="discount"
                                            label="{{ trans('main.discount') }}" value="0" placeholder="0" disabled/>
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
                                    label="{{ trans('main.founder') }}" value="عبدالله محمد فتحي" placeholder="{{ trans('main.placeholders.realName') }}"
                                    disabled />
                                <p id="founder_phone" class="mb-1">01098617164</p>
                                <p id="founder_email" class="mb-0">bwazik@outlook.com</p>
                        </div>
                        <div class="col-md-6 d-flex justify-content-md-end mt-2">
                            <div class="invoice-calculations">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="w-px-100">{{ trans('main.amount') }}:</span>
                                    <h6 class="mb-0"><span id="plan_amount">00.0</span> {{ trans('main.currency') }}</h6>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="w-px-100">{{ trans('main.discount') }}:</span>
                                    <h6 class="mb-0"><span id="plan_discount">0</span> %</h6>
                                </div>
                                <hr class="my-2" />
                                <div class="d-flex justify-content-between">
                                    <span class="w-px-100">{{ trans('main.total') }}:</span>
                                    <h6 class="mb-0"><span id="plan_total">00.0</span> {{ trans('main.currency') }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="my-0" />
                <div class="card-body py-6 px-0">
                    <div class="row">
                        <div class="col-12">
                            <x-text-area divClasses="" name="description" label="{{ trans('main.description') }}" placeholder="{{ trans('main.placeholders.description') }}" maxlength=500/>
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
                    <a href="{{ route('admin.invoices.teachers.index') }}" id="show" class="btn btn-outline-secondary d-grid w-100 mb-4">
                        <span class="d-flex align-items-center justify-content-center text-nowrap">{{ trans('admin/invoices.invoices') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('page-js')
    <script>
        initializeSelect2('add-form', 'teacher_id');
        initializeSelect2('add-form', 'subscription_id');

        fetchMultipleDataByAjax('#add-form #teacher_id', "{{ route('admin.fetch.teachers.teacher-subscriptions', '__ID__') }}",
            '#add-form #subscription_id', 'teacher_id', 'GET');
        fetchSingleDataByAjax('#add-form #teacher_id', "{{ route('admin.fetch.teachers.data', '__ID__') }}", [
            { targetSelector: '#add-form #teacher_phone', dataKey: 'phone' },
            { targetSelector: '#add-form #teacher_email', dataKey: 'email' },
        ], 'teacher_id');
        fetchSingleDataByAjax('#add-form #subscription_id', "{{ route('admin.fetch.teacher-subscriptions.data', '__ID__') }}", [
            { targetSelector: '#add-form #amount', dataKey: 'plan.amount' },
            { targetSelector: '#add-form #plan_amount', dataKey: 'plan.amount' },
            { targetSelector: '#add-form #plan_total', dataKey: 'amount' },
        ], 'subscription_id');


        let fields = ['teacher_id', 'subscription_id', 'date', 'due_date', 'description'];
        handleFormSubmit('#add-form', fields, '#add-modal');
    </script>
@endsection
