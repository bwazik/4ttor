@extends('layouts.landing.master')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/front-page-landing.css') }}" />
    <style>
        .hero-title {
            line-height: 3.3rem;
        }

        .features-new-icon {
            border: 2px solid rgba(102, 108, 255, 0.32);
            height: 5.125rem;
            width: 5.125rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: auto;
            margin-right: auto;
            border-width: 2px;
            border-style: solid;
            border-radius: 50rem;
            transition: all 0.3s ease-in-out;
        }
    </style>
@endsection

@section('title', trans('landing.title'))

@section('content')
    <!-- Hero: Start -->
    <section id="landingHero" class="section-py landing-hero position-relative">
        <img src="{{ asset('assets/img/front-pages/backgrounds/hero-bg-light.png') }}" alt="hero background"
            class="position-absolute top-0 start-0 w-100 h-100 z-n1" data-speed="1"
            data-app-light-img="front-pages/backgrounds/hero-bg-light.png"
            data-app-dark-img="front-pages/backgrounds/hero-bg-dark.png" />
        <div class="container">
            <div class="hero-text-box text-center">
                <h3 class="text-primary hero-title fs-2">{{ trans('landing.hero.title') }}</h3>
                <h2 class="h6 mb-8">{{ trans('landing.hero.subtitle') }}</h2>
                <a href="https://wa.me/+201098617164" class="btn btn-lg btn-primary">{{ trans('landing.hero.button') }}</a>
            </div>
            <div class="position-relative hero-animation-img">
                <a href="../vertical-menu-template/app-ecommerce-dashboard.html" target="_blank">
                    <div class="hero-dashboard-img text-center">
                        <img src="{{ asset('assets/img/front-pages/landing-page/hero-dashboard-light.png') }}"
                            alt="hero dashboard" class="animation-img" data-speed="2"
                            data-app-light-img="front-pages/landing-page/hero-dashboard-light.png"
                            data-app-dark-img="front-pages/landing-page/hero-dashboard-dark.png" />
                    </div>
                    <div class="position-absolute hero-elements-img">
                        <img src="{{ asset('assets/img/front-pages/landing-page/hero-elements-light.png') }}"
                            alt="hero elements" class="animation-img" data-speed="4"
                            data-app-light-img="front-pages/landing-page/hero-elements-light.png"
                            data-app-dark-img="front-pages/landing-page/hero-elements-dark.png" />
                    </div>
                </a>
            </div>
        </div>
    </section>
    <!-- Hero: End -->

    <!-- Useful features: Start -->
    <section id="landingFeatures" class="section-py landing-features">
        <div class="container">
            <h6 class="text-center d-flex justify-content-center align-items-center mb-6">
                <img src="{{ asset('assets/img/front-pages/icons/section-tilte-icon.png') }}" alt="section title icon"
                    class="me-3" />
                <span class="text-uppercase">{{ trans('landing.features.title') }}</span>
            </h6>
            <h5 class="text-center mb-2">
                <span class="display-5 fs-4 fw-bold">{{ trans('landing.features.heading1') }}</span> {{ trans('landing.features.heading2') }}
            </h5>
            <p class="text-center fw-medium mb-4 mb-md-12">{{ trans('landing.features.subtitle') }}</p>
            <div class="features-icon-wrapper row gx-0 gy-12 gx-sm-6 mt-n4 mt-sm-0">
                <div class="col-lg-4 col-sm-6 text-center features-icon-box">
                    <span class="features-new-icon badge rounded-pill bg-label-hover-primary mb-4 p-5 d-flex align-items-center justify-content-center">
                        <i class="tf-icons ri-dashboard-3-line ri-42px"></i>
                    </span>
                    <h5 class="mb-2">{{ trans('landing.features.multiDashboards.title') }}</h5>
                    <p class="features-icon-description">{{ trans('landing.features.multiDashboards.description') }}</p>
                </div>
                <div class="col-lg-4 col-sm-6 text-center features-icon-box">
                    <span class="features-new-icon badge rounded-pill bg-label-hover-primary mb-4 p-5 d-flex align-items-center justify-content-center">
                        <i class="tf-icons ri-video-on-line ri-42px"></i>
                    </span>
                    <h5 class="mb-2">{{ trans('landing.features.zoom.title') }}</h5>
                    <p class="features-icon-description">{{ trans('landing.features.zoom.description') }}</p>
                </div>
                <div class="col-lg-4 col-sm-6 text-center features-icon-box">
                    <span class="features-new-icon badge rounded-pill bg-label-hover-primary mb-4 p-5 d-flex align-items-center justify-content-center">
                        <i class="tf-icons ri-whatsapp-line ri-42px"></i>
                    </span>
                    <h5 class="mb-2">{{ trans('landing.features.whatsappNotifications.title') }}</h5>
                    <p class="features-icon-description">{{ trans('landing.features.whatsappNotifications.description') }}</p>
                </div>
                <div class="col-lg-4 col-sm-6 text-center features-icon-box">
                    <span class="features-new-icon badge rounded-pill bg-label-hover-primary mb-4 p-5 d-flex align-items-center justify-content-center">
                        <i class="tf-icons ri-refresh-line ri-42px"></i>
                    </span>
                    <h5 class="mb-2">{{ trans('landing.features.updates.title') }}</h5>
                    <p class="features-icon-description">{{ trans('landing.features.updates.description') }}</p>
                </div>
                <div class="col-lg-4 col-sm-6 text-center features-icon-box">
                    <span class="features-new-icon badge rounded-pill bg-label-hover-primary mb-4 p-5 d-flex align-items-center justify-content-center">
                        <i class="tf-icons ri-brain-line ri-42px"></i>
                    </span>
                    <h5 class="mb-2">{{ trans('landing.features.studentActivities.title') }}</h5>
                    <p class="features-icon-description">{{ trans('landing.features.studentActivities.description') }}</p>
                </div>
                <div class="col-lg-4 col-sm-6 text-center features-icon-box">
                    <span class="features-new-icon badge rounded-pill bg-label-hover-primary mb-4 p-5 d-flex align-items-center justify-content-center">
                        <i class="tf-icons ri-customer-service-2-line ri-42px"></i>
                    </span>
                    <h5 class="mb-2">{{ trans('landing.features.support.title') }}</h5>
                    <p class="features-icon-description">{{ trans('landing.features.support.description') }}</p>
                </div>
            </div>
        </div>
    </section>
    <!-- Useful features: End -->

    <!-- Our great team: Start -->
    <section id="landingTeam" class="section-py bg-body landing-team">
        <div class="container bg-icon-right position-relative">
            <img src="{{ asset('assets/img/front-pages/icons/bg-right-icon-light.png') }}" alt="section icon"
                class="position-absolute top-0 end-0" data-speed="1"
                data-app-light-img="front-pages/icons/bg-right-icon-light.png"
                data-app-dark-img="front-pages/icons/bg-right-icon-dark.png" />
            <h6 class="text-center d-flex justify-content-center align-items-center mb-6">
                <img src="{{ asset('assets/img/front-pages/icons/section-tilte-icon.png') }}" alt="section title icon"
                    class="me-3" />
                <span class="text-uppercase">{{ trans('landing.team.title') }}</span>
            </h6>
            <h5 class="text-center mb-2"><span class="display-5 fs-4 fw-bold">{{ trans('landing.team.heading1') }}</span> {{ trans('landing.team.heading2') }}</h5>
            <p class="text-center fw-medium mb-4 mb-md-12 pb-7">{{ trans('landing.team.subtitle') }}</p>
            <div class="row gy-lg-5 gy-12 mt-2">
                <div class="col-12">
                    <div class="card card-hover-border-primary mt-4 mt-lg-0 shadow-none">
                        <div class="bg-label-primary position-relative team-image-box">
                            <img src="{{ asset('assets/img/front-pages/landing-page/team-member.png') }}"
                                class="position-absolute card-img-position bottom-0 start-50 scaleX-n1-rtl"
                                alt="founder image" />
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title mb-1">{{ trans('layouts/footer.founderName') }}</h5>
                            <p class="card-text mb-3">{{ trans('landing.team.memberRole') }}</p>
                            <div class="text-center team-media-icons">
                                <a href="https://www.instagram.com/bwazik/" class="text-heading" target="_blank">
                                    <i class="tf-icons ri-instagram-line ri-22px me-1"></i>
                                </a>
                                <a href="https://wa.me/+201098617164" class="text-heading" target="_blank">
                                    <i class="tf-icons ri-whatsapp-line ri-22px me-1"></i>
                                </a>
                                <a href="https://github.com/bwazik" class="text-heading" target="_blank">
                                    <i class="tf-icons ri-github-fill ri-22px me-1"></i>
                                </a>
                                <a href="https://www.linkedin.com/in/bazoka/" class="text-heading" target="_blank">
                                    <i class="tf-icons ri-linkedin-box-fill ri-22px"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Our great team: End -->

    <!-- Fun facts: Start -->
    <section id="landingFunFacts" class="section-py landing-fun-facts py-12 my-4">
        <div class="container">
            <div class="row gx-0 gy-6 gx-sm-6">
                <div class="col-md-3 col-sm-6 text-center">
                    <span class="badge rounded-pill bg-label-hover-primary fun-facts-icon mb-6 p-5"><i
                            class="tf-icons ri-presentation-line ri-42px"></i></span>
                    <h2 class="fw-bold mb-0 fun-facts-text">{{ $metrics['teachers'] }}+</h2>
                    <h6 class="mb-0 text-body">{{ trans('admin/teachers.teachers') }}</h6>
                </div>
                <div class="col-md-3 col-sm-6 text-center">
                    <span class="badge rounded-pill bg-label-hover-success fun-facts-icon mb-6 p-5"><i
                            class="tf-icons ri-graduation-cap-line ri-42px"></i></span>
                    <h2 class="fw-bold mb-0 fun-facts-text">{{ $metrics['students'] }}+</h2>
                    <h6 class="mb-0 text-body">{{ trans('admin/students.students') }}</h6>
                </div>
                <div class="col-md-3 col-sm-6 text-center">
                    <span class="badge rounded-pill bg-label-hover-warning fun-facts-icon mb-6 p-5"><i
                            class="tf-icons ri-pencil-ruler-line ri-42px"></i></span>
                    <h2 class="fw-bold mb-0 fun-facts-text">{{ $metrics['lessons'] }}+</h2>
                    <h6 class="mb-0 text-body">{{ trans('admin/lessons.lessons') }}</h6>
                </div>
                <div class="col-md-3 col-sm-6 text-center">
                    <span class="badge rounded-pill bg-label-hover-info fun-facts-icon mb-6 p-5"><i
                            class="tf-icons ri-group-2-line ri-42px"></i></span>
                    <h2 class="fw-bold mb-0 fun-facts-text">{{ $metrics['groups'] }}+</h2>
                    <h6 class="mb-0 text-body">{{ trans('admin/groups.groups') }}</h6>
                </div>
            </div>
        </div>
    </section>
    <!-- Fun facts: End -->

    <!-- FAQ: Start -->
    <section id="landingFAQ" class="section-py bg-body landing-faq">
        <div class="container bg-icon-right">
            <img src="{{ asset('assets/img/front-pages/icons/bg-right-icon-light.png') }}" alt="section icon"
                class="position-absolute top-0 end-0" data-speed="1"
                data-app-light-img="front-pages/icons/bg-right-icon-light.png"
                data-app-dark-img="front-pages/icons/bg-right-icon-dark.png" />

            <h6 class="text-center d-flex justify-content-center align-items-center mb-6">
                <img src="{{ asset('assets/img/front-pages/icons/section-tilte-icon.png') }}" alt="section title icon"
                    class="me-3" />
                <span class="text-uppercase">faq</span>
            </h6>
            <h5 class="text-center mb-2">Frequently asked<span class="display-5 fs-4 fw-bold"> questions</span></h5>
            <p class="text-center fw-medium mb-4 mb-md-12 pb-4">
                Browse through these FAQs to find answers to commonly asked questions.
            </p>
            <div class="row gy-5">
                <div class="col-lg-5">
                    <div class="text-center">
                        <img src="{{ asset('assets/img/front-pages/landing-page/sitting-girl-with-laptop.png') }}"
                            alt="sitting girl with laptop" class="faq-image scaleX-n1-rtl" />
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="accordion" id="accordionFront">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="head-One">
                                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                                    data-bs-target="#accordionOne" aria-expanded="true" aria-controls="accordionOne">
                                    Do you charge for each upgrade?
                                </button>
                            </h2>

                            <div id="accordionOne" class="accordion-collapse collapse" data-bs-parent="#accordionFront"
                                aria-labelledby="accordionOne">
                                <div class="accordion-body">
                                    Lemon drops chocolate cake gummies carrot cake chupa chups muffin topping. Sesame
                                    snaps icing
                                    marzipan gummi bears macaroon dragée danish caramels powder. Bear claw dragée
                                    pastry topping
                                    soufflé. Wafer gummi bears marshmallow pastry pie.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item previous-active">
                            <h2 class="accordion-header" id="head-Two">
                                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                                    data-bs-target="#accordionTwo" aria-expanded="false" aria-controls="accordionTwo">
                                    Do I need to purchase a license for each website?
                                </button>
                            </h2>
                            <div id="accordionTwo" class="accordion-collapse collapse" aria-labelledby="accordionTwo"
                                data-bs-parent="#accordionFront">
                                <div class="accordion-body">
                                    Dessert ice cream donut oat cake jelly-o pie sugar plum cheesecake. Bear claw
                                    dragée oat cake
                                    dragée ice cream halvah tootsie roll. Danish cake oat cake pie macaroon tart donut
                                    gummies. Jelly
                                    beans candy canes carrot cake. Fruitcake chocolate chupa chups.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item active">
                            <h2 class="accordion-header" id="head-Three">
                                <button type="button" class="accordion-button" data-bs-toggle="collapse"
                                    data-bs-target="#accordionThree" aria-expanded="true" aria-controls="accordionThree">
                                    What is regular license?
                                </button>
                            </h2>
                            <div id="accordionThree" class="accordion-collapse collapse show"
                                aria-labelledby="accordionThree" data-bs-parent="#accordionFront">
                                <div class="accordion-body">
                                    Regular license can be used for end products that do not charge users for access
                                    or service(access
                                    is free and there will be no monthly subscription fee). Single regular license can
                                    be used for
                                    single end product and end product can be used by you or your client. If you want
                                    to sell end
                                    product to multiple clients then you will need to purchase separate license for
                                    each client. The
                                    same rule applies if you want to use the same end product on multiple
                                    domains(unique setup). For
                                    more info on regular license you can check official description.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="head-Four">
                                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                                    data-bs-target="#accordionFour" aria-expanded="false" aria-controls="accordionFour">
                                    What is extended license?
                                </button>
                            </h2>
                            <div id="accordionFour" class="accordion-collapse collapse" aria-labelledby="accordionFour"
                                data-bs-parent="#accordionFront">
                                <div class="accordion-body">
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Nobis et aliquid quaerat
                                    possimus maxime!
                                    Mollitia reprehenderit neque repellat deleniti delectus architecto dolorum maxime,
                                    blanditiis
                                    earum ea, incidunt quam possimus cumque.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="head-Five">
                                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                                    data-bs-target="#accordionFive" aria-expanded="false" aria-controls="accordionFive">
                                    Which license is applicable for SASS application?
                                </button>
                            </h2>
                            <div id="accordionFive" class="accordion-collapse collapse" aria-labelledby="accordionFive"
                                data-bs-parent="#accordionFront">
                                <div class="accordion-body">
                                    Lorem ipsum dolor sit amet consectetur, adipisicing elit. Sequi molestias
                                    exercitationem ab cum
                                    nemo facere voluptates veritatis quia, eveniet veniam at et repudiandae mollitia
                                    ipsam quasi
                                    labore enim architecto non!
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- FAQ: End -->

    <!-- CTA: Start -->
    <section id="landingCTA" class="section-py landing-cta p-lg-0 pb-0 position-relative">
        <img src="{{ asset('assets/img/front-pages/backgrounds/cta-bg.png') }}"
            class="position-absolute bottom-0 end-0 scaleX-n1-rtl h-100 w-100 z-n1" alt="cta image" />
        <div class="container">
            <div class="row align-items-center gy-5 gy-lg-0">
                <div class="col-lg-6 text-center text-lg-start">
                    <h3 class="display-5 text-primary fw-bold mb-1 h3">{{ trans('landing.cta.title') }}</h3>
                    <p class="fw-medium mb-6 mb-md-8">{{ trans('landing.cta.subtitle') }}</p>
                    <a href="{{ route('login.choose') }}" class="btn btn-primary">{{ trans('landing.cta.button') }}<i
                            class="ri-arrow-right-line ri-16px ms-2 scaleX-n1-rtl"></i></a>
                </div>
                <div class="col-lg-6 pt-lg-12">
                    <img src="{{ asset('assets/img/front-pages/landing-page/cta-dashboard.png') }}" alt="cta dashboard"
                        class="img-fluid" />
                </div>
            </div>
        </div>
    </section>
    <!-- CTA: End -->

    <!-- Contact Us: Start -->
    <section id="landingContact" class="section-py bg-body landing-contact">
        <div class="container bg-icon-left position-relative">
            <img src="{{ asset('assets/img/front-pages/icons/bg-left-icon-light.png') }}" alt="section icon"
                class="position-absolute top-0 start-0" data-speed="1"
                data-app-light-img="front-pages/icons/bg-left-icon-light.png"
                data-app-dark-img="front-pages/icons/bg-left-icon-dark.png" />
            <h6 class="text-center d-flex justify-content-center align-items-center mb-6">
                <img src="{{ asset('assets/img/front-pages/icons/section-tilte-icon.png') }}" alt="section title icon"
                    class="me-3" />
                <span class="text-uppercase">{{ trans('landing.contact.title') }}</span>
            </h6>
            <p class="text-center fw-medium mb-4 mb-md-12 pb-3">{{ trans('landing.contact.subtitle') }}</p>
            <div class="row gy-6">
                <div class="col-lg-5">
                    <div class="card h-100">
                        <div class="bg-primary rounded-4 text-white card-body p-8">
                            <p class="fw-medium mb-1_5 tagline">{{ trans('landing.contact.cardTagline') }}</p>
                            <h4 class="text-white mb-5 title">{{ trans('landing.contact.cardTitle') }}</h4>
                            <img src="{{ asset('assets/img/illustrations/auth-cover-login-mask-dark.png') }}"
                                alt="let’s contact" class="w-100 mb-5" />
                            <p class="mb-0 description">{{ trans('landing.contact.cardDescription') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="card">
                        <div class="card-body">
                            <form id="contact-form" action="{{ route('landing.contact') }}" method="POST" autocomplete="off">
                                @csrf
                                <div class="row g-5">
                                    <x-basic-input context="modal" type="text" name="name" label="{{ trans('main.realName_ar') }}" placeholder="{{ trans('main.placeholders.realName') }}" required />
                                    <x-basic-input context="modal" type="number" name="phone" label="{{ trans('main.phone') }}" placeholder="{{ trans('admin/teachers.placeholders.phone') }}" required />
                                    <div class="col-sm-12">
                                        <div class="form-floating form-floating-outline">
                                            <textarea id="message" class="form-control h-px-250" name="message" required placeholder="{{ trans('main.placeholders.message') }}" aria-label="{{ trans('main.placeholders.message') }}" maxlength="255"></textarea>
                                            <label for="message">{{ trans('landing.contact.message') }}</label>
                                        </div>
                                        <span class="invalid-feedback" id="message_error" role="alert"></span>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary mt-5 waves-effect waves-light">{{ trans('landing.contact.button') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Contact Us: End -->
@endsection

@section('page-js')
    <script src="{{ asset('assets/js/front-page-landing.js') }}"></script>

    <script>
        let fields = ['name', 'phone', 'message'];
        handleFormSubmit('#contact-form', fields);
    </script>
@endsection
