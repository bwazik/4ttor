@extends('layouts.student.master')

@section('page-css')

@endsection

@section('title', pageTitle('admin/plans.plans'))

@section('content')
    <div class="row">
        <div class="col-md-12">
            @include('student.account.navbar')
            <!-- Coupons Form -->
            <x-account.coupons action="{{ route('student.account.coupons.redeem') }}" />
            <!-- Coupons Form -->

            <!-- Coupons DataTable -->
            <x-datatable cardClasses="mb-6"
                datatableTitle="{{ trans('main.datatableTitle', ['item' => trans('admin/coupons.coupons')]) }}">
                <th></th>
                <th>#</th>
                <th>{{ trans('main.code') }}</th>
                <th>{{ trans('main.status') }}</th>
                <th>{{ trans('main.amount') }}</th>
            </x-datatable>
            <!--/ Coupons DataTable -->

        </div>
    </div>
@endsection

@section('page-js')

    <script>
        initializeDataTable('#datatable', "{{ route('student.account.coupons.index') }}", [2, 3, 4], [{
                data: "",
                orderable: false,
                searchable: false
            },
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },
            {
                data: 'code',
                name: 'code'
            },
            {
                data: 'is_used',
                name: 'is_used'
            },
            {
                data: 'amount',
                name: 'amount'
            },
        ]);

        let fields = ['code'];
        handleFormSubmit('#redeem-form', fields);
    </script>
@endsection
