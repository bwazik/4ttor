<?php

return [
    # Datatable buttons
    'datatableTitle' => 'Manage :item',
    'export' => 'Export',
    'deleteSelected' => 'Delete Selected',
    'addItem' => 'Add :item',
    'datatable' => [
        'search' => 'Search:',
        'empty_table' => 'No data available in table',
        'zero_records' => 'No matching records found',
        'length_menu' => 'Show _MENU_ entries',
        'info' => 'Showing _START_ to _END_ of _TOTAL_ entries',
        'info_empty' => 'Showing 0 to 0 of 0 entries',
        'info_filtered' => '(filtered from _MAX_ total entries)',
    ],

    # Datatable headers
    'name' => 'Name',
    'status' => 'Status',
    'actions' => 'Actions',

    # Main inputs
    'name_ar' => 'Name (AR)',
    'name_en' => 'Name (EN)',
    'active' => 'Active',
    'inactive' => 'Inactive',
    'select_option' => 'Select an option from the list',

    # Modals
    'items' => 'Items Count',
    'editItem' => 'Edit :item',
    'deleteItem' => 'Delete :item',
    'delete_warning' => 'This will delete:',
    'confirm_deletion' => 'Are you sure you want to permanently delete these items?',
    'cannot_undo' => 'This action cannot be undone.',

    # Buttons
    'submit' => 'Submit',
    'cancel' => 'Cancel',
    'yes_delete' => 'Yes, Delete',

    # Toasts
    'errorMessage' => 'An unexpected error occurred. Please try again later!',
    'added' => ':item has been added successfully!',
    'edited' => ':item has been edited successfully!',
    'deleted' => ':item has been deleted successfully!',
    'deletedSelected' => 'Selected :item has been deleted successfully!',
    'noItemsSelected' => 'No items were selected!',
    'tooManyRequestsMessage' => 'You have exceeded the maximum number of requests. Please try again later!',
    'errorDependencies' => 'The following :model cannot be deleted because they have associated :dependency: :items',
];
