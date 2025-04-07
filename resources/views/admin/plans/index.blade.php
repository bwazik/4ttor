@extends('layouts.admin.master')

@section('page-css')

@endsection

@section('title', pageTitle('admin/plans.plans'))

@section('content')
    <!-- DataTable with Buttons -->
    <x-datatable datatableTitle="{{ trans('main.datatableTitle', ['item' => trans('admin/plans.plans')]) }}"
        dataToggle="modal" deleteButton addButton="{{ trans('main.addItem', ['item' => trans('admin/plans.plan')]) }}">
        <th></th>
        <th class="dt-checkboxes-cell dt-checkboxes-select-all"><input type="checkbox" id="select-all" class="form-check-input"></th>
        <th>#</th>
        <th>{{ trans('main.name') }}</th>
        <th>{{ trans('admin/plans.monthly_price') }}</th>
        <th>{{ trans('admin/plans.term_price') }}</th>
        <th>{{ trans('admin/plans.year_price') }}</th>
        <th>{{ trans('admin/plans.student_limit') }}</th>
        <th>{{ trans('admin/plans.assistant_limit') }}</th>
        <th>{{ trans('main.status') }}</th>
        <th>{{ trans('main.actions') }}</th>
    </x-datatable>
    @include('admin.plans.modals')
    <!--/ DataTable with Buttons -->
@endsection

@section('page-js')
    <script>
        initializeDataTable('#datatable', "{{ route('admin.plans.index') }}", [2, 3, 4, 5, 6, 7, 8, 9],
            [
                { data: "", orderable: false, searchable: false },
                { data: 'selectbox', name: 'selectbox', orderable: false, searchable: false },
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'monthly_price', name: 'monthly_price' },
                { data: 'term_price', name: 'term_price' },
                { data: 'year_price', name: 'year_price' },
                { data: 'student_limit', name: 'student_limit' },
                { data: 'assistant_limit', name: 'assistant_limit' },
                { data: 'is_active', name: 'is_active' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
        );

        // Setup add modal
        setupModal({
            buttonId: '#add-button',
            modalId: '#add-modal',
            fields: {
                attendance_reports: () => '',
                financial_reports: () => '',
                performance_reports: () => '',
                whatsapp_messages: () => '',
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
                monthly_price: button => button.data('monthly_price'),
                term_price: button => button.data('term_price'),
                year_price: button => button.data('year_price'),
                student_limit: button => button.data('student_limit'),
                parent_limit: button => button.data('parent_limit'),
                assistant_limit: button => button.data('assistant_limit'),
                group_limit: button => button.data('group_limit'),
                quiz_monthly_limit: button => button.data('quiz_monthly_limit'),
                quiz_term_limit: button => button.data('quiz_term_limit'),
                quiz_year_limit: button => button.data('quiz_year_limit'),
                assignment_monthly_limit: button => button.data('assignment_monthly_limit'),
                assignment_term_limit: button => button.data('assignment_term_limit'),
                assignment_year_limit: button => button.data('assignment_year_limit'),
                attendance_reports: button => button.data('attendance_reports'),
                financial_reports: button => button.data('financial_reports'),
                performance_reports: button => button.data('performance_reports'),
                whatsapp_messages: button => button.data('whatsapp_messages'),
                is_active: button => button.data('is_active'),
                description_ar: button => button.data('description_ar'),
                description_en: button => button.data('description_en'),
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

        let fields = ['name_ar', 'name_en', 'monthly_price', 'term_price', 'year_price', 'student_limit', 'parent_limit', 'assistant_limit', 'group_limit', 'quiz_monthly_limit', 'quiz_term_limit', 'quiz_year_limit', 'assignment_monthly_limit', 'assignment_term_limit', 'assignment_year_limit', 'attendance_reports', 'financial_reports', 'performance_reports', 'whatsapp_messages', 'is_active', 'description_ar', 'description_en'];
        handleFormSubmit('#add-form', fields, '#add-modal', 'modal', '#datatable');
        handleFormSubmit('#edit-form', fields, '#edit-modal', 'modal', '#datatable');
        handleDeletionFormSubmit('#delete-form', '#delete-modal', '#datatable')
        handleDeletionFormSubmit('#delete-selected-form', '#delete-selected-modal', '#datatable')
    </script>
@endsection
