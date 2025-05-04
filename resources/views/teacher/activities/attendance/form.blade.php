<div class="col-12 mb-6">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>{{ trans('admin/attendance.studentsSearch') }}</h4>
            <div class="attendance-legend no-print">
                <div class="d-flex gap-4">
                    <div><span class="status-present rounded"></span> {{ trans('admin/attendance.present') }}</div>
                    <div><span class="status-absent rounded"></span> {{ trans('admin/attendance.absent') }}</div>
                    <div><span class="status-late rounded"></span> {{ trans('admin/attendance.late') }}</div>
                    <div><span class="status-excused rounded"></span> {{ trans('admin/attendance.excused') }}</div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form id="students-form" action="{{ route('teacher.attendance.students') }}" method="POST" autocomplete="off">
                @csrf
                <div class="row g-5">
                    <x-select-input context="modal" name="grade_id" label="{{ trans('main.grade') }}" :options="$grades" />
                    <x-select-input context="modal" name="group_id" label="{{ trans('main.group') }}"  />
                    <x-basic-input divContext="col-12" type="text" name="date" classes="flatpickr-date" label="{{ trans('main.date') }}" placeholder="YYYY-MM-DD" value="{{ now()->format('Y-m-d') }}"/>
                </div>
                <div class="pt-6">
                    <button type="submit" id="submit" class="btn btn-primary me-sm-2">{{ trans('main.search') }}</button>
                    <button type="button" id="mark-all" class="btn btn-success">{{ trans('admin/attendance.markAllPresent') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
