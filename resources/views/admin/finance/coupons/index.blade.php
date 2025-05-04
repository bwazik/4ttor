@extends('layouts.admin.master')

@section('page-css')

@endsection

@section('title', pageTitle('admin/coupons.coupons'))

@section('content')
    <!-- DataTable with Buttons -->
    <x-datatable datatableTitle="{{ trans('main.datatableTitle', ['item' => trans('admin/coupons.coupons')]) }}"
        dataToggle="offcanvas" deleteButton addButton="{{ trans('main.addItem', ['item' => trans('admin/coupons.coupon')]) }}">
        <th></th>
        <th class="dt-checkboxes-cell dt-checkboxes-select-all"><input type="checkbox" id="select-all" class="form-check-input"></th>
        <th>#</th>
        <th>{{ trans('main.code') }}</th>
        <th>{{ trans('main.status') }}</th>
        <th>{{ trans('main.amount') }}</th>
        <th>{{ trans('main.teacher') }}</th>
        <th>{{ trans('main.student') }}</th>
        <th>{{ trans('main.actions') }}</th>
    </x-datatable>
    @include('admin.finance.coupons.modals')
    <!--/ DataTable with Buttons -->
@endsection

@section('page-js')
    <script>
        initializeDataTable('#datatable', "{{ route('admin.coupons.index') }}", [2, 3, 4, 5, 6, 7],
            [
                { data: "", orderable: false, searchable: false },
                { data: 'selectbox', name: 'selectbox', orderable: false, searchable: false },
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'code', name: 'code' },
                { data: 'is_used', name: 'is_used' },
                { data: 'amount', name: 'amount' },
                { data: 'teacher_id', name: 'teacher_id' },
                { data: 'student_id', name: 'student_id' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
        );


        // Setup add modal
        setupModal({
            buttonId: '#add-button',
            modalId: '#add-modal',
            fields: {
                code: () => '{{ substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10) }}',
                is_used: () => '',
                teacher_id: () => '',
                student_id: () => '',
            }
        });
        // Setup edit modal
        setupModal({
            buttonId: '#edit-button',
            modalId: '#edit-modal',
            fields: {
                id: button => button.data('id'),
                code: button => button.data('code'),
                is_used: button => button.data('is_used'),
                amount: button => button.data('amount'),
                teacher_id: button => button.data('teacher_id'),
                student_id: button => button.data('student_id'),
            }
        });
        // Setup delete modal
        setupModal({
            buttonId: '#delete-button',
            modalId: '#delete-modal',
            fields: {
                id: button => button.data('id'),
                itemToDelete: button => `${button.data('code')}`
            }
        });

        let fields = ['code', 'is_used', 'amount', 'teacher_id', 'student_id'];
        handleFormSubmit('#add-form', fields, '#add-modal', 'offcanvas', '#datatable');
        handleFormSubmit('#edit-form', fields, '#edit-modal', 'offcanvas', '#datatable');
        handleDeletionFormSubmit('#delete-form', '#delete-modal', '#datatable')
        handleDeletionFormSubmit('#delete-selected-form', '#delete-selected-modal', '#datatable')
    </script>
@endsection
