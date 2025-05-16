<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"
    class="light-style layout-wide" data-theme="theme-default" data-assets-path="{{ asset('assets') }}/"
    data-template="vertical-menu-template" data-style="light">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="description"
        content="Shattor Platform - Empowering teachers with tools to manage students and create engaging educational experiences." />
    <meta name="author" content="Abdullah Mohamed (Bazoka), Developer and Founder of Shattor Platform" />
    <meta name="keywords" content="Shattor, education platform, teacher tools, student management, online learning" />
    <!-- Title -->
    <title>{{ pageTitle(trans('main.printItem', ['item' => trans('admin/invoices.invoice')])) }}</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/brand/navbar.png') }}" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@100..900&display=swap" rel="stylesheet">
    <!-- Vendor Fonts -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/remixicon/remixicon.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/flag-icons.css') }}" />
    <!-- Menu waves for no-customizer fix -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />
    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}" />

    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-invoice-print.css') }}">
    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="{{ asset('assets/vendor/js/template-customizer.js') }}"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('assets/js/config.js') }}"></script>
</head>

<body>
    <div class="invoice-print p-6">
        <div class="d-flex justify-content-between flex-row">
            <div class="mb-6">
                <div class="d-flex svg-illustration align-items-center gap-2 mb-6">
                    <span class="app-brand-logo demo">
                        <img width="60" height="60" src="{{ asset('assets/img/brand/navbar.png') }}"
                            alt="Shattor">
                    </span>
                    <span class="h4 mb-0 app-brand-text fw-semibold">{{ trans('layouts/sidebar.platformName') }}
                        </span>
                </div>
                <p class="mb-1">01098617164</p>
                <p class="mb-0">bwazik@outlook.com</p>
            </div>
            <div>
                <h4 class="mb-6">{{ strtoupper(trans('admin/invoices.theInvoice')) }} #{{ isAdmin() ? $invoice->id : substr($invoice->uuid, 14, 4) }}</h4>
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
        <hr class="mb-6" />
        <div class="d-flex justify-content-between mb-6">
            <div class="my-2">
                <h6>{{ trans('main.teacher') }}:</h6>
                <p class="mb-1">{{ $invoice->teacher->name }}</p>
                <p class="mb-1">{{ $invoice->teacher->phone }}</p>
                <p class="mb-0">{{ $invoice->teacher->email }}</p>
            </div>
        </div>
        <div class="table-responsive border border-bottom-0 rounded">
            <table class="table m-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ trans('main.plan') }}</th>
                        <th>{{ trans('main.amount') }}</th>
                        <th>{{ trans('main.discount') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $invoice->subscription->plan->name ?? 'N/A' }}</td>
                        <td>{{ $invoice->subscription->amount }} {{ trans('main.currency') }}
                        </td>
                        <td>0.00 %</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="table-responsive">
            <table class="table m-0 table-borderless">
                <tbody>
                    <tr>
                        <td class="align-top px-6 py-6">
                            <h6>{{ trans('main.founder') }}:</h6>
                            <p class="mb-1">عبدالله محمد فتحي</p>
                            <p class="mb-1">01098617164</p>
                            <p class="mb-0">bwazik@outlook.com</p>
                        </td>
                        <td class="px-0 py-12 w-px-100">
                            <p class="mb-2">{{ trans('main.amount') }}:</p>
                            <p class="mb-2 border-bottom pb-2">{{ trans('main.discount') }}:</p>
                            <p class="mb-0 pt-2">{{ trans('main.total') }}:</p>
                        </td>
                        <td class="text-end px-0 py-6 w-px-100">
                            <p class="fw-medium mb-2">{{ $invoice->subscription->amount }}
                                {{ trans('main.currency') }}</p>
                            <p class="fw-medium mb-2 border-bottom pb-2">0.00 %</p>
                            <p class="fw-medium mb-0 pt-2">{{ $invoice->subscription->amount }}
                                {{ trans('main.currency') }}</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <hr class="mt-0 mb-6" />
        <div class="row">
            <div class="col-12">
                <span class="fw-medium">{{ trans('main.description') }}:</span>
                <span>{{ $invoice->description }}</span>
            </div>
        </div>
    </div>

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/hammer/hammer.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
    <!-- endbuild -->

    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script>
        'use strict';
        (function() {
            window.print();
        })();
    </script>
</body>

</html>
