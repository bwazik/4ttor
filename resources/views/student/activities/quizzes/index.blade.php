@extends('layouts.student.master')

@section('page-css')

@endsection

@section('title', pageTitle('admin/quizzes.quizzes'))

@section('content')
    <!-- DataTable -->
    <x-datatable datatableTitle="{{ trans('main.datatableTitle', ['item' => trans('admin/quizzes.quizzes')]) }}">
        <th></th>
        <th>#</th>
        <th>{{ trans('main.title') }}</th>
        <th>{{ trans('main.teacher') }}</th>
        <th>{{ trans('main.duration') }}</th>
        <th>{{ trans('main.start_time') }}</th>
        <th>{{ trans('main.end_time') }}</th>
        <th>{{ trans('main.link') }}</th>
    </x-datatable>
    <!--/ DataTable -->
@endsection

@section('page-js')
    <script>
        initializeDataTable('#datatable', "{{ route('student.quizzes.index') }}", [2, 3, 4, 5, 6, 7],
            [
                { data: "", orderable: false, searchable: false },
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'teacher_id', name: 'teacher_id' },
                { data: 'duration', name: 'duration', orderable: false, searchable: false },
                { data: 'start_time', name: 'start_time', orderable: false, searchable: false },
                { data: 'end_time', name: 'end_time', orderable: false, searchable: false },
                { data: 'startQuiz', name: 'startQuiz', orderable: false, searchable: false },
            ],
        );

        @if(session('error'))
            toastr.error("{{ session('error') }}");
        @endif
        @if(session('success'))
            toastr.success("{{ session('success') }}");
        @endif
    </script>
@endsection
