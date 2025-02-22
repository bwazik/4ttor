@extends('layouts.admin.master')

@section('page-css')

@endsection

@section('title', pageTitle('admin/receipts.receipts'))

@section('content')
    <!-- DataTable with Buttons -->
    <x-datatable datatableTitle="{{ trans('main.datatableTitle', ['item' => trans('admin/receipts.receipts')]) }}"
        dataToggle="offcanvas" addButton="{{ trans('main.addItem', ['item' => trans('admin/receipts.receipt')]) }}">
        <th></th>
        <th>#</th>
        <th>{{ trans('main.date') }}</th>
        <th>{{ trans('main.teacher') }}</th>
        <th>{{ trans('main.amount') }}</th>
        <th>{{ trans('main.description') }}</th>
        <th>{{ trans('main.created_at') }}</th>
        <th>{{ trans('main.updated_at') }}</th>
        <th>{{ trans('main.actions') }}</th>
    </x-datatable>
    @include('admin.finance.receipts.teachers.modals')
    <!--/ DataTable with Buttons -->
@endsection

@section('page-js')
    <script>
        initializeDataTable('#datatable', "{{ route('admin.receipts.teachers.index') }}", [2, 3, 4, 5, 6],
            [
                { data: "", orderable: false, searchable: false },
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'date', name: 'date' },
                { data: 'teacher_id', name: 'teacher_id' },
                { data: 'debit', name: 'debit' },
                { data: 'description', name: 'description' },
                { data: 'created_at', name: 'created_at', orderable: false, searchable: false },
                { data: 'updated_at', name: 'updated_at', orderable: false, searchable: false },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
        );

        // Setup add modal
        setupModal({
            buttonId: '#add-button',
            modalId: '#add-modal',
            fields: {
                teacher_id: () => '',
            }
        });
        // Setup edit modal
        setupModal({
            buttonId: '#edit-button',
            modalId: '#edit-modal',
            fields: {
                id: button => button.data('id'),
                teacher_id: button => button.data('teacher_id'),
                accountBalance: button => button.data('account_balance'),
                amount: button => button.data('amount'),
                description: button => button.data('description'),
            }
        });
        // Setup delete modal
        setupModal({
            buttonId: '#delete-button',
            modalId: '#delete-modal',
            fields: {
                id: button => button.data('id'),
                itemToDelete: button => `${button.data('teacher_id')} - ${button.data('amount')}`
            }
        });

        let fields = ['teacher_id', 'amount', 'description'];
        handleFormSubmit('#add-form', fields, '#add-modal', 'offcanvas', '#datatable');
        handleFormSubmit('#edit-form', fields, '#edit-modal', 'offcanvas', '#datatable');
        handleDeletionFormSubmit('#delete-form', '#delete-modal', '#datatable')
        fetchSingleDataByAjax('#add-form #teacher_id', "{{ route('admin.teachers.accountBalance', '__ID__') }}", '#add-form #accountBalance', 'teacher_id', 'GET')
    </script>
@endsection
