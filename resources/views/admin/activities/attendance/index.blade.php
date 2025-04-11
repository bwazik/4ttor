@extends('layouts.admin.master')

@section('page-css')
    <style>
        .attendance-legend span {
            display: inline-block;
            width: 20px;
            height: 20px;
            margin-right: 5px;
            vertical-align: middle;
            border-radius: 4px;
            opacity: 0.7;
        }

        .status-present {
            background-color: #bdf5a1;
        }

        .status-absent {
            background-color: #ffb5b2;
        }

        .status-late {
            background-color: #ffde96;
        }

        .status-excused {
            background-color: #91e4ff;
        }

        .status-container {
            display: flex;
            justify-content: center;
            gap: 0.4rem;
        }

        .status-btn {
            min-width: 45px;
            padding: 0.4rem 0.7rem;
            transition: all 0.2s ease-in-out;
            border: 2px solid transparent;
            font-weight: 500;
            color: black;
            background-color: white;
        }

        .status-btn[data-status="1"] {
            border-color: #28a745;
            color: #28a745;
        }

        .status-btn[data-status="2"] {
            border-color: #dc3545;
            color: #dc3545;
        }

        .status-btn[data-status="3"] {
            border-color: #ffc107;
            color: #ffc107;
        }

        .status-btn[data-status="4"] {
            border-color: #17a2b8;
            color: #17a2b8;
        }

        .status-btn.active {
            font-weight: bold;
            color: white !important;
            border-color: transparent !important;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            transform: scale(1.05);
        }

        .status-btn.active[data-status="1"] {
            background-color: #28a745;
        }

        .status-btn.active[data-status="2"] {
            background-color: #dc3545;
        }

        .status-btn.active[data-status="3"] {
            background-color: #ffc107;
        }

        .status-btn.active[data-status="4"] {
            background-color: #17a2b8;
        }
    </style>
@endsection

@section('title', pageTitle('admin/attendance.attendance'))

@section('content')
    @include('admin.activities.attendance.form')
    <!-- DataTable with Buttons -->
    <x-datatable datatableTitle="{{ trans('main.datatableTitle', ['item' => trans('admin/attendance.attendance')]) }}"
        dataToggle="offcanvas" otherButton="{{ trans('admin/attendance.submit') }}" otherIcon="ri-add-line">
        <th></th>
        <th>#</th>
        <th>{{ trans('main.student') }}</th>
        <th>{{ trans('main.description') }}</th>
        <th>{{ trans('main.actions') }}</th>
    </x-datatable>
    <!--/ DataTable with Buttons -->
@endsection

@section('page-js')
    <script>
        $(document).ready(function() {
            let dataTable = null;
            const form = $('#students-form');
            const formId = '#students-form';
            const url = form.attr('action');

            form.on('submit', function(e) {
                e.preventDefault();
                submitButton = $(this).find('button[type="submit"]');
                submitButton.prop('disabled', true);

                const fields = ['teacher_id', 'grade_id', 'group_id', 'date'];
                // Clear previous error states
                $.each(fields, function(_, field) {
                    $(formId + ' #' + field).removeClass('is-invalid');
                    $(formId + ' #' + field + '_error').text('').addClass('d-none').removeClass(
                        'd-block');
                });

                const formData = {
                    teacher_id: $(formId + ' #teacher_id').val(),
                    grade_id: $(formId + ' #grade_id').val(),
                    group_id: $(formId + ' #group_id').val(),
                    date: $(formId + ' #date').val()
                };

                if (!formData.teacher_id || !formData.grade_id || !formData.group_id || !formData.date) {
                    toastr.error('Please select a teacher, grade, group, and date');
                    setTimeout(function() {
                        submitButton.prop('disabled', false);
                    }, 1500);
                    return;
                }

                if (dataTable) {
                    dataTable.destroy();
                }

                datatable = initializePostDataTable('#datatable', url, [2, 3, 4],
                    [{
                            data: "",
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
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'note',
                            name: 'note'
                        },
                        {
                            data: 'actions',
                            name: 'actions',
                            orderable: false,
                            searchable: false
                        }
                    ], {
                        teacher_id: $('#students-form #teacher_id').val(),
                        grade_id: $('#students-form #grade_id').val(),
                        group_id: $('#students-form #group_id').val(),
                        date: $('#students-form #date').val(),
                    },
                    '#students-form'
                );
            });
        });

        function gatherAttendanceData() {
            let attendanceData = [];

            $('#datatable tbody tr').each(function() {
                let $row = $(this);

                let studentId = $row.find('.status-container').data('student-id');
                let note = $row.find('.note-input').val();
                let activeButton = $row.find('.status-container .status-btn.active');
                let status = activeButton.length > 0 ? activeButton.data('status') : null;

                if (studentId) {
                    attendanceData.push({
                        student_id: studentId,
                        status: status,
                        note: note || null
                    });
                }
            });

            return attendanceData;
        }

        $(document).ready(function() {
            $('#other-button').on('click', function() {
                let submitButton = $(this);
                submitButton.prop('disabled', true);

                let form = $('#students-form');
                let data = gatherAttendanceData();

                let payload = {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    teacher_id: form.find('#teacher_id').val(),
                    grade_id: form.find('#grade_id').val(),
                    group_id: form.find('#group_id').val(),
                    date: form.find('#date').val(),
                    attendance: data
                };

                $.ajax({
                    url: "{{ route('admin.attendance.insert') }}",
                    type: 'POST',
                    dataType: "json",
                    contentType: "application/json",
                    data: JSON.stringify(payload),
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.success)
                            setTimeout(function() {
                                submitButton.prop('disabled', false);
                            }, 1500);
                            refreshDataTable("#datatable");
                        } else {
                            toastr.error(response.error || errorMessage);
                            setTimeout(function() {
                                submitButton.prop('disabled', false);
                            }, 1500);
                        }
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 429) {
                            toastr.error(tooManyRequestsMessage);
                        } else if (xhr.responseJSON) {
                            if (xhr.responseJSON.errors) {
                                $.each(xhr.responseJSON.errors, function(key, val) {
                                    toastr.error(val[0]);
                                });
                            } else if (xhr.responseJSON.error) {
                                toastr.error(xhr.responseJSON.error);
                            } else {
                                toastr.error(errorMessage);
                            }
                        } else {
                            toastr.error(errorMessage);
                        }

                        setTimeout(function() {
                            submitButton.prop('disabled', false);
                        }, 1500);
                    },
                });
            });
        });

        $(document).on('click', '.status-btn', function() {
            let $button = $(this);
            let $statusContainer = $button.closest('.status-container');

            $statusContainer.find('.status-btn').removeClass('active')
                // .css('background-color', 'white')
                .css('color', function() {
                    return $(this).css('border-color');
                });

            $button.addClass('active')
                // .css('background-color', $button.css('border-color'))
                .css('color', 'white');

            checkAllStatusSelected();
        });

        document.getElementById('date').addEventListener('change', function () {
            let selectedDate = this.value;
            let today = "{{ now()->toDateString() }}";

            let submitButton = document.getElementById('other-button');

            if (selectedDate !== today) {
                submitButton.disabled = true;
            } else {
                submitButton.disabled = false;
            }
        });

        $('#mark-all').on('click', function () {
            let allMarked = true;

            $('.status-container').each(function () {
                const activeStatus = $(this).find('.status-btn.active').data('status');
                if (activeStatus !== 1) {
                    allMarked = false;
                    return false;
                }
            });

            if (allMarked) {
                $('.status-btn').removeClass('active')
                    .css('color', function () {
                        return $(this).css('border-color');
                    });
            } else {
                $('.status-container').each(function () {
                    const $presentBtn = $(this).find('.status-btn[data-status="1"]');
                    $presentBtn.click();
                });
            }
        });

        function checkAllStatusSelected() {
            let allSelected = true;
            $('.status-container').each(function () {
                if (!$(this).find('.active').length) {
                    allSelected = false;
                }
            });

            $('#other-button').prop('disabled', !allSelected);
        }

        // Setup students form
        initializeSelect2('students-form', 'grade_id');
        initializeSelect2('students-form', 'teacher_id');
        initializeSelect2('students-form', 'group_id');
        initializeSelect2('select2-primary', 'status_1');
        fetchMultipleDataByAjax('#students-form #teacher_id', "{{ route('admin.teachers.grades', '__ID__') }}",
            '#students-form #grade_id', 'teacher_id', 'GET');
        fetchMultipleDataByAjax('#students-form #grade_id',
            "{{ route('admin.fetch.teachers.grade.groups', ['__SECOND_ID__', '__ID__']) }}", '#students-form #group_id',
            'grade_id', 'GET');
    </script>
@endsection
