@extends('layouts.admin.master')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-invoice.css') }}">
@endsection

@section('title', pageTitle(trans('main.previewItem', ['item' => trans('admin/invoices.invoice')])))

@section('content')
    <div class="row invoice-preview">
        <div class="col-xl-9 col-md-8 col-12 mb-md-0 mb-6">
            <div class="card invoice-preview-card p-sm-12 p-6">
                <div class="card-body invoice-preview-header rounded-4 p-6">
                    <div
                        class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column text-heading align-items-xl-center align-items-md-start align-items-sm-center flex-wrap gap-6">
                        <div>
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
                        <div>
                            <h5 class="mb-6">{{ trans('admin/invoices.theInvoice') }} #{{ isAdmin() ? $invoice->id : substr($invoice->uuid, 14, 4) }}</h5>
                            <div class="mb-2">
                                <span>{{ trans('main.status') }}:</span>
                                @switch($invoice->status)
                                    @case(1)
                                    <span class="badge rounded-pill bg-label-warning text-capitalized">{{ trans('main.pending') }}</span>
                                        @break
                                    @case(2)
                                    <span class="badge rounded-pill bg-label-success text-capitalized">{{ trans('main.paid') }}</span>
                                        @break
                                    @case(3)
                                    <span class="badge rounded-pill bg-label-danger text-capitalized">{{ trans('main.overdue') }}</span>
                                        @break
                                    @case(4)
                                    <span class="badge rounded-pill bg-label-secondary text-capitalized">{{ trans('main.canceled') }}</span>
                                        @break
                                    @default
                                    <span class="badge rounded-pill bg-label-secondary text-capitalized">-</span>
                                @endswitch
                            </div>
                            <div class="mb-1">
                                <span>{{ trans('main.date') }}:</span>
                                <span>{{ formatDate($invoice->date) }}</span>
                            </div>
                            <div>
                                <span>{{ trans('main.due_date') }}:</span>
                                <span>{{ formatDate($invoice->due_date) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body py-6 px-0">
                    <div class="d-flex justify-content-between flex-wrap gap-6">
                        <div>
                            <h6>{{ trans('main.teacher') }}:</h6>
                            <p class="mb-1">{{ $invoice->teacher->name }}</p>
                            <p class="mb-1">{{ $invoice->teacher->phone }}</p>
                            <p class="mb-0">{{ $invoice->teacher->email }}</p>
                        </div>
                    </div>
                </div>
                <div class="table-responsive border rounded-4 border-bottom-0">
                    <table class="table m-0">
                        <thead>
                            <tr>
                                <th>{{ trans('main.plan') }}</th>
                                <th>{{ trans('main.amount') }}</th>
                                <th>{{ trans('main.discount') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-nowrap text-heading">{{ $invoice->subscription->plan->name ?? 'N/A' }}
                                </td>
                                <td class="text-nowrap">{{ $invoice->subscription->amount }}
                                    {{ trans('main.currency') }}</td>
                                <td>0.00 %</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="table-responsive">
                    <table class="table m-0 table-borderless">
                        <tbody>
                            <tr>
                                <td class="align-top px-0 py-6">
                                    <h6>{{ trans('main.founder') }}:</h6>
                                    <p class="mb-1">عبدالله محمد فتحي</p>
                                    <p class="mb-1">01098617164</p>
                                    <p class="mb-0">bwazik@outlook.com</p>
                                </td>
                                <td class="pe-0 py-6 w-px-100">
                                    <p class="mb-1">{{ trans('main.amount') }}:</p>
                                    <p class="mb-1 border-bottom pb-2">{{ trans('main.discount') }}:</p>
                                    <p class="mb-0 pt-2">{{ trans('main.total') }}:</p>
                                </td>
                                <td class="text-end px-0 py-6 w-px-100">
                                    <p class="fw-medium mb-1">{{ $invoice->subscription->amount }} {{ trans('main.currency') }}</p>
                                    <p class="fw-medium mb-1 border-bottom pb-2">0.00 %</p>
                                    <p class="fw-medium mb-0 pt-2">{{ $invoice->subscription->amount }} {{ trans('main.currency') }}</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <hr class="mt-0 mb-6" />
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-12">
                            <span class="fw-medium text-heading">{{ trans('main.description') }}:</span>
                            <span>{{ $invoice->description }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-4 col-12 invoice-actions">
            @if(isAdmin())
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('admin.invoices.teachers.edit', $invoice->id) }}" id="print" class="btn btn-primary d-grid w-100 mb-4">
                            <span class="d-flex align-items-center justify-content-center text-nowrap">{{ trans('main.edit') }}</span>
                        </a>
                        <div class="d-flex">
                            <a target="_blank" href="{{ route('admin.invoices.teachers.print', $invoice->id) }}" class="btn btn-outline-secondary d-grid w-100 me-4 waves-effect text-nowrap">
                                {{ trans('main.print') }}
                            </a>
                            <a href="{{ route('admin.invoices.teachers.index') }}" class="btn btn-outline-secondary d-grid w-100 waves-effect text-nowrap">
                                {{ trans('admin/invoices.invoices') }}
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body">
                        @if($invoice->status === 1 || $invoice->status === 3)
                            <a href="{{ $payUrl }}" id="print" class="btn btn-success d-grid w-100 mb-4">
                                <span class="d-flex align-items-center justify-content-center text-nowrap">{{ trans('main.addItem', ['item' => trans('main.payment')]) }}</span>
                            </a>
                        @endif
                        <div class="d-flex">
                            <a target="_blank" href="{{ route('teacher.billing.invoices.print', $invoice->uuid) }}" class="btn btn-outline-secondary d-grid w-100 me-4 waves-effect text-nowrap">
                                {{ trans('main.print') }}
                            </a>
                            <a href="{{ route('teacher.billing.index') }}" class="btn btn-outline-secondary d-grid w-100 waves-effect text-nowrap">
                                {{ trans('account.billing') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('page-js')
    <script></script>
@endsection
