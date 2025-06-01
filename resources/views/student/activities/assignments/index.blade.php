@extends('layouts.student.master')

@section('page-css')

@endsection

@section('title', pageTitle('admin/assignments.assignments'))

@section('content')
    <x-datatable datatableTitle="{{ trans('main.datatableTitle', ['item' => trans('admin/assignments.assignments')]) }}">
        <th></th>
        <th>#</th>
        <th>{{ trans('main.title') }}</th>
        <th>{{ trans('main.teacher') }}</th>
        <th>{{ trans('main.deadline') }}</th>
        <th>{{ trans('main.score') }}</th>
        <th>{{ trans('main.actions') }}</th>
    </x-datatable>
@endsection

@section('page-js')
    <script>
        initializeDataTable('#datatable', "{{ route('student.assignments.index') }}", [2, 3, 4, 5, 6],
            [
                { data: "", orderable: false, searchable: false },
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'title', name: 'title' },
                { data: 'teacher_id', name: 'teacher_id' },
                { data: 'deadline', name: 'deadline', orderable: false, searchable: false },
                { data: 'score', name: 'score' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
        );

        @if(session('error'))
            toastr.error("{{ session('error') }}");
        @endif
    </script>
@endsection
