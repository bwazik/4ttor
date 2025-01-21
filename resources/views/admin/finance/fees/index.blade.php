@extends('layouts.admin.master')

@section('page-css')

@endsection

@section('title', pageTitle('admin/fees.fees'))

@section('content')
    <!-- DataTable with Buttons -->
    <x-datatable datatableTitle="{{ trans('main.datatableTitle', ['item' => trans('admin/fees.fees')]) }}"
        dataToggle="offcanvas" deleteButton addButton="{{ trans('main.addItem', ['item' => trans('admin/fees.fee')]) }}">
        <th></th>
        <th class="dt-checkboxes-cell dt-checkboxes-select-all"><input type="checkbox" id="select-all"class="form-check-input"></th>
        <th>#</th>
        <th>{{ trans('main.name') }}</th>
        <th>{{ trans('main.amount') }}</th>
        <th>{{ trans('main.teacher') }}</th>
        <th>{{ trans('main.grade') }}</th>
        <th>{{ trans('main.created_at') }}</th>
        <th>{{ trans('main.actions') }}</th>
    </x-datatable>
    @include('admin.finance.fees.modals')
    <!--/ DataTable with Buttons -->
@endsection

@section('page-js')
    <script>
        initializeDataTable('#datatable', "{{ route('admin.fees.index') }}", [2, 3, 4, 5, 6, 7],
            [
                { data: "", orderable: false, searchable: false },
                { data: 'selectbox', name: 'selectbox', orderable: false, searchable: false },
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'amount', name: 'amount' },
                { data: 'teacher_id', name: 'teacher_id' },
                { data: 'grade_id', name: 'grade_id' },
                { data: 'created_at', name: 'created_at' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
        );

        // Setup add modal
        setupModal({
            buttonId: '#add-button',
            modalId: '#add-modal',
            fields: {
                teacher_id: () => '',
                grade_id: () => ''
            }
        });
        // Setup edit modal
        setupModal({
            buttonId: '#edit-button',
            modalId: '#edit-modal',
            fields: {
                id: button => button.data('id'),
                name_ar: button => button.data('name_ar'),
                name_en: button => button.data('name_en'),
                amount: button => button.data('amount'),
                teacher_id: button => button.data('teacher_id'),
                grade_id: button => button.data('grade_id'),
            }
        });
        // Setup delete modal
        setupModal({
            buttonId: '#delete-button',
            modalId: '#delete-modal',
            fields: {
                id: button => button.data('id'),
                itemToDelete: button => `${button.data('name_ar')} - ${button.data('name_en')}`
            }
        });

        let fields = ['name_ar', 'name_en', 'amount', 'teacher_id', 'grade_id'];
        handleFormSubmit('#add-form', fields, '#add-modal', 'offcanvas', '#datatable');
        handleFormSubmit('#edit-form', fields, '#edit-modal', 'offcanvas', '#datatable');
        handleDeletionFormSubmit('#delete-form', '#delete-modal', '#datatable')
        handleDeletionFormSubmit('#delete-selected-form', '#delete-selected-modal', '#datatable')
        fetchDataByAjax('#add-form #teacher_id', "{{ route('admin.teachers.grades') }}", '#add-form #grade_id', 'teacher_id')
    </script>
@endsection
