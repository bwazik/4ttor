@extends('layouts.teacher.master')

@section('page-css')
    <style>
        .disabled-option:hover{
            border-color: #464963 !important;
        }

        .disabled-option{
            cursor: not-allowed !important;
        }

    </style>
@endsection

@section('title', pageTitle('admin/plans.plans'))

@section('content')
    <div class="card px-3">
        <form id="payment-form" action="{{ $payUrl }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-7 card-body border-end p-8">
                    <h4 class="mb-2">{{ trans('admin/plans.checkout') }}</h4>
                    <p class="mb-0">{{ trans('admin/plans.plans_description') }}</p>
                    <div class="row my-8 gx-5 text-nowrap">
                        <div class="col-md mb-md-0 mb-5">
                            <div class="form-check custom-option custom-option-basic disabled-option mb-5">
                                <label
                                    class="form-check-label custom-option-content disabled-option d-flex gap-4 align-items-center"
                                    for="InstaPayRadio">
                                    <input name="payment_method" class="form-check-input" type="radio" value="3"
                                        id="InstaPayRadio" disabled/>
                                    <span class="custom-option-body">
                                        <img src="{{ asset('assets/img/brand/instapay.png') }}" alt="{{ trans('main.instapay') }}" width="50" height="50"/>
                                        <span class="ms-4 text-heading">{{ trans('main.instapay') }} <small class="text-muted">{{ trans('main.soon') }}</small></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md mb-md-0 mb-5">
                            <div class="form-check custom-option custom-option-basic disabled-option mb-5">
                                <label
                                    class="form-check-label custom-option-content disabled-option d-flex gap-4 align-items-center"
                                    for="VodafoneCashRadio">
                                    <input name="payment_method" class="form-check-input" type="radio" value="2"
                                        id="VodafoneCashRadio" disabled/>
                                    <span class="custom-option-body">
                                        <img src="{{ asset('assets/img/brand/vodafone.png') }}" alt="{{ trans('main.vodafoneCash') }}" width="50" height="50"/>
                                        <span class="ms-4 text-heading">{{ trans('main.vodafoneCash') }} <small class="text-muted">{{ trans('main.soon') }}</small></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-check custom-option custom-option-basic checked">
                                <label
                                    class="form-check-label custom-option-content d-flex gap-4 align-items-center"
                                    for="BalanceRadio">
                                    <input name="payment_method" class="form-check-input" type="radio" value="4"
                                        id="BalanceRadio" checked/>
                                    <span class="custom-option-body">
                                        <img src="{{ asset('assets/img/brand/wallet.png') }}" alt="trans('main.balance')" width="50" height="50"/>
                                        <span class="ms-4 text-heading">{{ trans('main.balance') }}</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <span class="invalid-feedback" id="payment_method_error" role="alert"></span>
                    </div>
                    <h4 class="mb-6">{{ trans('admin/plans.payer_details') }}</h4>
                    <div class="row g-5">
                        <div class="d-flex justify-content-between bg-lighter p-2 mb-5 rounded">
                            <p class="mb-0">{{ trans('main.due_amount') }}:</p>
                            <p class="fw-medium mb-0"><span id="due_amount">{{ $dueAmount }}</span> {{ trans('main.currency') }}</p>
                        </div>
                        <x-basic-input divClasses="col-12" price type="number" name="amount" label="{{ trans('main.amount') }}" placeholder="0.00" required/>
                        <x-basic-input divClasses="col-12" type="text" name="name" label="{{ trans('main.name') }}" placeholder="{{ trans('main.placeholders.realName') }}" value="{{ Auth::user()->name ?? 'N/A' }}" readonly/>
                        <x-basic-input divClasses="col-12" type="number" name="phone" label="{{ trans('main.phone') }}" placeholder="01098617164" value="{{ Auth::user()->phone ?? 'N/A' }}" readonly/>
                        <x-basic-input divClasses="col-12" type="email" name="email" label="{{ trans('main.email') }}" placeholder="bwazik@outlook.com" value="{{ Auth::user()->email ?? 'N/A' }}" readonly/>
                    </div>
                </div>
                <div class="col-lg-5 card-body p-8 pt-0 pt-lg-8">
                    <h4 class="mb-2">{{ trans('admin/plans.order_summary') }}</h4>
                    <p class="mb-8">{{ $invoice->subscription->plan->description }}</p>
                    <div class="bg-lighter p-6 rounded-4">
                        <p>{{ $invoice->subscription->plan->name }}</p>
                        <div id="plan-heading" class="d-flex align-items-center">
                            <h1 class="text-heading"><span id="plan_amount">{{ number_format($invoice->subscription->amount, 0) }}</span>{{ trans('main.currency') }} </h1>
                            <sub id="plan_period" class="h6 text-body mb-1">/{{ $invoice->subscription->period === 1 ? trans('main.monthly') : ($invoice->subscription->period === 2 ? trans('main.termly') : ($invoice->subscription->period === 3 ? trans('main.yearly') : 'N/A')) }}</sub>
                        </div>
                    </div>
                    <div id="plan-data" class="mt-5">
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="mb-0">{{ trans('main.amount') }}</p>
                            <h6 class="mb-0"><span id="plan_amount">{{ number_format($invoice->subscription->amount, 0) }}</span> {{ trans('main.currency') }}</h6>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <p class="mb-0">{{ trans('main.discount') }}</p>
                            <h6 class="mb-0">0.00 %</h6>
                        </div>
                        <hr />
                        <div class="d-flex justify-content-between align-items-center pb-1">
                            <p class="mb-0">{{ trans('main.total') }}</p>
                            <h6 class="mb-0"><span id="plan_total">{{ number_format($invoice->subscription->amount, 0) }}</span> {{ trans('main.currency') }}</h6>
                        </div>
                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-success">
                                <span class="me-1_5">{{ trans('admin/plans.pay_now') }}</span>
                                <i class="ri-arrow-right-line ri-16px scaleX-n1-rtl"></i>
                            </button>
                        </div>

                        <p class="mt-8 mb-0">{{ trans('admin/plans.terms_of_services') }}</p>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('page-js')
    <script>
        let paymentFields = ['amount', 'payment_method'];
        handleFormSubmit('#payment-form', paymentFields, '#payment-modal', '', '', "{{ route('teacher.billing.index') }}");
    </script>
@endsection
