@extends('layouts.admin.master')

@section('page-css')

@endsection

@section('title', pageTitle('admin/invoices.invoices'))

@section('content')
    <!-- DataTable with Buttons -->
    <x-datatable datatableTitle="{{ trans('main.datatableTitle', ['item' => trans('admin/invoices.invoices')]) }}"
        dataToggle="offcanvas" addButton="{{ trans('main.addItem', ['item' => trans('admin/invoices.invoice')]) }}">
        <th></th>
        <th>#</th>
        <th>{{ trans('main.date') }}</th>
        <th>{{ trans('main.amount') }}</th>
        <th>{{ trans('main.teacher') }}</th>
        <th>{{ trans('main.plan') }}</th>
        <th>{{ trans('main.created_at') }}</th>
        <th>{{ trans('main.actions') }}</th>
    </x-datatable>
    @include('admin.finance.invoices.teachers.modals')
    <!--/ DataTable with Buttons -->
@endsection

@section('page-js')
    <script>
        initializeDataTable('#datatable', "{{ route('admin.invoices.teachers.index') }}", [2, 3, 4, 5, 6, 7],
            [
                { data: "", orderable: false, searchable: false },
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'date', name: 'date' },
                { data: 'amount', name: 'amount' },
                { data: 'teacher_id', name: 'teacher_id' },
                { data: 'plan_id', name: 'plan_id' },
                { data: 'created_at', name: 'created_at', orderable: false, searchable: false },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
        );

        // Setup add modal
        setupModal({
            buttonId: '#add-button',
            modalId: '#add-modal',
            fields: {
                teacher_id: () => '',
                plan_id: () => ''
            }
        });

        // Setup delete modal
        setupModal({
            buttonId: '#delete-button',
            modalId: '#delete-modal',
            fields: {
                id: button => button.data('id'),
                itemToDelete: button => `${button.data('teacher_id')} - ${button.data('plan_id')} - ${button.data('amount')}`
            }
        });

        let fields = ['teacher_id', 'plan_id'];
        handleFormSubmit('#add-form', fields, '#add-modal', 'offcanvas', '#datatable');
        handleDeletionFormSubmit('#delete-form', '#delete-modal', '#datatable')
        fetchSingleDataByAjax('#add-form #plan_id', "{{ route('admin.plans.price') }}", '#add-form #amount', 'plan_id')
    </script>
@endsection
