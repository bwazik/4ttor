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
        <th>{{ trans('main.student') }}</th>
        <th>{{ trans('main.fee') }}</th>
        <th>{{ trans('main.created_at') }}</th>
        <th>{{ trans('main.actions') }}</th>
    </x-datatable>
    @include('admin.finance.invoices.students.modals')
    <!--/ DataTable with Buttons -->
@endsection

@section('page-js')
    <script>
        initializeDataTable('#datatable', "{{ route('admin.invoices.students.index') }}", [2, 3, 4, 5, 6, 7],
            [
                { data: "", orderable: false, searchable: false },
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'date', name: 'date' },
                { data: 'amount', name: 'amount' },
                { data: 'student_id', name: 'student_id' },
                { data: 'fee_id', name: 'fee_id' },
                { data: 'created_at', name: 'created_at', orderable: false, searchable: false },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
        );

        // Setup add modal
        setupModal({
            buttonId: '#add-button',
            modalId: '#add-modal',
            fields: {
                student_id: () => '',
                teacher_id: () => '',
                fee_id: () => ''
            }
        });

        // Setup delete modal
        setupModal({
            buttonId: '#delete-button',
            modalId: '#delete-modal',
            fields: {
                id: button => button.data('id'),
                itemToDelete: button => `${button.data('student_id')} - ${button.data('fee_id')} - ${button.data('amount')}`
            }
        });

        let fields = ['student_id', 'fee_id'];
        handleFormSubmit('#add-form', fields, '#add-modal', 'offcanvas', '#datatable');
        handleDeletionFormSubmit('#delete-form', '#delete-modal', '#datatable')
        fetchSingleDataByAjax('#add-form #student_id', "{{ route('admin.students.grade', '__ID__') }}", '#add-form #grade_id', 'student_id', 'GET')
        fetchMultipleDataByAjax('#add-form #student_id', "{{ route('admin.students.teachers', '__ID__') }}", '#add-form #teacher_id', 'student_id', 'GET')
        fetchMultipleDataByAjax('#add-form #teacher_id', "{{ route('admin.teachers.fees', '__ID__') }}", '#add-form #fee_id', 'teacher_id', 'GET')
        fetchSingleDataByAjax('#add-form #fee_id', "{{ route('admin.fees.amount', '__ID__') }}", '#add-form #amount', 'fee_id', 'GET')
    </script>
@endsection
