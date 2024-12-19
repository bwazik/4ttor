@extends('layouts.admin.master')

@section('page-css')

@endsection

@section('title', pageTitle('admin/teachers.teachers'))

@section('content')
    <!-- DataTable with Buttons -->
    <x-datatable datatableTitle="{{ trans('main.datatableTitle', ['item' => trans('admin/teachers.teachers')]) }}"
        dataToggle="offcanvas" deleteButton archiveButton addButton="{{ trans('main.addItem', ['item' => trans('admin/teachers.teacher')]) }}">
        <th></th>
        <th class="dt-checkboxes-cell dt-checkboxes-select-all"><input type="checkbox" id="select-all"class="form-check-input">
        </th>
        <th>#</th>
        <th>{{ trans('main.name') }}</th>
        <th>{{ trans('main.username') }}</th>
        <th>{{ trans('main.phone') }}</th>
        <th>{{ trans('main.plan') }}</th>
        <th>{{ trans('main.status') }}</th>
        <th>{{ trans('main.actions') }}</th>
    </x-datatable>
    @include('admin.teachers.manage.modals')
    <!--/ DataTable with Buttons -->
@endsection

@section('page-js')
    <script>
        initializeDataTable('#datatable', "{{ route('admin.teachers.index') }}", [2, 3, 4, 5, 6, 7],
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
                    data: 'details',
                    name: 'details',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'username',
                    name: 'username'
                },
                {
                    data: 'phone',
                    name: 'phone'
                },
                {
                    data: 'plan_id',
                    name: 'plan_id'
                },
                {
                    data: 'is_active',
                    name: 'is_active'
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
                subject_id: () => '',
                grades: () => '',
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
                username: button => button.data('username'),
                email: button => button.data('email'),
                phone: button => button.data('phone'),
                password: button => button.data('password'),
                subject_id: button => button.data('subject_id'),
                grades: button => button.data('grades'),
                is_active: button => button.data('is_active'),
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
        // Setup archive modal
        setupModal({
            buttonId: '#archive-button',
            modalId: '#archive-modal',
            fields: {
                id: button => button.data('id'),
                itemToArchive: button => `${button.data('name_ar')} - ${button.data('name_en')}`
            }
        });

        let fields = ['name_ar', 'name_en', 'username', 'email', 'phone', 'password', 'subject_id', 'grades'];
        handleFormSubmit('#add-form', fields, '#add-modal', 'offcanvas', '#datatable');
        handleFormSubmit('#edit-form', fields, '#edit-modal', 'offcanvas', '#datatable');
        handleDeletionFormSubmit('#delete-form', '#delete-modal', '#datatable')
        handleDeletionFormSubmit('#archive-form', '#archive-modal', '#datatable')
        handleDeletionFormSubmit('#delete-selected-form', '#delete-selected-modal', '#datatable')
        handleDeletionFormSubmit('#archive-selected-form', '#archive-selected-modal', '#datatable')


        $('#add-form #name_en').on('input', function() {
            const username = '4ttor' + generateRandomString() + 't';
            $('#add-form #username').val(username);
        });
    </script>
@endsection
