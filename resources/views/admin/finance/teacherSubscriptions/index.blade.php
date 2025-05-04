@extends('layouts.admin.master')

@section('page-css')

@endsection

@section('title', pageTitle('admin/teacherSubscriptions.teacherSubscriptions'))

@section('content')
    <!-- DataTable with Buttons -->
    <x-datatable datatableTitle="{{ trans('main.datatableTitle', ['item' => trans('admin/teacherSubscriptions.teacherSubscriptions')]) }}"
        dataToggle="offcanvas" deleteButton
        addButton="{{ trans('main.addItem', ['item' => trans('admin/teacherSubscriptions.teacherSubscription')]) }}">
        <th></th>
        <th class="dt-checkboxes-cell dt-checkboxes-select-all"><input type="checkbox" id="select-all" class="form-check-input">
        </th>
        <th>#</th>
        <th>{{ trans('main.teacher') }}</th>
        <th>{{ trans('main.plan') }}</th>
        <th>{{ trans('main.amount') }}</th>
        <th>{{ trans('main.start_date') }}</th>
        <th>{{ trans('main.end_date') }}</th>
        <th>{{ trans('main.status') }}</th>
        <th>{{ trans('main.actions') }}</th>
    </x-datatable>
    @include('admin.finance.teacherSubscriptions.modals')
    <!--/ DataTable with Buttons -->
@endsection

@section('page-js')
    <script>
        initializeDataTable('#datatable', "{{ route('admin.teacher-subscriptions.index') }}", [2, 3, 4, 5, 6, 7],
            [{
                    data: "",
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'selectbox',
                    name: 'selectbox',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'teacher_id',
                    name: 'teacher_id'
                },
                {
                    data: 'plan_id',
                    name: 'plan_id'
                },
                {
                    data: 'amount',
                    name: 'amount',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'start_date',
                    name: 'start_date',
                    searchable: false
                },
                {
                    data: 'end_date',
                    name: 'end_date',
                    searchable: false
                },
                {
                    data: 'status',
                    name: 'status',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                }
            ],
        );

        // Setup add modal
        setupModal({
            buttonId: '#add-button',
            modalId: '#add-modal',
            fields: {
                teacher_id: () => '',
                plan_id: () => '',
                period: () => '',
                status: () => '',
            }
        });
        // Setup edit modal
        setupModal({
            buttonId: '#edit-button',
            modalId: '#edit-modal',
            fields: {
                id: button => button.data('id'),
                teacher_id: button => button.data('teacher_id'),
                plan_id: button => button.data('plan_id'),
                period: button => button.data('period'),
                amount: button => button.data('amount'),
                start_date: button => button.data('start_date'),
                end_date: button => button.data('end_date'),
                status: button => button.data('status'),
            }
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

        let fields = ['teacher_id', 'plan_id', 'period', 'amount', 'start_date', 'end_date', 'status'];
        handleFormSubmit('#add-form', fields, '#add-modal', 'offcanvas', '#datatable');
        handleFormSubmit('#edit-form', fields, '#edit-modal', 'offcanvas', '#datatable');
        handleDeletionFormSubmit('#delete-form', '#delete-modal', '#datatable')
        handleDeletionFormSubmit('#delete-selected-form', '#delete-selected-modal', '#datatable')
        fetchSingleDataByAjax('#add-form #plan_id', "{{ route('admin.fetch.plans.data', ['__ID__', '__SECOND_ID__']) }}", [
            { targetSelector: '#add-form #amount', dataKey: 'amount' },
        ], 'plan_id', 'GET', 'period');
    </script>
@endsection
