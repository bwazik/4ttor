@extends('layouts.admin.master')

@section('page-css')

@endsection

@section('title', pageTitle('admin/invoices.invoices'))

@section('content')
    <!-- DataTable with Buttons -->
    <x-datatable datatableTitle="{{ trans('main.datatableTitle', ['item' => trans('admin/invoices.invoices')]) }}"
        dataToggle="modal">
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
    <!-- Delete Modal -->
    <x-modal modalType="delete" modalTitle="{{ trans('main.deleteItem', ['item' => trans('admin/invoices.invoice')]) }}"
        action="{{ route('admin.invoices.teachers.delete') }}" id submitColor="danger" submitButton="{{ trans('main.yes_delete') }}">
        @include('partials.delete-modal-body')
    </x-modal>
    <!-- Restore Modal -->
    <x-modal modalType="restore" modalTitle="{{ trans('main.restoreItem', ['item' => trans('admin/invoices.invoice')]) }}"
        action="{{ route('admin.invoices.teachers.restore') }}" id submitButton="{{ trans('main.yes_restore') }}">
        @include('partials.restore-modal-body')
    </x-modal>
    <!--/ DataTable with Buttons -->
@endsection

@section('page-js')
    <script>
        initializeDataTable('#datatable', "{{ route('admin.invoices.teachers.archived') }}", [2, 3, 4, 5, 6, 7],
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

        // Setup delete modal
        setupModal({
            buttonId: '#delete-button',
            modalId: '#delete-modal',
            fields: {
                id: button => button.data('id'),
                itemToDelete: button => `${button.data('plan')} - ${button.data('teacher')}`
            }
        });
        // Setup restore modal
        setupModal({
            buttonId: '#restore-button',
            modalId: '#restore-modal',
            fields: {
                id: button => button.data('id'),
                itemToRestore: button => `${button.data('plan')} - ${button.data('teacher')}`
            }
        });

        handleDeletionFormSubmit('#delete-form', '#delete-modal', '#datatable')
        handleDeletionFormSubmit('#restore-form', '#restore-modal', '#datatable')
    </script>
@endsection
