@extends('layouts.admin.master')

@section('page-css')
    <style>
        .custom-option.checked {
            --bs-custom-option-border-color: var(--bs-primary) !important;
            border: 1.5px solid var(--bs-custom-option-border-color) !important;
            margin: 0 !important;
        }

        .custom-option.checked-success {
            --bs-custom-option-border-color: var(--bs-success) !important;
            border: 1.5px solid var(--bs-custom-option-border-color) !important;
            margin: 0 !important;
        }

        .custom-option.checked-danger {
            --bs-custom-option-border-color: var(--bs-danger) !important;
            border: 1.5px solid var(--bs-custom-option-border-color) !important;
            margin: 0 !important;
        }
    </style>
@endsection

@section('title', pageTitle(trans('admin/quizzes.reviewAnswers') . ' - ' . $quiz->name))

@section('content')
    <div class="row g-6">
        <div class="col-lg-12">
            <div class="card mb-6">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">{{ $quiz->name }} - {{ $result->student->name }}</h5>
                    <button class="btn btn-sm btn-icon btn-text-danger rounded-pill waves-effect waves-light p-0"
                        id="delete-button"
                        data-student_name="{{ $result->student->name }}"
                        data-bs-target="#delete-modal" data-bs-toggle="modal" data-bs-dismiss="modal">
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
                                    data-bs-target="#answers-tab" aria-controls="answers-tab"
                                    aria-selected="false">{{ trans('admin/answers.answers') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#original-answers-tab" aria-controls="original-answers-tab"
                                    aria-selected="false">{{ trans('admin/quizzes.originalAnswers') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#violations-tab" aria-controls="violations-tab"
                                    aria-selected="false">{{ trans('admin/quizzes.studentViolations') }}</button>
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
                                        <div class="col-xl-2 col-md-4 col-sm-6">
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
                                        <div class="col-xl-2 col-md-4 col-sm-6">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="avatar" data-bs-toggle="tooltip"
                                                    data-bs-original-title="{{ trans('admin/quizzes.totalQuestions') }}">
                                                    <div class="avatar-initial bg-label-primary rounded">
                                                        <i class="icon-base ri ri-file-list-line icon-24px"></i>
                                                    </div>
                                                </div>
                                                <div class="card-info">
                                                    <h5 class="mb-0">{{ $quiz->questions_count }}</h5>
                                                    <p class="mb-0">{{ trans('admin/quizzes.totalQuestions') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-md-4 col-sm-6">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="avatar" data-bs-toggle="tooltip"
                                                    data-bs-original-title="{{ trans('admin/quizzes.correctAnswers') }}">
                                                    <div class="avatar-initial bg-label-success rounded">
                                                        <i class="icon-base ri ri-checkbox-circle-line icon-24px"></i>
                                                    </div>
                                                </div>
                                                <div class="card-info">
                                                    <h5 class="mb-0">{{ $reviewData['correctAnswers'] }}</h5>
                                                    <p class="mb-0">{{ trans('admin/quizzes.correctAnswers') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-md-4 col-sm-6">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="avatar" data-bs-toggle="tooltip"
                                                    data-bs-original-title="{{ trans('admin/quizzes.wrongAnswers') }}">
                                                    <div class="avatar-initial bg-label-danger rounded">
                                                        <i class="icon-base ri ri-close-circle-line icon-24px"></i>
                                                    </div>
                                                </div>
                                                <div class="card-info">
                                                    <h5 class="mb-0">{{ $reviewData['wrongAnswers'] }}</h5>
                                                    <p class="mb-0">{{ trans('admin/quizzes.wrongAnswers') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-md-4 col-sm-6">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="avatar" data-bs-toggle="tooltip"
                                                    data-bs-original-title="{{ trans('admin/quizzes.unanswered') }}">
                                                    <div class="avatar-initial bg-label-warning rounded">
                                                        <i class="icon-base ri ri-question-line icon-24px"></i>
                                                    </div>
                                                </div>
                                                <div class="card-info">
                                                    <h5 class="mb-0">{{ $reviewData['unanswered'] }}</h5>
                                                    <p class="mb-0">{{ trans('admin/quizzes.unanswered') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-md-4 col-sm-6">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="avatar" data-bs-toggle="tooltip"
                                                    data-bs-original-title="{{ trans('main.status') }}">
                                                    <div
                                                        class="avatar-initial rounded @if ($result->status == 2) bg-label-success @elseif($result->status == 3) bg-label-danger @else bg-label-warning @endif">
                                                        <i
                                                            class="icon-base ri @if ($result->status == 1) ri-time-line @elseif($result->status == 2) ri-checkbox-circle-line @elseif($result->status == 3) ri-close-circle-line @else ri-question-line @endif icon-24px"></i>
                                                    </div>
                                                </div>
                                                <div class="card-info">
                                                    <h5 class="mb-0">
                                                        @if ($result->status == 1)
                                                            {{ trans('admin/quizzes.inProgress') }}
                                                        @elseif($result->status == 2)
                                                            {{ trans('admin/quizzes.completed') }}
                                                        @elseif($result->status == 3)
                                                            {{ trans('admin/quizzes.failed') }}
                                                        @else
                                                            N/A
                                                        @endif
                                                    </h5>
                                                    <p class="mb-0">{{ trans('main.status') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h5 class="mt-4">{{ trans('main.score') }}: {{ round($result->total_score, 1) }}
                                    {{ trans('account.from') }}
                                    {{ $quiz->total_score }}</h5>
                                <div class="progress" style="height: 12px;">
                                    @php
                                        $percentage = $result->percentage;
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
                            <div class="tab-pane fade" id="answers-tab" role="tabpanel">
                                @if ($reviewData['studentOrderedQuestions']->isEmpty())
                                    <p class="text-center">{{ trans('main.errorMessage') }}</p>
                                @else
                                    <div class="accordion accordion-popout" id="accordionAnswers">
                                        @foreach ($reviewData['studentOrderedQuestions'] as $index => $question)
                                            <div class="accordion-item">
                                                <h2 class="accordion-header"
                                                    id="headingPopout{{ $question->question_id }}">
                                                    <button type="button"
                                                        class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#accordionAnswers{{ $question->question_id }}"
                                                        aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                                        aria-controls="accordionAnswers{{ $question->question_id }}">
                                                        {{ $index + 1 }} -
                                                        {{ $question->question->question_text ?? 'N/A' }}
                                                        @if ($question->answer_id)
                                                            @if ($question->question->answers->firstWhere('id', $question->answer_id)->is_correct)
                                                                <span
                                                                    class="badge badge-center rounded-pill bg-label-success ms-2"><i
                                                                        class="icon-base ri ri-check-line"></i></span>
                                                            @else
                                                                <span
                                                                    class="badge badge-center rounded-pill bg-label-danger ms-2"><i
                                                                        class="icon-base ri ri-close-line"></i></span>
                                                            @endif
                                                        @else
                                                            <span
                                                                class="badge rounded-pill bg-label-danger text-capitalized ms-2">{{ trans('admin/quizzes.unanswered') }}</span>
                                                        @endif
                                                        <span class="text-muted small ms-2">({{ $question->answered_at ? isoFormat($question->answered_at) : 'N/A' }})</span>
                                                    </button>
                                                </h2>
                                                <div id="accordionAnswers{{ $question->question_id }}"
                                                    class="accordion-collapse collapse"
                                                    aria-labelledby="headingPopout{{ $question->question_id }}"
                                                    data-bs-parent="#accordionAnswers">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            @foreach ($question->sorted_answers as $option)
                                                                <div class="col-md-12 mb-3">
                                                                    <div
                                                                        class="form-check custom-option custom-option-icon {{ $option->is_correct ? 'checked-success' : ($question->answer_id == $option->id && !$option->is_correct ? 'checked-danger' : '') }} {{ $question->answer_id == $option->id ? 'checked' : '' }}">
                                                                        <label
                                                                            class="form-check-label custom-option-content"
                                                                            for="answerRadioIcon{{ $option->id }}">
                                                                            <span class="custom-option-body">
                                                                                <small>{{ $option->answer_text }}</small>
                                                                                <small
                                                                                    class="text-secondary">({{ trans('main.score') }}:
                                                                                    {{ number_format($option->score) }})</small>
                                                                            </span>
                                                                            <input
                                                                                name="answerRadioIcon-{{ $question->question_id }}"
                                                                                class="form-check-input" type="radio"
                                                                                value="{{ $option->id }}"
                                                                                id="answerRadioIcon{{ $option->id }}"
                                                                                {{ $question->answer_id == $option->id ? 'checked' : '' }}
                                                                                disabled>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="tab-pane fade" id="original-answers-tab" role="tabpanel">
                                @if ($reviewData['normalOrderedQuestions']->isEmpty())
                                    <p class="text-center">{{ trans('main.errorMessage') }}</p>
                                @else
                                    <div class="accordion accordion-popout" id="accordionOriginalAnswers">
                                        @foreach ($reviewData['normalOrderedQuestions'] as $index => $question)
                                            <div class="accordion-item">
                                                <h2 class="accordion-header"
                                                    id="headingPopout{{ $question->question_id }}">
                                                    <button type="button"
                                                        class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#accordionOriginalAnswers{{ $question->id }}"
                                                        aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                                        aria-controls="accordionOriginalAnswers{{ $question->id }}">
                                                        {{ $index + 1 }} - {{ $question->question_text ?? 'N/A' }}
                                                        @if ($question->answer_id)
                                                            @if ($question->answers->firstWhere('id', $question->answer_id)->is_correct)
                                                                <span
                                                                    class="badge badge-center rounded-pill bg-label-success ms-2"><i
                                                                        class="icon-base ri ri-check-line"></i></span>
                                                            @else
                                                                <span
                                                                    class="badge badge-center rounded-pill bg-label-danger ms-2"><i
                                                                        class="icon-base ri ri-close-line"></i></span>
                                                            @endif
                                                        @else
                                                            <span
                                                                class="badge rounded-pill bg-label-danger text-capitalized ms-2">{{ trans('admin/quizzes.unanswered') }}</span>
                                                        @endif
                                                        <span class="text-muted small ms-2">({{ $question->answered_at ? isoFormat($question->answered_at) : 'N/A' }})</span>
                                                    </button>
                                                </h2>
                                                <div id="accordionOriginalAnswers{{ $question->id }}"
                                                    class="accordion-collapse collapse"
                                                    aria-labelledby="headingPopout{{ $question->id }}"
                                                    data-bs-parent="#accordionOriginalAnswers">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            @foreach ($question->sorted_answers as $option)
                                                                <div class="col-md-12 mb-3">
                                                                    <div
                                                                        class="form-check custom-option custom-option-icon {{ $option->is_correct ? 'checked-success' : ($question->answer_id == $option->id && !$option->is_correct ? 'checked-danger' : '') }} {{ $question->answer_id == $option->id ? 'checked' : '' }}">
                                                                        <label
                                                                            class="form-check-label custom-option-content"
                                                                            for="originalAnswersRadioIcon{{ $option->id }}">
                                                                            <span class="custom-option-body">
                                                                                <small>{{ $option->answer_text }}</small>
                                                                                <small
                                                                                    class="text-secondary">({{ trans('main.score') }}:
                                                                                    {{ number_format($option->score) }})</small>
                                                                            </span>
                                                                            <input
                                                                                name="originalAnswersRadioIcon-{{ $question->id }}"
                                                                                class="form-check-input" type="radio"
                                                                                value="{{ $option->id }}"
                                                                                id="originalAnswersRadioIcon{{ $option->id }}"
                                                                                {{ $question->answer_id == $option->id ? 'checked' : '' }}
                                                                                disabled>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="tab-pane fade" id="violations-tab" role="tabpanel">
                                <div class="table-responsive text-nowrap text-center">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>{{ trans('admin/quizzes.violationType') }}</th>
                                                <th>{{ trans('admin/quizzes.detectedAt') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-border-bottom-0">
                                            @forelse ($violations as $violation)
                                                <tr>
                                                    <td>{{ trans('admin/quizzes.violationTypes.' . $violation->violation_type) ?? 'N/A' }}
                                                    </td>
                                                    <td>({{ $violation->detected_at ? isoFormat($violation->detected_at) : 'N/A' }})</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="2">{{ trans('main.datatable.empty_table') }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="details-tab" role="tabpanel">
                                <ul class="timeline card-timeline mb-0 mt-5">
                                    <li class="timeline-item timeline-item-transparent">
                                        <span class="timeline-point timeline-point-success"></span>
                                        <div class="timeline-event">
                                            <div class="timeline-header mb-3">
                                                <h6 class="mb-0">{{ trans('main.start_time') }}</h6>
                                            </div>
                                            <p class="mb-2">{{ $result->started_at ? isoFormat($result->started_at) : 'N/A' }}</p>
                                        </div>
                                    </li>
                                    <li class="timeline-item timeline-item-transparent">
                                        <span class="timeline-point timeline-point-warning"></span>
                                        <div class="timeline-event">
                                            <div class="timeline-header mb-3">
                                                <h6 class="mb-0">{{ trans('admin/quizzes.lastOrder') }}</h6>
                                            </div>
                                            <p class="mb-2">
                                                {{ $details['lastOrderedQuestion']?->question_text ?? 'N/A' }}</p>
                                        </div>
                                    </li>
                                    <li class="timeline-item timeline-item-transparent">
                                        <span class="timeline-point timeline-point-danger"></span>
                                        <div class="timeline-event">
                                            <div class="timeline-header mb-3">
                                                <h6 class="mb-0">{{ trans('main.end_time') }}</h6>
                                            </div>
                                            <p class="mb-2">{{ $result->completed_at ? isoFormat($result->completed_at) : 'N/A' }}</p>
                                        </div>
                                    </li>
                                    <li class="timeline-item timeline-item-transparent">
                                        <span class="timeline-point timeline-point-info"></span>
                                        <div class="timeline-event">
                                            <div class="timeline-header mb-3">
                                                <h6 class="mb-0">{{ trans('admin/quizzes.avgTimePerQuestion') }}</h6>
                                            </div>
                                            <p class="mb-2">
                                                {{ $details['avgTimePerQuestion'] ? $details['avgTimePerQuestion'] . ' ' . trans('admin/zooms.minutes') : 'N/A' }}
                                            </p>
                                        </div>
                                    </li>
                                    <li class="timeline-item timeline-item-transparent">
                                        <span class="timeline-point timeline-point-info"></span>
                                        <div class="timeline-event">
                                            <div class="timeline-header mb-3">
                                                <h6 class="mb-0">{{ trans('admin/quizzes.totalTimeTaken') }}</h6>
                                            </div>
                                            <p class="mb-2">
                                                {{ $details['totalTimeTaken'] ? $details['totalTimeTaken'] . ' ' . trans('admin/zooms.minutes') : 'N/A' }}
                                            </p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <x-modal modalType="delete" modalTitle="{{ trans('admin/quizzes.resetStudentQuiz') }}"
        action="{{ route('admin.quizzes.resetStudentQuiz', ['id' => $quiz->id, 'studentId' => $result->student->id]) }}" submitColor="danger" submitButton="{{ trans('main.submit') }}">
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

        handleDeletionFormSubmit('#delete-form', '#delete-modal', '#datatable', '{{ route("admin.quizzes.reports", $quiz->id) }}')
    </script>
@endsection
