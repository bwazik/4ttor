@extends('layouts.student.master')

@section('title', $quiz->name)

@section('content')
<div class="col-12">
    <h3>{{ $quiz->name }}</h3>
    <div class="mt-2">
        <span id="timer" class="badge bg-primary">Time Remaining: <span id="time-left">{{ $quiz->duration }}:00</span></span>
    </div>
    <small class="text-light fw-medium d-block mb-3">Answer all questions</small>

    <form id="quiz-form" onsubmit="return false">
        @foreach ($quiz->questions as $index => $question)
            <div class="question-step" style="{{ $index === 0 ? '' : 'display: none;' }}">
                <div class="content-header mb-4">
                    <h6 class="mb-0">Question {{ $index + 1 }} of {{ count($quiz->questions) }}</h6>
                    <small>{{ $question->question_text }}</small>
                </div>
                <div class="row g-3">
                    @foreach ($question->answers as $answer)
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input"
                                       type="radio"
                                       name="question-{{ $question->id }}"
                                       id="answer-{{ $answer->id }}"
                                       value="{{ $answer->id }}">
                                <label class="form-check-label" for="answer-{{ $answer->id }}">
                                    {{ $answer->answer_text }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="mt-4 d-flex justify-content-between">
            <button type="button" class="btn btn-secondary" id="prev-btn" disabled>Previous</button>
            <button type="button" class="btn btn-primary" id="next-btn">Next</button>
            <button type="submit" class="btn btn-success d-none" id="submit-btn">Submit</button>
        </div>
    </form>
</div>
@endsection

@section('page-js')
<script>
    $(document).ready(function () {
        const steps = $('.question-step');
        let currentStep = 0;

        function updateStep() {
            steps.hide();
            steps.eq(currentStep).show();

            $('#prev-btn').prop('disabled', currentStep === 0);
            $('#next-btn').toggle(currentStep < steps.length - 1);
            $('#submit-btn').toggle(currentStep === steps.length - 1);
        }

        $('#next-btn').click(() => {
            if (currentStep < steps.length - 1) {
                currentStep++;
                updateStep();
            }
        });

        $('#prev-btn').click(() => {
            if (currentStep > 0) {
                currentStep--;
                updateStep();
            }
        });

        updateStep();

        // Timer Logic
        let duration = {{ $quiz->duration }} * 60;
        const timerDisplay = $('#time-left');

        function updateTimer() {
            if (duration <= 0) {
                $('#quiz-form').trigger('submit');
                return;
            }

            const minutes = Math.floor(duration / 60);
            const seconds = duration % 60;
            timerDisplay.text(`${minutes}:${seconds.toString().padStart(2, '0')}`);
            duration--;

            if (duration <= 30) {
                $('#timer').removeClass('bg-primary').addClass('bg-danger');
            }
        }

        updateTimer();
        const timerInterval = setInterval(updateTimer, 1000);

        $('#quiz-form').on('submit', function () {
            clearInterval(timerInterval);
            // You can submit via AJAX or regular POST here
            alert('Quiz submitted!');
        });
    });
</script>
@endsection
