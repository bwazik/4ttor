@extends('layouts.admin.master')

@section('page-css')

@endsection

@section('title', pageTitle('admin/invoices.invoices'))

@section('content')
    @include('admin.finance.invoices.statistics')
    <!-- DataTable with Buttons -->
    <x-datatable datatableTitle="{{ trans('main.datatableTitle', ['item' => trans('admin/invoices.invoices')]) }}"
        dataToggle="offcanvas" hrefButton="{{ trans('main.addItem', ['item' => trans('admin/invoices.invoice')]) }}"
        hrefButtonRoute="{{ route('admin.invoices.teachers.create') }}">
        <th></th>
        <th>#</th>
        <th>{{ trans('main.due_amount') }}</th>
        <th>{{ trans('main.teacher') }}</th>
        <th>{{ trans('main.plan') }}</th>
        <th>{{ trans('main.date') }}</th>
        <th>{{ trans('main.amount') }}</th>
        <th>{{ trans('main.status') }}</th>
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
                { data: 'id', name: 'id' },
                { data: 'balance', name: 'balance', orderable: false, searchable: false },
                { data: 'details', name: 'teacher_id' },
                { data: 'subscription_id', name: 'subscription_id', orderable: false, searchable: false },
                { data: 'date', name: 'date' },
                { data: 'amount', name: 'amount', orderable: false, searchable: false },
                { data: 'status', name: 'status' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
        );

        // Setup Payment modal
        setupModal({
            buttonId: '#payment-button',
            modalId: '#payment-modal',
            fields: {
                id: button => button.data('id'),
                payment_method: () => 1,
            },
            onShow: function(modal, button) {
                const form = modal[0].querySelector('#payment-form');
                const dueAmount = form.querySelector('#due_amount');
                form.action = form.action.replace('__ID__', button.data('id'));
                dueAmount.textContent = button.data('due_amount');
            },
        });
        // Setup Refund modal
        setupModal({
            buttonId: '#refund-button',
            modalId: '#refund-modal',
            fields: {
                id: button => button.data('id'),
                payment_method: () => 1,
            },
            onShow: function(modal, button) {
                const form = modal[0].querySelector('#refund-form');
                const dueAmount = form.querySelector('#due_amount');
                form.action = form.action.replace('__ID__', button.data('id'));
                dueAmount.textContent = button.data('due_amount');
            },
        });
        // Setup delete modal
        setupModal({
            buttonId: '#delete-button',
            modalId: '#delete-modal',
            fields: {
                id: button => button.data('id'),
                itemToDelete: button => `${button.data('plan')} - ${button.data('teacher')}`
            }
        });
        // Setup cancel modal
        setupModal({
            buttonId: '#cancel-button',
            modalId: '#cancel-modal',
            fields: {
                id: button => button.data('id'),
                itemToCancel: button => `${button.data('plan')} - ${button.data('teacher')}`
            }
        });
        // Setup archive modal
        setupModal({
            buttonId: '#archive-button',
            modalId: '#archive-modal',
            fields: {
                id: button => button.data('id'),
                itemToArchive: button => `${button.data('plan')} - ${button.data('teacher')}`
            }
        });

        let paymentFields = ['amount', 'payment_method', 'description'];
        handleFormSubmit('#payment-form', paymentFields, '#payment-modal', 'offcanvas', '#datatable');
        handleFormSubmit('#refund-form', paymentFields, '#refund-modal', 'offcanvas', '#datatable');
        handleDeletionFormSubmit('#delete-form', '#delete-modal', '#datatable')
        handleDeletionFormSubmit('#cancel-form', '#cancel-modal', '#datatable')
        handleDeletionFormSubmit('#archive-form', '#archive-modal', '#datatable')
    </script>
@endsection
