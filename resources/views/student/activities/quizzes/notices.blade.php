@extends('layouts.student.master')

@section('page-css')

@endsection

@section('title', pageTitle('admin/quizzes.quizzes'))

@section('content')
    <div class="row g-6">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-6 gap-1">
                        <div class="me-1">
                            <h5 class="mb-0">{{ $quiz->name }}</h5>
                            <p class="mb-0">{{ trans('main.mr') }}/{{ trans('main.mrs') }}: <span class="fw-medium text-heading">{{ $quiz->teacher->name ?? 'N/A' }} </span></p>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-label-success rounded-pill">{{ $quiz->grade->name ?? 'N/A' }}</span>
                            <i class="ri-share-forward-line ri-24px mx-4 cursor-pointer" data-bs-toggle="tooltip"
                                title="{{ trans('main.share') }}"></i>
                        </div>
                    </div>
                    <div class="card academy-content shadow-none border">
                        <div class="card-body pt-3">
                            <h5>{{ trans('admin/assignments.details') }}</h5>
                            <div class="d-flex flex-wrap row-gap-2">
                                <div class="me-12">
                                    <p class="text-nowrap mb-3">
                                        <i class="ri-calendar-schedule-line ri-20px me-2"></i>{{ trans('main.duration') }}:
                                        {{ formatDuration($quiz->duration) }}
                                    </p>
                                    <p class="text-nowrap mb-3">
                                        <i class="ri-calendar-schedule-line ri-20px me-2"></i>{{ trans('admin/quizzes.questionsCount') }}:
                                        {{ $quiz->questions_count }}
                                    </p>
                                    <p class="text-nowrap mb-3">
                                        <i class="ri-star-smile-line ri-20px me-2"></i>{{ trans('main.start_time') }}:
                                        {{ isoFormat($quiz->start_time) }}
                                    </p>
                                    <p class="text-nowrap mb-3">
                                        <i class="ri-survey-line ri-20px me-2"></i>{{ trans('main.end_time') }}:
                                        {{ isoFormat($quiz->end_time) }}
                                    </p>
                                </div>
                            </div>
                            <hr class="my-6" />
                            <h5>{{ trans('admin/assignments.instructions') }}</h5>
                            <p class="mb-6">{{ __('أجب على الأسئلة واحدًا تلو الآخر. استخدم زري "التالي" و"السابق" للتنقل. يمكنك تعديل إجاباتك باستخدام "السابق".') }}
                            </p>
                            <p class="mb-6">{{ __('لديك') }} {{ formatDuration($quiz->duration) }}
                                {{ __('لإكمال الاختبار. ستظهر تحذيرات (نافذة منبثقة، مؤقت وامض، صوت) عند 5 و2 و1 دقيقة متبقية.') }}</p>
                            <p class="mb-6">{{ __('5 مخالفات (مثل تبديل علامات التبويب، النقرات المتعددة) ستؤدي إلى إرسال الاختبار تلقائيًا. سيتم مراجعة المخالفات بواسطة المعلم.') }}
                            </p>
                            <p class="mb-6">{{ __('تابع تقدمك عبر شريط التقدم. احصل على رسائل تشجيعية بعد كل 10 أسئلة!') }}</p>
                            @if (!now()->between($quiz->start_time, $quiz->end_time))
                                <a href="#" class="btn btn-secondary me-2 waves-effect waves-light">{{ trans('account.notAvailable') }}</a>
                            @elseif ($result && $result->status == 1)
                                <a href="{{ route('student.quizzes.take', $quiz->uuid) }}"
                                class="btn btn-primary me-2 waves-effect waves-light">{{ trans('admin/quizzes.resumeQuiz') }}</a>
                            @else
                                <a href="{{ route('student.quizzes.take', $quiz->uuid) }}"
                                class="btn btn-primary me-2 waves-effect waves-light">{{ trans('admin/quizzes.startQuiz') }}</a>
                            @endif
                            @if ($quiz->quiz_mode == 1 && now()->between($quiz->start_time, $quiz->end_time))
                                <div class="progress mt-3">
                                    <div class="progress-bar" role="progressbar" id="quizTimer" style="width: 0%"></div>
                                </div>
                                <script>
                                    const endTime = new Date('{{ $quiz->end_time }}').getTime();
                                    const updateTimer = () => {
                                        const now = Date.now();
                                        const remaining = Math.max(0, (endTime - now) / 1000);
                                        const percent = (remaining / ({{ $quiz->duration }} * 60)) * 100;
                                        document.getElementById('quizTimer').style.width = percent + '%';
                                        if (remaining <= 0) clearInterval(timer);
                                    };
                                    updateTimer();
                                    const timer = setInterval(updateTimer, 1000);
                                </script>
                            @endif
                            <hr class="my-6" />
                            <h5>{{ app()->getLocale() === 'ar' ? 'ال' : '' }}{{ trans('admin/teachers.teacher') }}</h5>
                            <div class="d-flex justify-content-start align-items-center user-name">
                                <div class="avatar-wrapper">
                                    <div class="avatar me-4">
                                        <img src="{{ $quiz->teacher->profile_pic ? asset('storage/profiles/teachers/' . $quiz->teacher->profile_pic) : asset('assets/img/avatars/default.jpg') }}"
                                            alt="Avatar" class="rounded-circle" />
                                    </div>
                                </div>
                                <div class="d-flex flex-column">
                                    <h6 class="mb-1">{{ $quiz->teacher->name ?? 'N/A' }}</h6>
                                    <small>{{ $quiz->teacher->phone ?? 'N/A' }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-js')
    <script>
        toggleShareButton();
    </script>
@endsection
