@extends('layouts.admin.master')

@section('page-css')

@endsection

@section('title', pageTitle('admin/assistants.assistants'))

@section('content')
    <!-- DataTable with Buttons -->
    <x-datatable datatableTitle="{{ trans('main.datatableTitle', ['item' => trans('admin/assistants.assistants')]) }}"
        dataToggle="offcanvas" deleteButton archiveButton addButton="{{ trans('main.addItem', ['item' => trans('admin/assistants.assistant')]) }}">
        <th></th>
        <th class="dt-checkboxes-cell dt-checkboxes-select-all"><input type="checkbox" id="select-all"class="form-check-input">
        </th>
        <th>#</th>
        <th>{{ trans('main.name') }}</th>
        <th>{{ trans('main.username') }}</th>
        <th>{{ trans('main.phone') }}</th>
        <th>{{ trans('main.teacher') }}</th>
        <th>{{ trans('main.status') }}</th>
        <th>{{ trans('main.actions') }}</th>
    </x-datatable>
    @include('admin.assistants.manage.modals')
    <!--/ DataTable with Buttons -->
@endsection

@section('page-js')
    <script>
        initializeDataTable('#datatable', "{{ route('admin.assistants.index') }}", [2, 3, 4, 5, 6, 7],
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
                    data: 'teacher_id',
                    name: 'teacher_id'
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
                teacher_id: () => '',
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
                teacher_id: button => button.data('teacher_id'),
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

        let fields = ['name_ar', 'name_en', 'username', 'email', 'phone', 'password', 'teacher_id'];
        handleFormSubmit('#add-form', fields, '#add-modal', 'offcanvas', '#datatable');
        handleFormSubmit('#edit-form', fields, '#edit-modal', 'offcanvas', '#datatable');
        handleDeletionFormSubmit('#delete-form', '#delete-modal', '#datatable')
        handleDeletionFormSubmit('#archive-form', '#archive-modal', '#datatable')
        handleDeletionFormSubmit('#delete-selected-form', '#delete-selected-modal', '#datatable')
        handleDeletionFormSubmit('#archive-selected-form', '#archive-selected-modal', '#datatable')

        generateRandomUsername('a');
    </script>
@endsection
