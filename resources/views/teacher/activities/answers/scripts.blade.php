<script>
    function getTableId(questionId) {
        return `#datatable${questionId}`;
    }

    function getRoute(route, questionId) {
        return route.replace('questionId', questionId);
    }

    $(document).ready(function() {
        $('.accordion-button').on('click', function() {
            const accordionItem = $(this).closest('.accordion-item');
            const questionId = accordionItem.find('.dt-checkboxes').val();
            const tableId = getTableId(questionId);
            const accordionBody = accordionItem.find('.accordion-collapse');

            if (!accordionBody.hasClass('show') && !$.fn.DataTable.isDataTable(tableId)) {
                initializeDataTable(tableId, "{{ route('admin.answers.index', 'questionId') }}".replace(
                        'questionId', questionId),
                    [2, 3, 4, 5],
                    [
                        { data: "", orderable: false, searchable: false },
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'answer_text', name: 'answer_text' },
                        { data: 'is_correct', name: 'is_correct' },
                        { data: 'score', name: 'score' },
                        { data: 'actions', name: 'actions', orderable: false, searchable: false }
                    ]);

                    accordionItem.find('#add-answer-button').attr('data-question_id', questionId);
                }
        });

        $(document).on('click', '#add-answer-button', function() {
            const questionId = $(this).data('question_id');
            const formAction = "{{ route('admin.answers.insert', 'questionId') }}".replace('questionId', questionId);
            $('#add-answer-form').attr('action', formAction);
            $('#add-answer-form').attr('data-question_id', questionId);
        });
        $(document).on('click', '[data-bs-target="#edit-answer-modal"], [data-bs-target="#delete-answer-modal"]', function() {
            const questionId = $(this).data('question_id');
            const targetForm = $(this).data('bs-target') === "#edit-answer-modal" ? $("#edit-answer-form") : $("#delete-answer-form");
            targetForm.attr('data-question_id', questionId);
        });

        // Setup add modal
        setupModal({
            buttonId: '#add-answer-button',
            modalId: '#add-answer-modal',
            fields: {
                is_correct: () => '',
            }
        });
        // Setup edit modal
        setupModal({
            buttonId: '#edit-answer-button',
            modalId: '#edit-answer-modal',
            fields: {
                id: button => button.data('id'),
                answer_text_ar: button => button.data('answer_text_ar'),
                answer_text_en: button => button.data('answer_text_en'),
                is_correct: button => button.data('is_correct'),
                score: button => button.data('score'),
            }
        });
        // Setup delete modal
        setupModal({
            buttonId: '#delete-answer-button',
            modalId: '#delete-answer-modal',
            fields: {
                id: button => button.data('id'),
                itemToDelete: button => `${button.data('answer_text_ar')} - ${button.data('answer_text_en')}`
            }
        });


        let answerFields = ['answer_text_ar', 'answer_text_en', 'is_correct', 'score'];
        handleFormSubmit('#add-answer-form', answerFields, '#add-answer-modal', 'offcanvas',
            () => `#datatable${$('#add-answer-form').attr('data-question_id')}`);

        handleFormSubmit('#edit-answer-form', answerFields, '#edit-answer-modal', 'offcanvas',
            () => `#datatable${$('#edit-answer-form').attr('data-question_id')}`);

        handleDeletionFormSubmit('#delete-answer-form', '#delete-answer-modal',
            () => `#datatable${$('#delete-answer-form').attr('data-question_id')}`);
    });
</script>
