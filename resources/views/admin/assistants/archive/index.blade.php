@extends('layouts.admin.master')

@section('page-css')

@endsection

@section('title', pageTitle('admin/assistants.assistants'))

@section('content')
    <!-- DataTable with Buttons -->
    <x-datatable datatableTitle="{{ trans('main.datatableTitle2', ['item' => trans('admin/assistants.assistants')]) }}"
        dataToggle="modal" restoreButton deleteButton>
        <th></th>
        <th class="dt-checkboxes-cell dt-checkboxes-select-all"><input type="checkbox" id="select-all" class="form-check-input">
        </th>
        <th>#</th>
        <th>{{ trans('main.name') }}</th>
        <th>{{ trans('main.username') }}</th>
        <th>{{ trans('main.phone') }}</th>
        <th>{{ trans('main.actions') }}</th>
    </x-datatable>
    @include('admin.assistants.archive.modals')
    <!--/ DataTable with Buttons -->
@endsection

@section('page-js')
    <script>
        initializeDataTable('#datatable', "{{ route('admin.assistants.archived') }}", [2, 3, 4, 5],
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
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                }
            ],
        );

        // Setup delete modal
        setupModal({
            buttonId: '#delete-button',
            modalId: '#delete-modal',
            fields: {
                id: button => button.data('id'),
                itemToDelete: button => `${button.data('name_ar')} - ${button.data('name_en')}`
            }
        });
        // Setup restore modal
        setupModal({
            buttonId: '#restore-button',
            modalId: '#restore-modal',
            fields: {
                id: button => button.data('id'),
                itemToRestore: button => `${button.data('name_ar')} - ${button.data('name_en')}`
            }
        });

        handleDeletionFormSubmit('#delete-form', '#delete-modal', '#datatable')
        handleDeletionFormSubmit('#restore-form', '#restore-modal', '#datatable')
        handleDeletionFormSubmit('#delete-selected-form', '#delete-selected-modal', '#datatable')
        handleDeletionFormSubmit('#restore-selected-form', '#restore-selected-modal', '#datatable')
    </script>
@endsection
