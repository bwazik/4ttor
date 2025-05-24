@extends('layouts.student.master')
@php
    use Carbon\Carbon;
@endphp

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}">
    <style>
        .disabled-nav {
            pointer-events: none;
            cursor: not-allowed;
            opacity: 0.6;
        }
    </style>
@endsection

@section('title', pageTitle($quiz->name))

@section('content')
    <div class="row gy-4">
        <div class="col-lg-3 col-md-12">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title">{{ $quiz->name }}</h5>
                    <p class="card-subtitle mb-2">{{ trans('admin/quizzes.totalQuestions') }}: {{ $quiz->questions_count }}
                    </p>
                    <p class="card-subtitle mb-0">{{ trans('admin/quizzes.totalScore') }}: {{ $quiz->total_score }}</p>
                </div>
                <div class="card-body">
                    <h6 class="mb-3">{{ trans('admin/quizzes.questionNavigator') }}:</h6>
                    <div id="question-navigator" class="d-flex justify-content-center flex-wrap">
                        @foreach ($quizOrders as $index => $order)
                            <div class="p-1">
                                <a href="javascript:void(0)"
                                    class="badge question-nav d-flex align-items-center justify-content-center waves-effect {{ $order->display_order == $currentOrder ? 'bg-label-primary shadow-sm' : (in_array($order->question_id, $answeredQuestionIds) ? 'bg-label-success' : 'bg-label-secondary') }} {{ $order->display_order > $result->last_order ? 'disabled-nav' : '' }}"
                                    style="width: 38px; height: 38px; text-decoration: none; font-size: 0.9rem;"
                                    data-url="{{ route('student.quizzes.take', [$quiz->uuid, $order->display_order]) }}"
                                    data-order="{{ $order->display_order }}" data-question-id="{{ $order->question_id }}"
                                    role="button">
                                    {{ $order->display_order }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9 col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                    <h5 id="question_title" class="card-title mb-0">
                        {{ trans('admin/questions.question') }} {{ $currentOrder }} {{ trans('admin/quizzes.of') }}
                        {{ $quiz->questions_count }}
                    </h5>
                    @if ($timeRemaining !== null)
                        <span id="timer-badge" class="badge bg-primary fs-6">
                            <i class="ri-time-line me-1"></i>
                            {{ trans('admin/quizzes.timeRemaining') }}: <span id="time-left"
                                data-seconds="{{ $timeRemaining ?? 0 }}"></span>
                        </span>
                    @else
                        <span class="badge bg-info fs-6">{{ trans('admin/quizzes.noTimeLimit') }}</span>
                    @endif
                </div>
                <div class="card-body">
                    <form id="quiz-form" action="{{ route('student.quizzes.submit', $quiz->uuid) }}" method="POST">
                        @csrf
                        <input id="question_id" type="hidden" name="question_id" value="{{ $question->id }}">
                        <input id="current_order" type="hidden" name="current_order" value="{{ $currentOrder }}">
                        <p id="question_text" class="card-text fs-5 mt-3 fw-medium">{{ $question->question_text }}</p>
                        <div id="answer-options" class="row">
                            @foreach ($answers as $answer)
                                <div class="col-md-12 mb-3">
                                    <div
                                        class="form-check custom-option custom-option-icon {{ $previousAnswer && $previousAnswer->answer_id == $answer->id ? 'checked' : '' }}">
                                        <label class="form-check-label custom-option-content"
                                            for="answer-{{ $answer->id }}">
                                            <span class="custom-option-body">
                                                {{ $answer->answer_text }}
                                            </span>
                                            <input name="answer_id" class="form-check-input" type="radio"
                                                value="{{ $answer->id }}" id="answer-{{ $answer->id }}"
                                                {{ $previousAnswer && $previousAnswer->answer_id == $answer->id ? 'checked' : '' }}>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="d-flex justify-content-between mt-4 pt-2 border-top">
                            <button type="button" id="prev-button"
                                class="btn btn-outline-secondary btn-prev waves-effect {{ $currentOrder == 1 ? 'disabled' : '' }}"
                                data-url="{{ $currentOrder > 1 ? route('student.quizzes.take', [$quiz->uuid, $currentOrder - 1]) : '' }}">
                                <i class="icon-base ri ri-arrow-right-line icon-16px me-sm-1 me-0"></i>
                                <span class="align-middle d-sm-inline-block d-none">{{ trans('main.previous') }}</span>
                            </button>
                            <button id="next-button" type="submit"
                                class="btn btn-primary btn-next waves-effect waves-light">
                                <span class="align-middle d-sm-inline-block d-none me-sm-1">{{ trans('main.next') }}</span>
                                <i class="icon-base ri ri-arrow-left-line icon-16px"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('page-js')
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>

    <script>
        function showAlert(title, text, icon, confirmButtonText) {
            if (typeof Swal !== 'undefined' && typeof Swal.fire === 'function') {
                return Swal.fire({
                    title: title,
                    text: text,
                    icon: icon,
                    confirmButtonText: confirmButtonText || '{{ trans('main.submit') }}',
                    customClass: {
                        confirmButton: 'btn btn-primary waves-effect waves-light'
                    },
                    buttonsStyling: false
                });
            } else {
                Swal.fire({
                    title: title,
                    text: text,
                    icon: icon,
                    confirmButtonText: confirmButtonText || '{{ trans('main.submit') }}',
                    customClass: {
                        confirmButton: 'btn btn-primary waves-effect waves-light'
                    },
                    buttonsStyling: false
                });
            }
        }

        function formatTime(seconds) {
            let hours = Math.floor(seconds / 3600);
            let minutes = Math.floor((seconds % 3600) / 60);
            let secs = seconds % 60;
            return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }

        function handleQuizSubmit(formId) {
            const $form = $(formId);
            const $submitButton = $form.find('button[type="submit"]');
            const $prevButton = $form.find('button[id="prev-button"]');
            const originalButtonContent = $submitButton.html();
            const originalPrevButtonContent = $prevButton.html();

            $(formId).on("submit", function(e) {
                e.preventDefault();

                $submitButton.find(".waves-ripple").remove();
                $submitButton.prop("disabled", true);
                $submitButton.html(
                    `<i class="ri-loader-4-line ri-spin ri-20px me-1"></i> ${window.translations.processing}...`
                );
                $prevButton.find(".waves-ripple").remove();
                $prevButton.prop("disabled", true);
                $prevButton.html(
                    `<i class="ri-loader-4-line ri-spin ri-20px me-1"></i> ${window.translations.processing}...`
                );

                let nextOrder = parseInt($("#current_order").val()) + 1;
                let nextUrl =
                    '{{ route('student.quizzes.take', [$quiz->uuid, ':order']) }}'.replace(
                        ":order",
                        nextOrder
                    );

                if (!$('input[name="answer_id"]:checked').length) {
                    showAlert("{{ trans('main.warning') }}", "{{ trans('admin/quizzes.noAnswerSelected') }}", "warning", "{{ trans('admin/quizzes.confirmButtonText') }}").then((result) => {
                        if (result.isConfirmed) {
                            loadQuestion(nextUrl, nextOrder, true);
                            resetButtonState($submitButton, originalButtonContent);
                            resetButtonState($prevButton, originalPrevButtonContent);
                        } else {
                            resetButtonState($submitButton, originalButtonContent);
                            resetButtonState($prevButton, originalPrevButtonContent);
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
                    success: function(response) {
                        if (response.success) {
                            if (response.is_last) {
                                toastr.success(response.success);
                                setTimeout(() => {
                                    window.location.href =
                                        '{{ route('student.quizzes.index') }}';
                                }, 1500);
                            } else {
                                let nextUrl =
                                    "{{ route('student.quizzes.take', [$quiz->uuid, ':order']) }}"
                                    .replace(
                                        ":order",
                                        response.next_order
                                    );
                                loadQuestion(nextUrl, response.next_order, true);
                            }
                        } else if (response.error) {
                            toastr.error(response.error || errorMessage);
                            if (response.redirect) {
                                setTimeout(() => {
                                    window.location.href = response.redirect;
                                }, 1500);
                            }
                        } else {
                            toastr.error(response.message || errorMessage);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 429) {
                            toastr.error(tooManyRequestsMessage);
                        } else if (xhr.responseJSON) {
                            if (xhr.responseJSON.errors) {
                                $.each(xhr.responseJSON.errors, function(key, val) {
                                    toastr.error(val[0]);
                                });
                            } else if (xhr.responseJSON.error) {
                                toastr.error(xhr.responseJSON.error);
                                if (xhr.responseJSON.redirect) {
                                    setTimeout(() => {
                                        window.location.href = xhr.responseJSON.redirect;
                                    }, 1500);
                                }
                            } else {
                                toastr.error(errorMessage);
                            }
                        } else {
                            toastr.error(errorMessage);
                        }

                        resetButtonState($submitButton, originalButtonContent);
                        resetButtonState($prevButton, originalPrevButtonContent);
                    },
                    complete: function() {
                        resetButtonState($submitButton, originalButtonContent);
                        resetButtonState($prevButton, originalPrevButtonContent);
                    },
                });
            });
        }

        function loadQuestion(url, order, fromSubmit = false) {
            const $submitButton = $("#quiz-form").find('button[type="submit"]');
            const $prevButton = $("#quiz-form").find('button[id="prev-button"]');
            const originalButtonContent = $submitButton.html();
            const originalPrevButtonContent = $prevButton.html();

            $submitButton.find(".waves-ripple").remove();
            $submitButton.prop("disabled", true);
            $submitButton.html(
                `<i class="ri-loader-4-line ri-spin ri-20px me-1"></i> ${window.translations.processing}...`
            );
            $prevButton.find(".waves-ripple").remove();
            $prevButton.prop("disabled", true);
            $prevButton.html(
                `<i class="ri-loader-4-line ri-spin ri-20px me-1"></i> ${window.translations.processing}...`
            );

            $.ajax({
                url: url,
                method: "GET",
                headers: {
                    Accept: "application/json",
                },
                success: function(data) {
                    console.log(data);
                    if (data.success) {
                        updateQuestion(data);
                    } else if (data.status === "success") {
                        toastr.success(data.message);
                    } else {
                        toastr.error(data.message);
                    }
                },
                error: function(xhr) {
                    console.log(xhr);
                    if (xhr.status === 429) {
                        toastr.error(tooManyRequestsMessage);
                    } else if (xhr.responseJSON) {
                        if (xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function(key, val) {
                                toastr.error(val[0]);
                            });
                        } else if (xhr.responseJSON.error) {
                            toastr.error(xhr.responseJSON.error);
                            if (xhr.responseJSON.redirect) {
                                setTimeout(() => {
                                    window.location.href = xhr.responseJSON.redirect;
                                }, 1500);
                            }
                        } else {
                            toastr.error(errorMessage);
                        }
                    } else {
                        toastr.error(errorMessage);
                    }
                },
                complete: function() {
                    if (!fromSubmit) {
                        resetButtonState($submitButton, originalButtonContent);
                        resetButtonState($prevButton, originalPrevButtonContent);
                    }
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
            data.answers.forEach(function(answer) {
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
            $(".question-nav").each(function() {
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
                data.current_order > 1 ?
                '{{ route('student.quizzes.take', [$quiz->uuid, ':order']) }}'.replace(
                    ":order",
                    data.current_order - 1
                ) :
                "";
            $("#prev-button")
                .toggleClass("disabled", data.current_order === 1)
                .data("url", prevUrl);
        }

        $(document).ready(function() {
            let timeLeft = parseInt($('#time-left').data('seconds'));
            let quizMode = {{ $quiz->quiz_mode }};
            let endTime = new Date('{{ $quiz->end_time }}').getTime();
            let totalDuration =
                {{ $quiz->quiz_mode == 1 ? Carbon::parse($quiz->end_time)->diffInSeconds(Carbon::parse($quiz->start_time)) : $quiz->duration * 60 }};
            let halfTime = Math.floor(totalDuration / 2);
            let fiveMinutes = 300;
            let halfTimeReminderShown = false;
            let fiveMinutesReminderShown = false;

            // Violation tracking
            let violationCooldown = 5000;
            let lastViolationTime = 0;

            function recordViolation(type, details) {
                let now = Date.now();
                if (now - lastViolationTime < violationCooldown) return;
                lastViolationTime = now;

                $.ajax({
                    url: '{{ route('student.quizzes.violation', $quiz->uuid) }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        violation_type: type,
                    },
                    success: function(response) {
                        console.log('Violation recorded:', type);
                        showAlert("{{ trans('main.warning') }}", "{{ trans('admin/quizzes.violationMessage') }}", "error", "{{ trans('admin/quizzes.violationButtonText') }}");
                    },
                    error: function(xhr) {
                        console.error('Violation recording failed:', xhr.responseText);
                    }
                });
            }

            // Detect tab switching, app switching, or screen lock (desktop and mobile)
            document.addEventListener('visibilitychange', function() {
                if (document.hidden) {
                    recordViolation('tab_switch');
                }
            });

            // Detect page hide (mobile: app minimization, tab unload)
            window.addEventListener('pagehide', function() {
                recordViolation('tab_switch');
            });

            // Detect window focus loss (desktop: window switch, mobile: browser controls)
            window.addEventListener('blur', function() {
                recordViolation('focus_loss');
            });

            // Detect copy/paste (works on mobile)
            $(document).on('copy', function(e) {
                recordViolation('copy');
                e.preventDefault(); // Optional: block copy
            });

            $(document).on('paste', function(e) {
                recordViolation('paste');
                e.preventDefault(); // Optional: block paste
            });

            // Detect right-click (desktop) or long-press (mobile context menu)
            $(document).on('contextmenu', function(e) {
                recordViolation('context_menu');
                e.preventDefault(); // Optional: block context menu
            });

            // Detect keyboard shortcuts (desktop, limited on mobile)
            $(document).on('keydown', function(e) {
                if (e.ctrlKey && (e.key === 'c' || e.key === 'v' || e.key === 't')) {
                    recordViolation('shortcut');
                    e.preventDefault(); // Optional: block shortcut
                }
                if (e.altKey && e.key === 'Tab') {
                    recordViolation('shortcut');
                    e.preventDefault(); // Cannot fully block Alt+Tab
                }
            });

            if (timeLeft > 0) {
                $('#time-left').text(formatTime(timeLeft));
                let timer = setInterval(function() {
                    timeLeft--;
                    let now = new Date().getTime();
                    if (!halfTimeReminderShown && timeLeft <= halfTime && timeLeft > halfTime - 1) {
                        showAlert("{{ trans('main.warning') }}", "{{ trans('admin/quizzes.halfTimeMessage') }}", "info", "{{ trans('main.submit') }}");
                        halfTimeReminderShown = true;
                    }
                    if (!fiveMinutesReminderShown && timeLeft <= fiveMinutes && timeLeft > fiveMinutes -
                        1) {
                        showAlert("{{ trans('main.warning') }}", "{{ trans('admin/quizzes.fiveMinutesMessage') }}", "warning", "{{ trans('main.submit') }}");
                        fiveMinutesReminderShown = true;
                    }
                    if (timeLeft <= 0) {
                        clearInterval(timer);
                        $('#time-left').text('00:00:00');
                        if (timeLeft <= 0 || (quizMode === 1 && now >= endTime)) {
                            clearInterval(timer);
                            $('#time-left').text('00:00:00');
                            if (quizMode === 1 || quizMode === 2) {
                                toastr.error('Time expired!');
                                setTimeout(() => {
                                    window.location.href = '{{ route('student.quizzes.index') }}';
                                }, 1500);
                            }
                        } else {
                            $('#time-left').text(formatTime(timeLeft));
                        }
                    } else {
                        $('#time-left').text(formatTime(timeLeft));
                    }
                }, 1000);
            } else if (timeLeft === 0 && $('#timer-badge').length) {
                $('#time-left').text('00:00:00');
                if (quizMode === 1 || quizMode === 2) {
                    toastr.error('Time expired!');
                    setTimeout(() => {
                        window.location.href = '{{ route('student.quizzes.index') }}';
                    }, 1500);
                }
            }


            $(".question-nav").on("click", function() {
                if (!$(this).hasClass("disabled-nav")) {
                    let url = $(this).data("url");
                    let order = $(this).data("order");
                    if (!$('input[name="answer_id"]:checked').length) {
                        showAlert("{{ trans('main.warning') }}", "{{ trans('admin/quizzes.noAnswerSelected') }}", "warning", "{{ trans('admin/quizzes.confirmButtonText') }}").then((result) => {
                            if (result.isConfirmed) {
                                loadQuestion(url, order);
                            }
                        });
                    } else {
                        loadQuestion(url, order);
                    }
                }
            });

            $("#prev-button").on("click", function(e) {
                e.preventDefault();
                if (!$(this).hasClass("disabled")) {
                    let url = $(this).data("url");
                    let order = parseInt($('#current_order').val()) - 1;
                    if (!$('input[name="answer_id"]:checked').length) {
                        showAlert("{{ trans('main.warning') }}", "{{ trans('admin/quizzes.noAnswerSelected') }}", "warning", "{{ trans('admin/quizzes.confirmButtonText') }}").then((result) => {
                            if (result.isConfirmed) {
                                loadQuestion(url, order);
                            }
                        });
                    } else {
                        loadQuestion(url, order);
                    }
                }
            });

            handleQuizSubmit('#quiz-form');
        });
    </script>
@endsection
