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
                            <p class="mb-0">{{ trans('main.mr') }}: <span class="fw-medium text-heading">
                                    {{ $quiz->teacher->name ?? 'N/A' }} </span></p>
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
                                        <i class="ri-calendar-schedule-line ri-20px me-2"></i>{{ trans('main.deadline') }}:
                                        {{ isoFormat($quiz->start_time) }}
                                    </p>
                                    <p class="text-nowrap mb-3">
                                        <i class="ri-star-smile-line ri-20px me-2"></i>{{ trans('main.score') }}:
                                        {{ $quiz->duration }}
                                    </p>
                                    <p class="text-nowrap mb-3">
                                        <i class="ri-survey-line ri-20px me-2"></i>{{ trans('main.grade') }}:
                                        {{ $quiz->grade->name ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                            <hr class="my-6" />
                            <h5>{{ trans('admin/assignments.instructions') }}</h5>
                            <p class="mb-6">
                                sd
                            </p>
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
                                    <small>{{ trans('admin/teachers.teacher') }}
                                        {{ $quiz->teacher->subject->name ?? 'N/A' }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <h1>{{ __('تعليمات الاختبار') }}: {{ $quiz->name }}</h1>
        <div class="card">
            <div class="card-body">
                <h5>{{ __('تفاصيل الاختبار') }}</h5>
                <ul>
                    <li><strong>{{ __('المدة') }}:</strong> {{ formatDuration($quiz->duration) }}</li>
                    <li><strong>{{ __('عدد الأسئلة') }}:</strong> {{ $quiz->questions_count }}</li>
                    <li><strong>{{ __('البدء') }}:</strong> {{ isoFormat($quiz->start_time) }}</li>
                    <li><strong>{{ __('الانتهاء') }}:</strong> {{ isoFormat($quiz->end_time) }}</li>
                </ul>

                <h5>{{ __('التعليمات') }}</h5>
                <p>{{ __('أجب على الأسئلة واحدًا تلو الآخر. استخدم زري "التالي" و"السابق" للتنقل. يمكنك تعديل إجاباتك باستخدام "السابق".') }}
                </p>

                <h5>{{ __('قواعد المؤقت') }}</h5>
                <p>{{ __('لديك') }} {{ formatDuration($quiz->duration) }}
                    {{ __('لإكمال الاختبار. ستظهر تحذيرات (نافذة منبثقة، مؤقت وامض، صوت) عند 5 و2 و1 دقيقة متبقية.') }}</p>

                <h5>{{ __('قواعد المخالفات') }}</h5>
                <p>{{ __('5 مخالفات (مثل تبديل علامات التبويب، النقرات المتعددة) ستؤدي إلى إرسال الاختبار تلقائيًا. سيتم مراجعة المخالفات بواسطة المعلم.') }}
                </p>

                <h5>{{ __('التقدم') }}</h5>
                <p>{{ __('تابع تقدمك عبر شريط التقدم. احصل على رسائل تشجيعية بعد كل 10 أسئلة!') }}</p>

                <a href="{{ route('student.quizzes.take', $quiz->uuid) }}"
                    class="btn btn-success">{{ __('ابدأ الاختبار') }}</a>
            </div>
        </div>
    </div>
@endsection

@section('page-js')
    <script>
        toggleShareButton();
    </script>
@endsection
