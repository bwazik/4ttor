function handleQuizSubmit(formId) {
    const $form = $(formId);
    const $submitButton = $form.find('button[type="submit"]');
    const originalButtonContent = $submitButton.html();

    $(formId).on("submit", function (e) {
        e.preventDefault();

        $submitButton.find(".waves-ripple").remove();
        $submitButton.prop("disabled", true);
        $submitButton.html(
            `<i class="ri-loader-4-line ri-spin ri-20px me-1"></i> ${window.translations.processing}...`
        );

        let nextOrder = parseInt($("#current_order").val()) + 1;
        let nextUrl =
            '{{ route("student.quizzes.take", [$quiz->uuid, ":order"]) }}'.replace(
                ":order",
                nextOrder
            );

        if (!$('input[name="answer_id"]:checked').length) {
            showAlert().then((result) => {
                if (result.isConfirmed) {
                    loadQuestion(nextUrl, nextOrder);
                }
            });
            return;
        }

        const formData = new FormData(this);

        $.ajax({
            url: $(this).attr("action"),
            type: $(this).attr("method"),
            dataType: "json",
            processData: false,
            contentType: false,
            data: formData,
            success: function (response) {
                if (response.success) {
                    if (response.is_last) {
                        toastr.success(response.success);
                        setTimeout(() => {
                            window.location.href =
                                '{{ route("student.quizzes.index") }}';
                        }, 1500);
                    } else {
                        let nextUrl =
                            "{{ route('student.quizzes.take', [$quiz->uuid, ':order']) }}".replace(
                                ":order",
                                response.next_order
                            );
                        loadQuestion(nextUrl, response.next_order);
                    }
                } else {
                    toastr.error(response.error || errorMessage);
                    resetButtonState($submitButton, originalButtonContent);
                }
            },
            error: function (xhr, status, error) {
                if (xhr.status === 429) {
                    toastr.error(tooManyRequestsMessage);
                } else if (xhr.responseJSON) {
                    if (xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function (key, val) {
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

                resetButtonState($submitButton, originalButtonContent);
            },
            complete: function () {
                resetButtonState($submitButton, originalButtonContent);
            },
        });
    });
}

function loadQuestion(url, order) {
    const $submitButton = $("#quiz-form").find('button[type="submit"]');
    const originalButtonContent = $submitButton.html();

    $submitButton.find(".waves-ripple").remove();
    $submitButton.prop("disabled", true);
    $submitButton.html(
        `<i class="ri-loader-4-line ri-spin ri-20px me-1"></i> ${window.translations.processing}...`
    );

    $.ajax({
        url: url,
        method: "GET",
        headers: {
            Accept: "application/json",
        },
        success: function (data) {
            console.log(data);
            if (data.success) {
                updateQuestion(data);
            } else if (data.status === "success") {
                alert(data.message || "Quiz completed!");
            } else {
                alert(data.message || "Unable to load question.");
            }
        },
        error: function (xhr) {
            if (xhr.status === 429) {
                toastr.error(tooManyRequestsMessage);
            } else if (xhr.responseJSON) {
                if (xhr.responseJSON.errors) {
                    $.each(xhr.responseJSON.errors, function (key, val) {
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

            resetButtonState($submitButton, originalButtonContent);
        },
        complete: function () {
            resetButtonState($submitButton, originalButtonContent);
        },
    });
}

function updateQuestion(data) {
    // Update question
    $("#question_id").val(data.question.id);
    $("#current_order").val(data.current_order);
    $("#question_text").text(data.question.text);
    $("#question_title").text(
        `{{ trans('admin/questions.question') }} ${data.current_order} {{ trans('admin/quizzes.of') }} ${data.quiz.questions_count}`
    );

    // Update answers
    let answersHtml = "";
    data.answers.forEach(function (answer) {
        answersHtml += `
            <div class="col-md-12 mb-3">
                <div class="form-check custom-option custom-option-icon ${
                    answer.checked ? "checked" : ""
                }">
                    <label class="form-check-label custom-option-content" for="answer-${
                        answer.id
                    }">
                        <span class="custom-option-body">${answer.text}</span>
                        <input name="answer_id" class="form-check-input" type="radio"
                               value="${answer.id}" id="answer-${answer.id}"
                               ${answer.checked ? "checked" : ""}>
                    </label>
                </div>
            </div>
        `;
    });
    $("#answer-options").html(answersHtml);

    // Update navigator
    $(".question-nav").each(function () {
        let $this = $(this);
        let order = $this.data("order");
        let questionId = $this.data("question-id");
        $this.removeClass(
            "bg-label-primary bg-label-success bg-label-secondary shadow-sm disabled-nav"
        );
        if (order == data.current_order) {
            $this.addClass("bg-label-primary shadow-sm");
        } else if (data.answered_question_ids.includes(questionId)) {
            $this.addClass("bg-label-success");
        } else {
            $this.addClass("bg-label-secondary");
        }
        if (order > data.quiz.last_order) {
            $this.addClass("disabled-nav");
        }
    });

    // Update Previous button
    let prevUrl =
        data.current_order > 1
            ? '{{ route("student.quizzes.take", [$quiz->uuid, ":order"]) }}'.replace(
                  ":order",
                  data.current_order - 1
              )
            : "";
    $("#prev-button")
        .toggleClass("disabled", data.current_order === 1)
        .data("url", prevUrl);
}

$(".question-nav").on("click", function () {
    if (!$(this).hasClass("disabled-nav")) {
        let url = $(this).data("url");
        let order = $(this).data("order");
        if (!$('input[name="answer_id"]:checked').length) {
            showAlert().then((result) => {
                if (result.isConfirmed) {
                    loadQuestion(url, order);
                }
            });
        } else {
            loadQuestion(url, order);
        }
    }
});

$("#prev-button").on("click", function () {
    if (!$(this).hasClass("disabled")) {
        let url = $(this).data("url");
        let order = $(this).data("order");
        if (!$('input[name="answer_id"]:checked').length) {
            showAlert().then((result) => {
                if (result.isConfirmed) {
                    loadQuestion(url, order);
                }
            });
        } else {
            loadQuestion(url, order);
        }
    }
});

$("#next-button").on("click", function () {
    handleQuizSubmit("#quiz-form");
});
