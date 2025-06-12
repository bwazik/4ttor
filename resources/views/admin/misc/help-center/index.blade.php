@extends('layouts.landing.master')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/front-page-help-center.css') }}" />
@endsection

@section('title', pageTitle('admin/helpCenter.helpCenter'))

@section('content')
    <!-- Help Center Header: Start -->
    <section class="section-py first-section-pt help-center-header position-relative overflow-hidden">
        <img class="banner-bg-img z-n1" src="../../assets/img/pages/header-light.png" alt="Help center header"
            data-app-light-img="pages/header-light.png" data-app-dark-img="pages/header-dark.png" />
        <h4 class="text-center text-primary mb-2">{{ trans('admin/helpCenter.header') }}</h4>
        <p class="text-body text-center mb-0 px-4">{{ trans('admin/helpCenter.subheader') }}</p>
        <div class="d-flex justify-content-center gap-3">
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
                        class="d-none d-sm-inline-block">{{ trans('main.addItem', ['item' => trans('admin/helpCenter.article')]) }}</span>
                </span>
            </button>
        </div>
    </section>
    <!-- Help Center Header: End -->

    <!-- Pinned Articles: Start -->
    <section class="section-py">
        <div class="container">
            <h4 class="text-center mb-6">{{ trans('admin/helpCenter.pinnedArticles') }}</h4>
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="row gy-6 gy-md-0">
                        @forelse ($pinnedArticles as $article)
                            <div class="col-md-4">
                                <div class="card border shadow-none">
                                    <div class="card-body text-center">
                                        <span
                                            class="avatar-initial bg-label-primary rounded-3 d-inline-flex align-items-center justify-content-center"
                                            style="width: 64px; height: 64px;">
                                            <i class="ri ri-{{ $article->category->icon }}"
                                                style="font-size: 30px; line-height: 1;"></i>
                                        </span>
                                        <h5 class="my-3">{{ $article->title }}</h5>
                                        <p class="mb-3">
                                            {{ Str::limit($article->description, 60, '…') }}</p>
                                        <a class="btn btn-outline-primary"
                                            href="{{ route('admin.help-center.show', [$article->category->slug, $article->slug]) }}">{{ trans('admin/helpCenter.readMore') }}</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center mt-6">{{ trans('admin/helpCenter.emptyArticles') }}</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Pinned Articles: End -->

    <!-- Knowledge Base: Start -->
    <section class="section-py bg-body">
        <div class="container">
            <h4 class="text-center mb-6">{{ trans('admin/helpCenter.articlesCategories') }}</h4>
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="row g-6">
                        @foreach ($categories as $category)
                            <div class="col-md-4 col-ms-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="avatar avatar-sm flex-shrink-0 me-2 d-flex align-items-center justify-content-center">
                                                <span
                                                    class="avatar-initial bg-label-primary rounded-3 d-flex align-items-center justify-content-center">
                                                    <i class="ri ri-{{ $category->icon }} ri-20px"></i>
                                                </span>
                                            </div>
                                            <h5 class="mb-0 ms-1">{{ $category->name }}</h5>
                                        </div>
                                        <ul class="list-unstyled my-6">
                                            @forelse ($category->articles->take(6) as $article)
                                                <li class="mb-2">
                                                    <a href="{{ route('admin.help-center.show', [$category->slug, $article->slug]) }}"
                                                        class="text-heading d-flex justify-content-between align-items-center">
                                                        <div class="dt-checkboxes-cell">
                                                            <input type="checkbox" value="{{ $article->id }}"
                                                                class="dt-checkboxes form-check-input">
                                                        </div>
                                                        <span class="text-truncate me-1">{{ $article->title }}</span>
                                                        <div class="d-flex align-items-center">
                                                            <i
                                                                class="tf-icons ri-arrow-right-s-line ri-20px scaleX-n1-rtl text-muted"></i>
                                                            <button
                                                                class="btn btn-sm btn-icon btn-text-secondary text-body rounded-pill waves-effect waves-light"
                                                                tabindex="0" type="button" data-bs-toggle="modal"
                                                                data-bs-target="#edit-modal" id="edit-button"
                                                                data-id="{{ $article->id }}"
                                                                data-title_ar="{{ $article->getTranslation('title', 'ar') }}"
                                                                data-title_en="{{ $article->getTranslation('title', 'en') }}"
                                                                data-slug="{{ $article->slug }}"
                                                                data-category_id="{{ $article->category_id }}"
                                                                data-audience="{{ $article->audience }}"
                                                                data-is_active="{{ $article->is_active ? 1 : 0 }}"
                                                                data-is_pinned="{{ $article->is_pinned ? 1 : 0 }}"
                                                                data-description_ar="{{ $article->getTranslation('description', 'ar') }}"
                                                                data-description_en="{{ $article->getTranslation('description', 'en') }}">
                                                                <i class="ri-edit-box-line ri-20px"></i>
                                                            </button>
                                                            <button
                                                                class="btn btn-sm btn-icon btn-text-danger rounded-pill text-body waves-effect waves-light me-1"
                                                                id="delete-button" data-id="{{ $article->id }}"
                                                                data-title_ar="{{ $article->getTranslation('title', 'ar') }}"
                                                                data-title_en="{{ $article->getTranslation('title', 'en') }}"
                                                                data-bs-target="#delete-modal" data-bs-toggle="modal"
                                                                data-bs-dismiss="modal">
                                                                <i class="ri-delete-bin-7-line ri-20px text-danger"></i>
                                                            </button>
                                                        </div>
                                                    </a>
                                                </li>
                                            @empty
                                                <div class="text-center mt-6">
                                                    {{ trans('admin/helpCenter.emptyArticles') }}
                                                </div>
                                            @endforelse
                                        </ul>
                                        @if ($category->articles->isNotEmpty())
                                            <p class="mb-0 fw-medium mt-6">
                                                <a href="{{ route('admin.help-center.show', [$category->slug, $category->articles->sortByDesc('published_at')->first()->slug]) }}"
                                                    class="d-flex align-items-center">
                                                    <span
                                                        class="me-3">{{ trans('admin/helpCenter.seeAll', ['count' => $category->articles->count()]) }}</span>
                                                    <i class="tf-icons ri-arrow-right-line scaleX-n1-rtl"></i>
                                                </a>
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Knowledge Base: End -->

    <!-- Help Area: Start -->
    <section class="section-py">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 text-center">
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
                        <h5 class="mt-4 mb-1"><a class="text-heading"
                                href="https://wa.me/+201098617164">+201098617164</a>
                        </h5>
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
                        <h5 class="mt-4 mb-1"><a class="text-heading"
                                href="mailto:support@shattor.com">support@shattor.com</a></h5>
                        <p class="mb-0">{{ trans('admin/faqs.contact.email') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Help Area: End -->

    @include('admin.misc.help-center.modals')
@endsection

@section('page-js')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/custom.js') }}"></script>

    <script>
        // Setup add modal
        setupModal({
            buttonId: '#add-button',
            modalId: '#add-modal',
            fields: {
                category_id: () => '',
                audience: () => '',
                is_active: () => '',
                is_pinned: () => '',
            }
        });

        // Setup edit modal
        setupModal({
            buttonId: '#edit-button',
            modalId: '#edit-modal',
            fields: {
                id: button => button.data('id'),
                title_ar: button => button.data('title_ar'),
                title_en: button => button.data('title_en'),
                slug: button => button.data('slug'),
                category_id: button => button.data('category_id'),
                audience: button => button.data('audience'),
                is_active: button => button.data('is_active'),
                is_pinned: button => button.data('is_pinned'),
                description_ar: button => button.data('description_ar'),
                description_en: button => button.data('description_en'),
            }
        });
        // Setup delete modal
        setupModal({
            buttonId: '#delete-button',
            modalId: '#delete-modal',
            fields: {
                id: button => button.data('id'),
                itemToDelete: button => `${button.data('title_ar')} - ${button.data('title_en')}`
            }
        });

        let fields = ['title_ar', 'title_en', 'slug', 'category_id', 'audience', 'is_active', 'is_pinned',
            'description_ar', 'description_en'
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
