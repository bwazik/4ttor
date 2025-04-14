@extends('layouts.teacher.master')

@section('page-css')
    <style>
        .custom-option-header {
            flex-direction: column;
        }
    </style>
@endsection

@section('title', pageTitle('admin/questions.questions'))

@section('content')
    <div class="card">
        <div class="card-datatable table-responsive pt-0">
            <div class="card-header d-flex align-items-center justify-content-between flex-column flex-md-row border-bottom">
                <div class="head-label text-center">
                    <h5 class="card-title mb-0">{{ trans('main.datatableTitle', ['item' => trans('admin/questions.questions')]) }}</h5>
                </div>
                <div class="dt-action-buttons text-end pt-3 pt-md-0">
                    <div class="dt-buttons btn-group flex-wrap">
                        <button id="delete-selected-btn" class="btn btn-danger me-4 waves-effect waves-light" tabindex="0"
                            data-bs-target="#delete-selected-modal" data-bs-toggle="modal" data-bs-dismiss="modal">
                            <span>
                                <i class="ri-delete-bin-line ri-16px me-sm-2"></i>
                                <span class="d-none d-sm-inline-block">{{ trans('main.deleteSelected') }}</span>
                            </span>
                        </button>
                        <button id="add-button" class="btn btn-primary waves-effect waves-light" tabindex="0"
                            data-bs-toggle="offcanvas" data-bs-target="#add-modal">
                            <span>
                                <i class="ri-add-line ri-16px me-sm-2"></i>
                                <span class="d-none d-sm-inline-block">{{ trans('main.addItem', ['item' => trans('admin/questions.question')]) }}</span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body mt-4">
                <div class="form-check custom-option custom-option-basic mb-4">
                    <label class="form-check-label custom-option-content" for="select-all">
                        <input type="checkbox" id="select-all" class="form-check-input me-2">
                        <span class="custom-option-header">
                            <span class="h6 mb-0">{{ trans('main.selectAll') }}</span>
                        </span>
                    </label>
                </div>

                <div class="accordion accordion-popout accordion-header-primary" id="accordionPopout">
                    @forelse($questions as $index => $question)
                        <div class="accordion-item">
                            <h2 class="accordion-header d-flex align-items-center" id="headingPopout{{ $index }}">
                                <button type="button" class="accordion-button collapsed flex-grow-1 d-flex align-items-center text-start" data-bs-toggle="collapse"
                                    data-bs-target="#accordionPopout{{ $index }}" aria-expanded="false" aria-controls="accordionPopout{{ $index }}">
                                    <div class="d-flex align-items-center">
                                        <div class="dt-checkboxes-cell me-2">
                                            <input type="checkbox" value="{{ $question->id }}" class="dt-checkboxes form-check-input">
                                        </div>
                                        <div class="me-1 text-nowrap">{{ $index + 1 }} -</div>
                                    </div>

                                    <span class="flex-grow-1 text-break">{{ $question->question_text }}</span>
                                </button>

                                <div class="d-flex align-items-center ms-2">
                                    <button class="btn btn-sm btn-icon btn-text-secondary text-body rounded-pill waves-effect waves-light me-1"
                                        tabindex="0" type="button" data-bs-toggle="offcanvas" data-bs-target="#edit-modal"
                                        id="edit-button" data-id="{{ $question->id }}"
                                        data-question_text_ar="{{ $question->getTranslation('question_text', 'ar') }}"
                                        data-question_text_en="{{ $question->getTranslation('question_text', 'en') }}">
                                        <i class="ri-edit-box-line ri-20px"></i>
                                    </button>
                                    <button class="btn btn-sm btn-icon btn-text-danger rounded-pill text-body waves-effect waves-light me-1"
                                        id="delete-button" data-id="{{ $question->id }}"
                                        data-question_text_ar="{{ $question->getTranslation('question_text', 'ar') }}"
                                        data-question_text_en="{{ $question->getTranslation('question_text', 'en') }}"
                                        data-bs-target="#delete-modal" data-bs-toggle="modal" data-bs-dismiss="modal">
                                        <i class="ri-delete-bin-7-line ri-20px text-danger"></i>
                                    </button>
                                </div>
                            </h2>

                            <div id="accordionPopout{{ $index }}" class="accordion-collapse collapse"
                                aria-labelledby="headingPopout{{ $index }}" data-bs-parent="#accordionPopout" style="">
                                <div class="accordion-body">
                                    @include('teacher.activities.answers.datatable')
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center p-3 text-muted">
                            {{ trans('admin/questions.noQuestionsFound') }}
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    @include('teacher.activities.questions.modals')
    @include('teacher.activities.answers.modals')
@endsection

@section('page-js')
    <script>
        // Setup edit modal
        setupModal({
            buttonId: '#edit-button',
            modalId: '#edit-modal',
            fields: {
                id: button => button.data('id'),
                question_text_ar: button => button.data('question_text_ar'),
                question_text_en: button => button.data('question_text_en'),
            }
        });
        // Setup delete modal
        setupModal({
            buttonId: '#delete-button',
            modalId: '#delete-modal',
            fields: {
                id: button => button.data('id'),
                itemToDelete: button => `${button.data('question_text_ar')} - ${button.data('question_text_en')}`
            }
        });

        let fields = ['question_text_ar', 'question_text_en'];
        handleFormSubmit('#add-form', fields, '#add-modal', 'offcanvas', '#noDatatable');
        handleFormSubmit('#edit-form', fields, '#edit-modal', 'offcanvas', '#noDatatable');
        handleDeletionFormSubmit('#delete-form', '#delete-modal', '#noDatatable')
        handleDeletionFormSubmit('#delete-selected-form', '#delete-selected-modal', '#noDatatable')
        $(function() {
            $('body').on('click', '#delete-selected-btn', function(e) {
                e.preventDefault();

                $('#delete-selected-form #ids-container').empty();
                const selected = Array.from($(".dt-checkboxes-cell input[type=checkbox]:checked"))
                    .map(
                        checkbox => checkbox.value);

                if (selected.length > 0) {
                    $('#delete-selected-modal').modal('show');

                    selected.forEach(id => {
                        $('#delete-selected-form #ids-container').append(
                            `<input type="hidden" name="ids[]" value="${id}">`
                        );
                    });

                    $('input[id="itemToDelete"]').val(window.translations.items + ': ' + selected.length);
                } else {
                    $('#delete-selected-modal').modal('show');
                }
            });
        });
    </script>
    @include('teacher.activities.answers.scripts')
@endsection
