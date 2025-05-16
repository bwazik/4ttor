@extends('layouts.teacher.master')

@section('page-css')

@endsection

@section('title', pageTitle('admin/plans.plans'))

@section('content')
    <div class="row">
        <div class="col-md-12">
            @include('teacher.account.navbar')
            <!-- Current Plan -->
            <div class="card mb-6">
                <h5 class="card-header">{{ trans('account.currentPlan') }}</h5>
                <div class="card-body pt-1">
                    @if ($subscription && Auth::user()->plan_id)
                        <!-- Subscribed -->
                        <div class="row row-gap-6">
                            <div class="col-md-6 mb-1">
                                <div class="mb-6">
                                    <h6 class="mb-1">{{ trans('account.yourCurrentPlanIs') }}
                                        {{ $subscription->plan->name }}</h6>
                                    <p>{{ $subscription->plan->description }}</p>
                                </div>
                                <div class="mb-6">
                                    <h6 class="mb-1">{{ trans('account.activeUntil') }}
                                        {{ formatDate($subscription->end_date) }}</h6>
                                    <p>{{ trans('account.subscriptionExpirationNotification') }}</p>
                                </div>
                                <div>
                                    <h6 class="mb-1">
                                        <span class="me-1">{{ number_format($subscription->amount, 2) }}
                                            {{ trans('main.currency') }}/
                                            {{ $subscription->period === 1 ? trans('main.monthly') : ($subscription->period === 2 ? trans('main.termly') : ($subscription->period === 3 ? trans('main.yearly') : 'N/A')) }}
                                        </span>
                                        @if ($subscription->plan_id === 3)
                                            <span
                                                class="badge bg-label-primary rounded-pill">{{ trans('admin/plans.popular') }}</span>
                                        @endif
                                    </h6>
                                    <p>{{ trans('account.planPricingDescription') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                @if ($invoice && $invoice->status === 1)
                                    <div class="alert alert-warning mb-6 alert-dismissible" role="alert">
                                        <h5 class="alert-heading mb-1 d-flex align-items-center">
                                            <span class="alert-icon rounded"><i
                                                    class="icon-base ri ri-alert-line icon-22px"></i></span>
                                            <span>{{ trans('account.completeSubscription') }}</span>
                                        </h5>
                                        <p>{{ trans('account.subscriptionNotActive', ['plan' => $subscription->plan->name]) }}
                                        </p>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="close"></button>
                                    </div>
                                @endif
                                <div class="plan-statistics">
                                    @if ($usage)
                                        <div class="d-flex justify-content-between">
                                            <h6 class="mb-1">{{ trans('account.days') }}</h6>
                                            <h6 class="mb-1">{{ $usage['usedDays'] }} {{ trans('account.of') }}
                                                {{ $usage['totalDays'] }} {{ trans('account.day') }}</h6>
                                        </div>
                                        <div class="progress rounded mb-1 progress-thin">
                                            <div class="progress-bar rounded" role="progressbar"
                                                style="width: {{ $usage['progress'] }}%"
                                                aria-valuenow="{{ $usage['progress'] }}" aria-valuemin="0"
                                                aria-valuemax="100">
                                            </div>
                                        </div>
                                        <small>{{ trans('account.days_remaining_until_update', ['days' => $usage['remainingDays']]) }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12 d-flex gap-4 flex-wrap">
                                <a href="{{ trans('account.upgradePlan') }}"
                                    class="btn btn-primary waves-effect waves-light">{{ trans('admin/plans.subscripe_now') }}</a>
                                <button class="btn btn-outline-danger cancel-subscription waves-effect" id="cancel-button"
                                    data-id="{{ $subscription->id }}" data-plan="{{ $subscription->plan->name }}"
                                    data-teacher="{{ Auth::user()->name }}" data-bs-target="#cancel-modal"
                                    data-bs-toggle="modal" data-bs-dismiss="modal">
                                    {{ trans('account.cancelSubscription') }}
                                </button>
                            </div>
                        </div>
                    @elseif ($subscription && !Auth::user()->plan_id)
                        <!-- Subscribed but Invoice Pending -->
                        <div class="row row-gap-6">
                            <div class="col-md-6 mb-1">
                                <div class="mb-6">
                                    <h6 class="mb-1">{{ trans('account.yourCurrentPlanIs') }}
                                        {{ $subscription->plan->name }}</h6>
                                    <p>{{ $subscription->plan->description }}</p>
                                </div>
                                <div class="mb-6">
                                    <h6 class="mb-1">{{ trans('account.activeUntil') }}
                                        {{ formatDate($subscription->end_date) }}</h6>
                                    <p>{{ trans('account.subscriptionExpirationNotification') }}</p>
                                </div>
                                <div>
                                    <h6 class="mb-1">
                                        <span class="me-1">{{ number_format($subscription->amount, 2) }}
                                            {{ trans('main.currency') }}/
                                            {{ $subscription->period === 1 ? trans('main.monthly') : ($subscription->period === 2 ? trans('main.termly') : ($subscription->period === 3 ? trans('main.yearly') : 'N/A')) }}
                                        </span>
                                        @if ($subscription->plan_id === 3)
                                            <span
                                                class="badge bg-label-primary rounded-pill">{{ trans('admin/plans.popular') }}</span>
                                        @endif
                                    </h6>
                                    <p>{{ trans('account.planPricingDescription') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="alert alert-warning mb-6 alert-dismissible" role="alert">
                                    <h5 class="alert-heading mb-1 d-flex align-items-center">
                                        <span class="alert-icon rounded"><i
                                                class="icon-base ri ri-alert-line icon-22px"></i></span>
                                        <span>{{ trans('account.completeSubscription') }}</span>
                                    </h5>
                                    <p>{{ trans('account.subscriptionNotActive', ['plan' => $subscription->plan->name]) }}
                                    </p>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="close"></button>
                                </div>
                            </div>
                            <div class="col-12 d-flex gap-4 flex-wrap">
                                <a href="{{ route('teacher.plans.index') }}"
                                    class="btn btn-primary waves-effect waves-light">{{ trans('account.upgradePlan') }}</a>
                                <button class="btn btn-outline-danger cancel-subscription waves-effect" id="cancel-button"
                                    data-id="{{ $subscription->id }}" data-plan="{{ $subscription->plan->name }}"
                                    data-teacher="{{ Auth::user()->name }}" data-bs-target="#cancel-modal"
                                    data-bs-toggle="modal" data-bs-dismiss="modal">
                                    {{ trans('account.cancelSubscription') }}
                                </button>
                            </div>
                        </div>
                    @else
                        <!-- Unsubscribed -->
                        <div class="row row-gap-6">
                            <div class="col-12">
                                <div class="alert alert-info mb-6" role="alert">
                                    <h5 class="alert-heading mb-1 d-flex align-items-center">
                                        <span class="alert-icon rounded"><i
                                                class="icon-base ri ri-information-line icon-22px"></i></span>
                                        <span>{{ trans('account.noActiveSubscription') }}</span>
                                    </h5>
                                    <p>{{ trans('account.noSubscriptionMessage') }}</p>
                                </div>
                            </div>
                            <div class="col-12 d-flex gap-4 flex-wrap">
                                <a href="{{ route('teacher.plans.index') }}"
                                    class="btn btn-primary waves-effect waves-light">{{ trans('admin/plans.subscripe_now') }}</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <!-- /Current Plan -->

            <!-- Invoices DataTable -->
            <x-datatable id="invoices-datatable" cardClasses="mb-6" datatableTitle="{{ trans('main.datatableTitle', ['item' => trans('admin/invoices.invoices')]) }}">
                <th></th>
                <th>#</th>
                <th>{{ trans('main.due_amount') }}</th>
                <th>{{ trans('main.plan') }}</th>
                <th>{{ trans('main.date') }}</th>
                <th>{{ trans('main.amount') }}</th>
                <th>{{ trans('main.status') }}</th>
                <th>{{ trans('main.actions') }}</th>
            </x-datatable>
            <!--/ Invoices DataTable -->

            <!-- Subscriptions DataTable -->
            <x-datatable id="subscriptions-datatable" cardClasses="mb-6" datatableTitle="{{ trans('main.datatableTitle', ['item' => trans('admin/teacherSubscriptions.subscriptions')]) }}">
                <th></th>
                <th>#</th>
                <th>{{ trans('main.plan') }}</th>
                <th>{{ trans('main.amount') }}</th>
                <th>{{ trans('main.start_date') }}</th>
                <th>{{ trans('main.end_date') }}</th>
                <th>{{ trans('main.status') }}</th>
            </x-datatable>
            <!-- Subscriptions DataTable -->

            <!-- Transactions DataTable -->
            <x-datatable id="transactions-datatable" cardClasses="mb-6" datatableTitle="{{ trans('main.datatableTitle', ['item' => trans('admin/transactions.transactions')]) }}">
                <th></th>
                <th>{{ trans('main.invoice') }}</th>
                <th>{{ trans('main.status') }}</th>
                <th>{{ trans('main.amount') }}</th>
                <th>{{ trans('main.description') }}</th>
                <th>{{ trans('main.paymentMethod') }}</th>
                <th>{{ trans('main.date') }}</th>
                <th>{{ trans('main.created_at') }}</th>
            </x-datatable>
            <!--/ Transactions DataTable -->
        </div>
    </div>

    <!-- Cancel Modal -->
    <x-modal modalType="cancel"
        modalTitle="{{ trans('main.cancelItem', ['item' => trans('admin/teacherSubscriptions.subscription')]) }}"
        action="{{ route('teacher.subscriptions.cancle') }}" id submitColor="danger"
        submitButton="{{ trans('main.yes_cancel') }}">
        @include('partials.cancel-modal-body')
    </x-modal>
@endsection

@section('page-js')
    <script>
        initializeDataTable('#invoices-datatable', "{{ route('teacher.billing.invoices') }}", [2, 3, 4, 5, 6], [
            { data: "", orderable: false, searchable: false },
            { data: 'uuid', name: 'uuid' },
            { data: 'balance', name: 'balance', orderable: false, searchable: false },
            { data: 'subscription_id', name: 'subscription_id', orderable: false, searchable: false },
            { data: 'date', name: 'date' },
            { data: 'amount', name: 'amount', orderable: false, searchable: false },
            { data: 'status', name: 'status' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]);

        initializeDataTable('#subscriptions-datatable', "{{ route('teacher.subscriptions.index') }}", [2, 3, 4, 5, 6], [
            { data: "", orderable: false, searchable: false },
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'plan_id', name: 'plan_id' },
            { data: 'amount', name: 'amount', orderable: false, searchable: false },
            { data: 'start_date', name: 'start_date', searchable: false },
            { data: 'end_date', name: 'end_date', searchable: false },
            { data: 'status', name: 'status', orderable: false, searchable: false },
        ]);

        initializeDataTable('#transactions-datatable', "{{ route('teacher.billing.transactions') }}", [1, 2, 3, 4, 5, 6, 7], [
            { data: "", orderable: false, searchable: false },
            { data: 'invoice_id', name: 'invoice_id', orderable: false, searchable: false },
            { data: 'type', name: 'type', orderable: false, searchable: false },
            { data: 'amount', name: 'amount' },
            { data: 'description', name: 'description', orderable: false, searchable: false },
            { data: 'payment_method', name: 'payment_method', orderable: false, searchable: false },
            { data: 'date', name: 'date' },
            { data: 'created_at', name: 'created_at' },
        ]);

        // Setup delete modal
        setupModal({
            buttonId: '#cancel-button',
            modalId: '#cancel-modal',
            fields: {
                id: button => button.data('id'),
                itemToCancel: button => `${button.data('plan')} - ${button.data('teacher')}`
            }
        });
        handleDeletionFormSubmit('#cancel-form', '#cancel-modal')
    </script>
@endsection
