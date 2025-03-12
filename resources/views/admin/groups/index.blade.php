@extends('layouts.admin.master')

@section('page-css')

@endsection

@section('title', pageTitle('admin/groups.groups'))

@section('content')
    <!-- DataTable with Buttons -->
    <x-datatable datatableTitle="{{ trans('main.datatableTitle', ['item' => trans('admin/groups.groups')]) }}"
        dataToggle="offcanvas" deleteButton addButton="{{ trans('main.addItem', ['item' => trans('admin/groups.group')]) }}">
        <th></th>
        <th class="dt-checkboxes-cell dt-checkboxes-select-all"><input type="checkbox" id="select-all" class="form-check-input"></th>
        <th>#</th>
        <th>{{ trans('main.name') }}</th>
        <th>{{ trans('main.teacher') }}</th>
        <th>{{ trans('main.grade') }}</th>
        <th>{{ trans('admin/groups.day_1') }}</th>
        <th>{{ trans('admin/groups.day_2') }}</th>
        <th>{{ trans('admin/groups.time') }}</th>
        <th>{{ trans('main.status') }}</th>
        <th>{{ trans('main.created_at') }}</th>
        <th>{{ trans('main.updated_at') }}</th>
        <th>{{ trans('main.actions') }}</th>
    </x-datatable>
    @include('admin.groups.modals')
    <!--/ DataTable with Buttons -->
@endsection

@section('page-js')
    <script>
        initializeDataTable('#datatable', "{{ route('admin.groups.index') }}", [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            [
                { data: "", orderable: false, searchable: false },
                { data: 'selectbox', name: 'selectbox', orderable: false, searchable: false },
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'teacher_id', name: 'teacher_id' },
                { data: 'grade_id', name: 'grade_id' },
                { data: 'day_1', name: 'day_1' },
                { data: 'day_2', name: 'day_2' },
                { data: 'time', name: 'time' },
                { data: 'is_active', name: 'is_active' },
                { data: 'created_at', name: 'created_at' },
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
                grade_id: () => '',
                day_1: () => '',
                day_2: () => '',
                is_active: () => '',
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
                teacher_id: button => button.data('teacher_id'),
                grade_id: button => button.data('grade_id'),
                day_1: button => button.data('day_1'),
                day_2: button => button.data('day_2'),
                time: button => button.data('time'),
                is_active: button => button.data('is_active')
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

        let fields = ['name_ar', 'name_en', 'teacher_id', 'grade_id', 'day_1', 'day_2', 'time', 'is_active'];
        handleFormSubmit('#add-form', fields, '#add-modal', 'offcanvas', '#datatable');
        handleFormSubmit('#edit-form', fields, '#edit-modal', 'offcanvas', '#datatable');
        handleDeletionFormSubmit('#delete-form', '#delete-modal', '#datatable')
        handleDeletionFormSubmit('#delete-selected-form', '#delete-selected-modal', '#datatable')
        fetchMultipleDataByAjax('#add-form #teacher_id', "{{ route('admin.teachers.grades', '__ID__') }}", '#add-form #grade_id', 'teacher_id', 'GET');

        $('#day_1, #day_2, #time').on('change', function () {
            updateGroupNames();
        });
    </script>
@endsection
