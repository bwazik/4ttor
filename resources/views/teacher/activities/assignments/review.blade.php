@extends('layouts.teacher.master')

@section('page-css')

@endsection

@section('title', pageTitle(trans('admin/assignments.reviewAssignment') . ' - ' . $submission->assignment->title))

@section('content')
    <div class="row g-6">
        <div class="col-lg-12">
            <div class="card mb-6">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">{{ $submission->assignment->title }} - {{ $student->name }}</h5>
                    <button class="btn btn-sm btn-icon btn-text-danger rounded-pill waves-effect waves-light p-0"
                        id="delete-button" data-student_name="{{ $student->name }}" data-bs-target="#delete-modal"
                        data-bs-toggle="modal" data-bs-dismiss="modal">
                        <i class="ri-delete-bin-7-line ri-20px text-danger"></i>
                    </button>
                </div>
                <div class="card-body pt-1">
                    <div class="nav-align-top nav-tabs-shadow">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link active waves-effect" role="tab"
                                    data-bs-toggle="tab" data-bs-target="#result-tab" aria-controls="result-tab"
                                    aria-selected="true">{{ trans('admin/quizzes.result') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#files-tab" aria-controls="files-tab"
                                    aria-selected="false">{{ trans('admin/assignments.files') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#details-tab" aria-controls="details-tab"
                                    aria-selected="false">{{ trans('admin/quizzes.anotherDetails') }}</button>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade active show" id="result-tab" role="tabpanel">
                                <div class="card-body">
                                    <div class="row g-4">
                                        <div class="col-md-4 col-sm-6">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="avatar" data-bs-toggle="tooltip"
                                                    data-bs-original-title="{{ trans('admin/quizzes.rank') }}">
                                                    <div class="avatar-initial bg-label-info rounded">
                                                        <i class="icon-base ri ri-trophy-line icon-24px"></i>
                                                    </div>
                                                </div>
                                                <div class="card-info">
                                                    <h5 class="mb-0">{{ $reviewData['formattedRank'] }}</h5>
                                                    <p class="mb-0">{{ trans('admin/quizzes.rank') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="avatar" data-bs-toggle="tooltip"
                                                    data-bs-original-title="{{ trans('admin/assignments.filesCount') }}">
                                                    <div class="avatar-initial bg-label-primary rounded">
                                                        <i class="icon-base ri ri-file-line icon-24px"></i>
                                                    </div>
                                                </div>
                                                <div class="card-info">
                                                    <h5 class="mb-0">{{ $reviewData['totalFiles'] }}</h5>
                                                    <p class="mb-0">{{ trans('admin/assignments.filesCount') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="avatar" data-bs-toggle="tooltip"
                                                    data-bs-original-title="{{ trans('admin/assignments.totalFileSize') }}">
                                                    <div class="avatar-initial bg-label-primary rounded">
                                                        <i class="icon-base ri ri-database-line icon-24px"></i>
                                                    </div>
                                                </div>
                                                <div class="card-info">
                                                    <h5 class="mb-0">{{ number_format($reviewData['totalFileSize'], 2) }}
                                                        MB</h5>
                                                    <p class="mb-0">{{ trans('admin/assignments.totalFileSize') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h5 class="mt-4">{{ trans('main.score') }}:
                                    {{ $submission->score ? round($submission->score, 1) : 'N/A' }}
                                    {{ trans('account.from') }}
                                    {{ $submission->assignment->score }}</h5>
                                <div class="progress" style="height: 12px;">
                                    @php
                                        $percentage =
                                            $submission->score && $submission->assignment->score
                                                ? ($submission->score / $submission->assignment->score) * 100
                                                : 0;
                                        $progressClass =
                                            $percentage < 50
                                                ? 'bg-danger'
                                                : ($percentage <= 75
                                                    ? 'bg-warning'
                                                    : 'bg-success');
                                    @endphp
                                    <div class="progress-bar {{ $progressClass }}" role="progressbar"
                                        style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}"
                                        aria-valuemin="0" aria-valuemax="100">
                                        {{ round($percentage, 1) }}%
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="files-tab" role="tabpanel">
                                <div class="accordion stick-top accordion-custom-button mb-5" id="assignmentDetails">
                                    <div class="accordion-item active mb-0">
                                        <div class="accordion-header border-bottom-0" id="headingOne">
                                            <button type="button" class="accordion-button" data-bs-toggle="collapse"
                                                data-bs-target="#assignmentfiles" aria-expanded="true"
                                                aria-controls="assignmentfiles">
                                                <span class="d-flex flex-column">
                                                    <span
                                                        class="h5 mb-0">{{ trans('admin/assignments.showFiles') }}</span>
                                                </span>
                                            </button>
                                        </div>
                                        <div id="assignmentfiles" class="accordion-collapse collapse show"
                                            data-bs-parent="#assignmentDetails">
                                            <div class="accordion-body py-4 border-top">
                                                @forelse($reviewData['files'] as $file)
                                                    <div class="d-flex align-items-center mb-4">
                                                        <div class="me-2">
                                                            @php
                                                                $extension = strtolower(
                                                                    pathinfo($file->file_name, PATHINFO_EXTENSION),
                                                                );
                                                                $imageSrc = match ($extension) {
                                                                    'pdf' => asset('assets/img/icons/misc/pdf.svg'),
                                                                    'jpg', 'jpeg' => asset(
                                                                        'assets/img/icons/misc/jpg.svg',
                                                                    ),
                                                                    'png' => asset('assets/img/icons/misc/png.svg'),
                                                                    'doc', 'docx' => asset(
                                                                        'assets/img/icons/misc/docx.svg',
                                                                    ),
                                                                    'xls', 'xlsx' => asset(
                                                                        'assets/img/icons/misc/xlsx.svg',
                                                                    ),
                                                                    'txt' => asset('assets/img/icons/misc/txt.svg'),
                                                                    default => asset('assets/img/icons/misc/file.png'),
                                                                };
                                                            @endphp
                                                            <img src="{{ $imageSrc }}"
                                                                alt="{{ $extension }} icon"
                                                                style="width: 32px; height: 32px;" />
                                                        </div>
                                                        <span class="text-nowrap overflow-hidden text-truncate"
                                                            style="max-width: 250px;" data-bs-toggle="tooltip"
                                                            data-bs-original-title="{{ $file->file_name }}">
                                                            <a target="_blank"
                                                                href="{{ route('teacher.assignments.submissions.download', $file->id) }}"
                                                                class="text-decoration-none">
                                                                {{ $file->file_name }}
                                                            </a>
                                                            <small class="text-body d-block">
                                                                {{ $file->file_size >= 1024 * 1024 ? number_format($file->file_size / (1024 * 1024), 2) . ' MB' : number_format($file->file_size / 1024, 2) . ' KB' }}
                                                                <small
                                                                    class="text-muted">({{ $file->created_at ? isoFormat($file->created_at) : 'N/A' }})</small>
                                                            </small>
                                                        </span>
                                                        <div class="ms-auto">
                                                            <a href="{{ route('teacher.assignments.submissions.view', $file->id) }}"
                                                                target="_blank"
                                                                class="btn btn-sm btn-icon btn-text-primary rounded-pill text-body waves-effect waves-light">
                                                                <i class="ri-eye-line ri-20px text-primary"></i>
                                                            </a>
                                                            <a href="{{ route('teacher.assignments.submissions.download', $file->id) }}"
                                                                class="btn btn-sm btn-icon btn-text-info rounded-pill text-body waves-effect waves-light">
                                                                <i class="ri-download-line ri-20px text-info"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <p class="text-muted">{{ trans('admin/resources.no_files_uploaded') }}
                                                    </p>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h5>{{ trans('admin/assignments.editScore') }}</h5>
                                <form id="feedback-form"
                                    action="{{ route('teacher.assignments.feedback', ['uuid' => $assignment->uuid, 'studentUuid' => $student->uuid]) }}"
                                    method="POST" autocomplete="off">
                                    @csrf
                                    <div class="mb-6">
                                        <div class="row gx-5">
                                            <x-basic-input context="offcanvas" type="number" name="score"
                                                label="{{ trans('main.score') }}" placeholder="0"
                                                value="{{ $submission->score }}" required />
                                            <x-text-area context="offcanvas" name="feedback"
                                                label="{{ trans('main.description') }}"
                                                placeholder="{{ trans('admin/assignments.placeholders.feedback') }}"
                                                value="{{ $submission->feedback }}" maxlength="500" />
                                        </div>
                                        <div>
                                            <button type="submit"
                                                class="btn btn-primary me-2 waves-effect waves-light">{{ trans('main.submit') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="details-tab" role="tabpanel">
                                <ul class="timeline card-timeline mb-0 mt-5">
                                    <li class="timeline-item timeline-item-transparent">
                                        <span class="timeline-point timeline-point-success"></span>
                                        <div class="timeline-event">
                                            <div class="timeline-header mb-3">
                                                <h6 class="mb-0">{{ trans('admin/assignments.submittedAt') }}</h6>
                                            </div>
                                            <p class="mb-2">
                                                {{ $submission->submitted_at ? isoFormat($submission->submitted_at) : 'N/A' }}
                                            </p>
                                        </div>
                                    </li>
                                    @if ($submission->score !== null)
                                        <li class="timeline-item timeline-item-transparent">
                                            <span class="timeline-point timeline-point-warning"></span>
                                            <div class="timeline-event">
                                                <div class="timeline-header mb-3">
                                                    <h6 class="mb-0">
                                                        {{ trans('admin/assignments.editScore') }}
                                                    </h6>
                                                </div>
                                                <p class="mb-2">
                                                    {{ $submission->updated_at ? isoFormat($submission->updated_at) : 'N/A' }}
                                                </p>
                                            </div>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <x-modal modalType="delete" modalTitle="{{ trans('admin/assignments.resetStudentAssignment') }}"
        action="{{ route('teacher.assignments.resetStudentAssignment', ['uuid' => $assignment->uuid, 'studentUuid' => $student->uuid]) }}" submitColor="danger" submitButton="{{ trans('main.submit') }}">
        @include('partials.delete-modal-body')
    </x-modal>
@endsection

@section('page-js')
    <script>
        // Setup delete modal
        setupModal({
            buttonId: '#delete-button',
            modalId: '#delete-modal',
            fields: {
                itemToDelete: button => `${button.data('student_name')}`
            }
        });

        handleFormSubmit('#feedback-form', ['score', 'feedback']);
        handleDeletionFormSubmit('#delete-form', '#delete-modal', '#datatable', '{{ route("teacher.assignments.reports", $assignment->uuid) }}')
    </script>
@endsection
