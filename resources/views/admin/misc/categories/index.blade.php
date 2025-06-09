@extends('layouts.admin.master')

@section('page-css')

@endsection

@section('title', pageTitle('admin/categories.categories'))

@section('content')
    <!-- DataTable with Buttons -->
    <x-datatable datatableTitle="{{ trans('main.datatableTitle', ['item' => trans('admin/categories.categories')]) }}"
        dataToggle="offcanvas" deleteButton addButton="{{ trans('main.addItem', ['item' => trans('admin/categories.category')]) }}">
        <th></th>
        <th class="dt-checkboxes-cell dt-checkboxes-select-all"><input type="checkbox" id="select-all" class="form-check-input"></th>
        <th>#</th>
        <th>{{ trans('main.name') }}</th>
        <th>{{ trans('main.slug') }}</th>
        <th>{{ trans('main.icon') }}</th>
        <th>{{ trans('main.order') }}</th>
        <th>{{ trans('main.actions') }}</th>
    </x-datatable>
    @include('admin.misc.categories.modals')
    <!--/ DataTable with Buttons -->
@endsection

@section('page-js')
    <script>
        initializeDataTable('#datatable', "{{ route('admin.categories.index') }}", [2, 3, 4, 5, 6],
            [
                { data: "", orderable: false, searchable: false },
                { data: 'selectbox', name: 'selectbox', orderable: false, searchable: false },
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'slug', name: 'slug' },
                { data: 'icon', name: 'icon' },
                { data: 'order', name: 'order' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
        );

        // Setup edit modal
        setupModal({
            buttonId: '#edit-button',
            modalId: '#edit-modal',
            fields: {
                id: button => button.data('id'),
                name_ar: button => button.data('name_ar'),
                name_en: button => button.data('name_en'),
                slug: button => button.data('slug'),
                icon: button => button.data('icon'),
                description_ar: button => button.data('description_ar'),
                description_en: button => button.data('description_en'),
                order: button => button.data('order'),
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

        let fields = ['name_ar', 'name_en', 'slug', 'icon', 'description_ar', 'description_en', 'order'];
        handleFormSubmit('#add-form', fields, '#add-modal', 'offcanvas', '#datatable');
        handleFormSubmit('#edit-form', fields, '#edit-modal', 'offcanvas', '#datatable');
        handleDeletionFormSubmit('#delete-form', '#delete-modal', '#datatable')
        handleDeletionFormSubmit('#delete-selected-form', '#delete-selected-modal', '#datatable')
    </script>
@endsection
