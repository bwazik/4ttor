@extends('layouts.teacher.master')

@section('page-css')

@endsection

@section('title', pageTitle('admin/assignments.assignments'))

@section('content')
    <!-- DataTable with Buttons -->
    <x-datatable datatableTitle="{{ trans('main.datatableTitle', ['item' => trans('admin/assignments.assignments')]) }}"
        dataToggle="modal" deleteButton addButton="{{ trans('main.addItem', ['item' => trans('admin/assignments.assignment')]) }}">
        <th></th>
        <th class="dt-checkboxes-cell dt-checkboxes-select-all"><input type="checkbox" id="select-all" class="form-check-input"></th>
        <th>#</th>
        <th>{{ trans('main.name') }}</th>
        <th>{{ trans('main.grade') }}</th>
        <th>{{ trans('main.deadline') }}</th>
        <th>{{ trans('main.score') }}</th>
        <th>{{ trans('main.actions') }}</th>
    </x-datatable>
    @include('teacher.activities.assignments.modals')
    <!--/ DataTable with Buttons -->
@endsection

@section('page-js')
    <script>
        initializeDataTable('#datatable', "{{ route('teacher.assignments.index') }}", [2, 3, 4, 5, 6, 7],
            [
                { data: "", orderable: false, searchable: false },
                { data: 'selectbox', name: 'selectbox', orderable: false, searchable: false },
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'title', name: 'title' },
                { data: 'grade_id', name: 'grade_id' },
                { data: 'deadline', name: 'deadline', orderable: false, searchable: false },
                { data: 'score', name: 'score' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
        );

        // Setup add modal
        setupModal({
            buttonId: '#add-button',
            modalId: '#add-modal',
            fields: {
                grade_id: () => '',
                groups: () => '',
            }
        });
        // Setup edit modal
        setupModal({
            buttonId: '#edit-button',
            modalId: '#edit-modal',
            fields: {
                id: button => button.data('id'),
                title_ar: button => button.data('title_ar'),
                title_en: button => button.data('title_en'),
                grade_id: button => button.data('grade_id'),
                groups: button => button.data('groups'),
                description: button => button.data('description'),
                deadline: button => button.data('deadline'),
                score: button => button.data('score'),
            }
        });
        // Setup delete modal
        setupModal({
            buttonId: '#delete-button',
            modalId: '#delete-modal',
            fields: {
                id: button => button.data('id'),
                itemToDelete: button => `${button.data('title_ar')} - ${button.data('title_en')}`
            }
        });

        let fields = ['teacher_id', 'grade_id', 'title_ar', 'title_en', 'description', 'deadline', 'score'];
        handleFormSubmit('#add-form', fields, '#add-modal', 'modal', '#datatable');
        handleFormSubmit('#edit-form', fields, '#edit-modal', 'modal', '#datatable');
        handleDeletionFormSubmit('#delete-form', '#delete-modal', '#datatable')
        handleDeletionFormSubmit('#delete-selected-form', '#delete-selected-modal', '#datatable')
        fetchMultipleDataByAjax('#add-form #grade_id', "{{ route('teacher.fetch.grade.groups', '__ID__') }}", '#add-form #groups', 'grade_id', 'GET')
    </script>
@endsection
