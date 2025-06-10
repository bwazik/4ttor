@extends('layouts.landing.master')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/front-page-landing.css') }}" />
@endsection

@section('title', 'Landing Page')

@section('content')
    <!-- Hero: Start -->
    <section id="landingHero" class="section-py landing-hero position-relative">
        <img src="{{ asset('assets/img/front-pages/backgrounds/hero-bg-light.png') }}" alt="hero background"
            class="position-absolute top-0 start-0 w-100 h-100 z-n1" data-speed="1"
            data-app-light-img="front-pages/backgrounds/hero-bg-light.png"
            data-app-dark-img="front-pages/backgrounds/hero-bg-dark.png" />
        <div class="container">
            <div class="hero-text-box text-center">
                <h3 class="text-primary hero-title fs-2">All in one sass application for your business</h3>
                <h2 class="h6 mb-8">
                    No coding required to make customisations.<br />The live customiser has everything your marketing
                    need.
                </h2>
                <a href="#landingPricing" class="btn btn-lg btn-primary">Get early access</a>
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
                <span class="text-uppercase">Useful features</span>
            </h6>
            <h5 class="text-center mb-2">
                <span class="display-5 fs-4 fw-bold">Everything you need</span> to start your next project
            </h5>
            <p class="text-center fw-medium mb-4 mb-md-12">
                Not just a set of tools, the package includes ready-to-deploy conceptual application.
            </p>
            <div class="features-icon-wrapper row gx-0 gy-12 gx-sm-6 mt-n4 mt-sm-0">
                <div class="col-lg-4 col-sm-6 text-center features-icon-box">
                    <div class="features-icon mb-4">
                        <img src="{{ asset('assets/img/front-pages/icons/laptop-charging.png') }}" alt="laptop charging" />
                    </div>
                    <h5 class="mb-2">Quality Code</h5>
                    <p class="features-icon-description">
                        Code structure that all developers will easily understand and fall in love with.
                    </p>
                </div>
                <div class="col-lg-4 col-sm-6 text-center features-icon-box">
                    <div class="features-icon mb-4">
                        <img src="{{ asset('assets/img/front-pages/icons/transition-up.png') }}" alt="transition up" />
                    </div>
                    <h5 class="mb-2">Continuous Updates</h5>
                    <p class="features-icon-description">
                        Free updates for the next 12 months, including new demos and features.
                    </p>
                </div>
                <div class="col-lg-4 col-sm-6 text-center features-icon-box">
                    <div class="features-icon mb-4">
                        <img src="{{ asset('assets/img/front-pages/icons/edit.png') }}" alt="edit" />
                    </div>
                    <h5 class="mb-2">Stater-Kit</h5>
                    <p class="features-icon-description">
                        Start your project quickly without having to remove unnecessary features.
                    </p>
                </div>
                <div class="col-lg-4 col-sm-6 text-center features-icon-box">
                    <div class="features-icon mb-4">
                        <img src="{{ asset('assets/img/front-pages/icons/3d-select-solid.png') }}" alt="3d select solid" />
                    </div>
                    <h5 class="mb-2">API Ready</h5>
                    <p class="features-icon-description">
                        Just change the endpoint and see your own data loaded within seconds.
                    </p>
                </div>
                <div class="col-lg-4 col-sm-6 text-center features-icon-box">
                    <div class="features-icon mb-4">
                        <img src="{{ asset('assets/img/front-pages/icons/lifebelt.png') }}" alt="lifebelt" />
                    </div>
                    <h5 class="mb-2">Excellent Support</h5>
                    <p class="features-icon-description">An easy-to-follow doc with lots of references and code examples.
                    </p>
                </div>
                <div class="col-lg-4 col-sm-6 text-center features-icon-box">
                    <div class="features-icon mb-4">
                        <img src="{{ asset('assets/img/front-pages/icons/google-docs.png') }}" alt="google docs" />
                    </div>
                    <h5 class="mb-2">Well Documented</h5>
                    <p class="features-icon-description">An easy-to-follow doc with lots of references and code examples.
                    </p>
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
                <span class="text-uppercase">our great team</span>
            </h6>
            <h5 class="text-center mb-2"><span class="display-5 fs-4 fw-bold">Supported</span> by Real People</h5>
            <p class="text-center fw-medium mb-4 mb-md-12 pb-7">Who is behind these great-looking interfaces?</p>
            <div class="row gy-lg-5 gy-12 mt-2">
                <div class="col-lg-3 col-sm-6">
                    <div class="card card-hover-border-primary mt-4 mt-lg-0 shadow-none">
                        <div class="bg-label-primary position-relative team-image-box">
                            <img src="{{ asset('assets/img/front-pages/landing-page/team-member-1.png') }}"
                                class="position-absolute card-img-position bottom-0 start-50 scaleX-n1-rtl"
                                alt="human image" />
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title mb-1">Sophie Gilbert</h5>
                            <p class="card-text mb-3">Project Manager</p>
                            <div class="text-center team-media-icons">
                                <a href="javascript:void(0);" class="text-heading" target="_blank">
                                    <i class="tf-icons ri-facebook-circle-line ri-22px me-1"></i>
                                </a>
                                <a href="javascript:void(0);" class="text-heading" target="_blank">
                                    <i class="tf-icons ri-twitter-line ri-22px me-1"></i>
                                </a>
                                <a href="javascript:void(0);" class="text-heading" target="_blank">
                                    <i class="tf-icons ri-linkedin-box-line ri-22px"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card card-hover-border-danger mt-4 mt-lg-0 shadow-none">
                        <div class="bg-label-danger position-relative team-image-box">
                            <img src="{{ asset('assets/img/front-pages/landing-page/team-member-2.png') }}"
                                class="position-absolute card-img-position bottom-0 start-50 scaleX-n1-rtl"
                                alt="human image" />
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title mb-1">Nannie Ford</h5>
                            <p class="card-text mb-3">Development Lead</p>
                            <div class="text-center team-media-icons">
                                <a href="javascript:void(0);" class="text-heading" target="_blank">
                                    <i class="tf-icons ri-facebook-circle-line ri-22px me-1"></i>
                                </a>
                                <a href="javascript:void(0);" class="text-heading" target="_blank">
                                    <i class="tf-icons ri-twitter-line ri-22px me-1"></i>
                                </a>
                                <a href="javascript:void(0);" class="text-heading" target="_blank">
                                    <i class="tf-icons ri-linkedin-box-line ri-22px"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card card-hover-border-success mt-4 mt-lg-0 shadow-none">
                        <div class="bg-label-success position-relative team-image-box">
                            <img src="{{ asset('assets/img/front-pages/landing-page/team-member-3.png') }}"
                                class="position-absolute card-img-position bottom-0 start-50 scaleX-n1-rtl"
                                alt="human image" />
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title mb-1">Chris Watkins</h5>
                            <p class="card-text mb-3">Marketing Manager</p>
                            <div class="text-center team-media-icons">
                                <a href="javascript:void(0);" class="text-heading" target="_blank">
                                    <i class="tf-icons ri-facebook-circle-line ri-22px me-1"></i>
                                </a>
                                <a href="javascript:void(0);" class="text-heading" target="_blank">
                                    <i class="tf-icons ri-twitter-line ri-22px me-1"></i>
                                </a>
                                <a href="javascript:void(0);" class="text-heading" target="_blank">
                                    <i class="tf-icons ri-linkedin-box-line ri-22px"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card card-hover-border-info mt-4 mt-lg-0 shadow-none">
                        <div class="bg-label-info position-relative team-image-box">
                            <img src="{{ asset('assets/img/front-pages/landing-page/team-member-4.png') }}"
                                class="position-absolute card-img-position bottom-0 start-50 scaleX-n1-rtl"
                                alt="human image" />
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title mb-1">Paul Miles</h5>
                            <p class="card-text mb-3">UI Designer</p>
                            <div class="text-center team-media-icons">
                                <a href="javascript:void(0);" class="text-heading" target="_blank">
                                    <i class="tf-icons ri-facebook-circle-line ri-22px me-1"></i>
                                </a>
                                <a href="javascript:void(0);" class="text-heading" target="_blank">
                                    <i class="tf-icons ri-twitter-line ri-22px me-1"></i>
                                </a>
                                <a href="javascript:void(0);" class="text-heading" target="_blank">
                                    <i class="tf-icons ri-linkedin-box-line ri-22px"></i>
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
                            class="tf-icons ri-layout-line ri-42px"></i></span>
                    <h2 class="fw-bold mb-0 fun-facts-text">137+</h2>
                    <h6 class="mb-0 text-body">Completed Sites</h6>
                </div>
                <div class="col-md-3 col-sm-6 text-center">
                    <span class="badge rounded-pill bg-label-hover-success fun-facts-icon mb-6 p-5"><i
                            class="tf-icons ri-time-line ri-42px"></i></span>
                    <h2 class="fw-bold mb-0 fun-facts-text">1,100+</h2>
                    <h6 class="mb-0 text-body">Working Hours</h6>
                </div>
                <div class="col-md-3 col-sm-6 text-center">
                    <span class="badge rounded-pill bg-label-hover-warning fun-facts-icon mb-6 p-5"><i
                            class="tf-icons ri-user-smile-line ri-42px"></i></span>
                    <h2 class="fw-bold mb-0 fun-facts-text">137+</h2>
                    <h6 class="mb-0 text-body">Happy Customers</h6>
                </div>
                <div class="col-md-3 col-sm-6 text-center">
                    <span class="badge rounded-pill bg-label-hover-info fun-facts-icon mb-6 p-5"><i
                            class="tf-icons ri-award-line ri-42px"></i></span>
                    <h2 class="fw-bold mb-0 fun-facts-text">23+</h2>
                    <h6 class="mb-0 text-body">Awards Winning</h6>
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
                    <h3 class="display-5 text-primary fw-bold mb-1 h3">Ready to Get Started?</h3>
                    <p class="fw-medium mb-6 mb-md-8">Start your project with a 14-day free trial</p>
                    <a href="payment-page.html" class="btn btn-primary">Get Started<i
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
                <span class="text-uppercase">contact us</span>
            </h6>
            <h5 class="text-center mb-2"><span class="display-5 fs-4 fw-bold">Lets work</span> together</h5>
            <p class="text-center fw-medium mb-4 mb-md-12 pb-3">Any question or remark? just write us a message</p>
            <div class="row gy-6">
                <div class="col-lg-5">
                    <div class="card h-100">
                        <div class="bg-primary rounded-4 text-white card-body p-8">
                            <p class="fw-medium mb-1_5 tagline">Let’s contact with us</p>
                            <h4 class="text-white mb-5 title">Share your ideas or requirement with our experts.</h4>
                            <img src="{{ asset('assets/img/front-pages/landing-page/let’s-contact.png') }}"
                                alt="let’s contact" class="w-100 mb-5" />
                            <p class="mb-0 description">
                                Looking for more customisation, more features, and more anything? Don’t worry, We’ve
                                provide you
                                with an entire team of experienced professionals.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="mb-6">Share your ideas</h5>
                            <form>
                                <div class="row g-5">
                                    <div class="col-md-6">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" class="form-control" id="basic-default-fullname"
                                                placeholder="John Doe" />
                                            <label for="basic-default-fullname">Full name</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating form-floating-outline">
                                            <input type="email" class="form-control" id="basic-default-email"
                                                placeholder="johndoe99@gmail.com" />
                                            <label for="basic-default-email">Email address</label>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-floating form-floating-outline">
                                            <textarea class="form-control h-px-250" placeholder="Message" aria-label="Message" id="basic-default-message"></textarea>
                                            <label for="basic-default-message">Message</label>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary mt-5">Send inquiry</button>
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
@endsection
