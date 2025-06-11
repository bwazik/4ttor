@extends('layouts.admin.master')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-faq.css') }}" />
@endsection

@section('title', pageTitle('admin/faqs.faqs'))

@section('content')
    <div
        class="faq-header d-flex flex-column justify-content-center align-items-center h-px-300 position-relative overflow-hidden rounded-4">
        <img src="{{ asset('assets/img/pages/header-light.png') }}" class="scaleX-n1-rtl faq-banner-img h-px-300 z-n1"
            alt="background image" data-app-light-img="pages/header-light.png" data-app-dark-img="pages/header-dark.png" />
        <h4 class="text-center text-primary mb-2">{{ trans('admin/faqs.header') }}</h4>
        <p class="text-body text-center mb-0 px-4">{{ trans('admin/faqs.subheader') }}</p>
        <div class="d-flex gap-3">
            <button id="delete-selected-btn" class="btn btn-danger mb-6 mt-7 px-sm-5 waves-effect waves-light"
                tabindex="0" data-bs-target="#delete-selected-modal" data-bs-toggle="modal" data-bs-dismiss="modal">
                <span>
                    <i class="ri-delete-bin-line ri-16px me-sm-2"></i>
                    <span class="d-none d-sm-inline-block">{{ trans('main.deleteSelected') }}</span>
                </span>
            </button>
            <button id="add-button" type="button" class="btn btn-primary mb-6 mt-7 px-sm-5 waves-effect waves-light"
                tabindex="0" data-bs-toggle="modal" data-bs-target="#add-modal">
                <span>
                    <i class="ri-add-line ri-16px me-sm-2"></i>
                    <span
                        class="d-none d-sm-inline-block">{{ trans('main.addItem', ['item' => trans('admin/faqs.faq')]) }}</span>
                </span>
            </button>
        </div>
    </div>

    <div class="row mt-6">
        <!-- Navigation -->
        <div class="col-lg-3 col-md-4 col-12 mb-md-0 mb-4">
            <div class="d-flex justify-content-between flex-column nav-align-left mb-2 mb-md-0">
                <ul class="nav nav-pills flex-column flex-nowrap" role="tablist">
                    @foreach ($categories as $index => $category)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $index === 0 ? 'active' : '' }}" data-bs-toggle="tab"
                                data-bs-target="#{{ $category->slug }}"
                                aria-selected="{{ $index === 0 ? 'true' : 'false' }}" role="tab">
                                <i class="ri ri-{{ $category->icon }} me-2"></i>
                                <span class="align-middle">{{ $category->name }}</span>
                            </button>
                        </li>
                    @endforeach
                </ul>
                <div class="d-none d-md-block">
                    <div class="mt-4 text-center">
                        <img src="{{ asset('assets/img/illustrations/faq-illustration.png') }}" class="img-fluid"
                            width="135" alt="FAQ Image" />
                    </div>
                </div>
            </div>
        </div>
        <!-- /Navigation -->

        <!-- FAQs -->
        <div class="col-lg-9 col-md-8 col-12">
            <div class="tab-content p-0">
                @foreach ($categories as $index => $category)
                    <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="{{ $category->slug }}"
                        role="tabpanel">
                        <div class="d-flex mb-4 gap-4 align-items-center">
                            <div class="avatar avatar-md">
                                <span class="avatar-initial bg-label-primary rounded-4">
                                    <i class="ri ri-{{ $category->icon }} ri-30px"></i>
                                </span>
                            </div>
                            <div>
                                <h5 class="mb-0">
                                    <span class="align-middle">{{ $category->name }}</span>
                                </h5>
                                <span>{{ $category->description }}</span>
                            </div>
                        </div>
                        <div id="accordion{{ $category->slug }}"
                            class="accordion accordion-popout accordion-header-primary">
                            @forelse ($category->faqs as $faqIndex => $faq)
                                <div class="accordion-item">
                                    <h2 class="accordion-header d-flex align-items-center">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            aria-expanded="false"
                                            data-bs-target="#accordion{{ $category->slug }}-{{ $faq->id }}"
                                            aria-controls="accordion{{ $category->slug }}-{{ $faq->id }}">
                                            <div class="d-flex align-items-center">
                                                <div class="dt-checkboxes-cell me-2">
                                                    <input type="checkbox" value="{{ $faq->id }}"
                                                        class="dt-checkboxes form-check-input">
                                                </div>
                                                <div class="me-1 text-nowrap">{{ $faqIndex + 1 }} -</div>
                                            </div>
                                            <span class="flex-grow-1 text-break">{{ $faq->question }}</span>
                                        </button>

                                        <div class="d-flex align-items-center ms-2">
                                            <button
                                                class="btn btn-sm btn-icon btn-text-secondary text-body rounded-pill waves-effect waves-light me-1"
                                                tabindex="0" type="button" data-bs-toggle="modal"
                                                data-bs-target="#edit-modal" id="edit-button" data-id="{{ $faq->id }}"
                                                data-category_id="{{ $faq->category_id }}"
                                                data-audience="{{ $faq->audience }}"
                                                data-is_active="{{ $faq->is_active ? 1 : 0 }}"
                                                data-is_at_landing="{{ $faq->is_at_landing ? 1 : 0 }}"
                                                data-order="{{ $faq->order }}"
                                                data-question_ar="{{ $faq->getTranslation('question', 'ar') }}"
                                                data-question_en="{{ $faq->getTranslation('question', 'en') }}"
                                                data-answer_ar="{{ $faq->getTranslation('answer', 'ar') }}"
                                                data-answer_en="{{ $faq->getTranslation('answer', 'en') }}">
                                                <i class="ri-edit-box-line ri-20px"></i>
                                            </button>
                                            <button
                                                class="btn btn-sm btn-icon btn-text-danger rounded-pill text-body waves-effect waves-light me-1"
                                                id="delete-button" data-id="{{ $faq->id }}"
                                                data-question_ar="{{ $faq->getTranslation('question', 'ar') }}"
                                                data-question_en="{{ $faq->getTranslation('question', 'en') }}"
                                                data-bs-target="#delete-modal" data-bs-toggle="modal"
                                                data-bs-dismiss="modal">
                                                <i class="ri-delete-bin-7-line ri-20px text-danger"></i>
                                            </button>
                                        </div>
                                    </h2>
                                    <div id="accordion{{ $category->slug }}-{{ $faq->id }}"
                                        class="accordion-collapse collapse"
                                        data-bs-parent="#accordion{{ $category->slug }}">
                                        <div class="accordion-body">
                                            {!! $faq->answer !!}
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center mt-6">{{ trans('main.datatable.empty_table') }}</div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <!-- /FAQs -->
    </div>

    <!-- Contact -->
    <div class="row my-6">
        <div class="col-12 text-center my-6">
            <div class="badge bg-label-primary rounded-pill">{{ trans('admin/faqs.contact.question') }}</div>
            <h4 class="my-2">{{ trans('admin/faqs.contact.title') }}</h4>
            <p class="mb-0">{{ trans('admin/faqs.contact.subtitle') }}</p>
        </div>
    </div>
    <div class="row justify-content-center gap-sm-0 gap-6">
        <div class="col-sm-6">
            <div class="p-6 rounded-4 bg-faq-section d-flex align-items-center flex-column">
                <div class="avatar avatar-md">
                    <span class="avatar-initial bg-label-primary rounded-3">
                        <i class="ri-phone-line ri-30px"></i>
                    </span>
                </div>
                <h5 class="mt-4 mb-1"><a class="text-heading" href="tel:+(810)25482568">+201098617164</a></h5>
                <p class="mb-0">{{ trans('admin/faqs.contact.phone') }}</p>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="p-6 rounded-4 bg-faq-section d-flex align-items-center flex-column">
                <div class="avatar avatar-md">
                    <span class="avatar-initial bg-label-primary rounded-3">
                        <i class="ri-mail-line ri-30px"></i>
                    </span>
                </div>
                <h5 class="mt-4 mb-1"><a class="text-heading" href="mailto:support@shattor.com">support@shattor.com</a></h5>
                <p class="mb-0">{{ trans('admin/faqs.contact.email') }}</p>
            </div>
        </div>
    </div>
    <!-- /Contact -->
    @include('admin.misc.faqs.modals')
@endsection

@section('page-js')
    <script>
        // Setup add modal
        setupModal({
            buttonId: '#add-button',
            modalId: '#add-modal',
            fields: {
                category_id: () => '',
                audience: () => '',
                is_active: () => '',
                is_at_landing: () => '',
            }
        });

        // Setup edit modal
        setupModal({
            buttonId: '#edit-button',
            modalId: '#edit-modal',
            fields: {
                id: button => button.data('id'),
                category_id: button => button.data('category_id'),
                audience: button => button.data('audience'),
                is_active: button => button.data('is_active'),
                is_at_landing: button => button.data('is_at_landing'),
                order: button => button.data('order'),
                question_ar: button => button.data('question_ar'),
                question_en: button => button.data('question_en'),
                answer_ar: button => button.data('answer_ar'),
                answer_en: button => button.data('answer_en'),
            }
        });
        // Setup delete modal
        setupModal({
            buttonId: '#delete-button',
            modalId: '#delete-modal',
            fields: {
                id: button => button.data('id'),
                itemToDelete: button => `${button.data('question_ar')} - ${button.data('question_en')}`
            }
        });

        let fields = ['category_id', 'audience', 'is_active', 'is_at_landing', 'question_ar', 'question_en', 'answer_ar',
            'answer_en'
        ];
        handleFormSubmit('#add-form', fields, '#add-modal', 'modal');
        handleFormSubmit('#edit-form', fields, '#edit-modal', 'modal');
        handleDeletionFormSubmit('#delete-form', '#delete-modal');
        handleDeletionFormSubmit('#delete-selected-form', '#delete-selected-modal');
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
@endsection
