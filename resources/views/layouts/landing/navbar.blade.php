<nav class="layout-navbar container shadow-none py-0">
    <div class="navbar navbar-expand-lg landing-navbar border-top-0 px-4 px-md-8">
        <!-- Menu logo wrapper: Start -->
        <div class="navbar-brand app-brand demo d-flex py-0 py-lg-2 me-6">
            <!-- Mobile menu toggle: Start-->
            <button class="navbar-toggler border-0 px-0 me-2" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <i class="tf-icons ri-menu-fill ri-24px align-middle"></i>
            </button>
            <!-- Mobile menu toggle: End-->
            <a href="{{ route('landing') }}" class="app-brand-link">
                <span class="app-brand-logo demo">
                    <img width="40" height="40" src="{{ asset('assets/img/brand/navbar.png') }}" alt="Shattor">
                </span>
                <span class="app-brand-text demo menu-text fw-semibold ms-2">{{ trans('layouts/sidebar.platformName') }}</span>
            </a>
        </div>
        <!-- Menu logo wrapper: End -->
        <!-- Menu wrapper: Start -->
        <div class="collapse navbar-collapse landing-nav-menu" id="navbarSupportedContent">
            <button class="navbar-toggler border-0 text-heading position-absolute end-0 top-0 scaleX-n1-rtl"
                type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="tf-icons ri-close-fill"></i>
            </button>
            <ul class="navbar-nav me-auto p-4 p-lg-0">
                <li class="nav-item">
                    <a class="nav-link fw-medium" aria-current="page" href="{{ route('landing') }}#landingHero">{{ trans('landing.navbar.home') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-medium" href="{{ route('landing') }}#landingFeatures">{{ trans('landing.navbar.features') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-medium" href="{{ route('landing') }}#landingTeam">{{ trans('landing.navbar.team') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-medium" href="{{ route('landing') }}#landingFAQ">{{ trans('landing.navbar.faq') }}</a>
                </li>
                @if(isAdmin() || isTeacher() || isAssistant() || isStudent() || isParent())
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href={{ getHelpCenterRoute() }}>{{ trans('landing.navbar.helpCenter') }}</a>
                    </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link fw-medium text-nowrap" href="{{ route('landing') }}#landingContact">{{ trans('landing.navbar.contact') }}</a>
                </li>
            </ul>
        </div>
        <div class="landing-menu-overlay d-lg-none"></div>
        <!-- Menu wrapper: End -->
        <!-- Toolbar: Start -->
        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <!-- Language -->
            <li class="nav-item dropdown me-xl-0">
                @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                    @if (App::getLocale() != $localeCode)
                        <a class="nav-link btn btn-text-secondary rounded-pill btn-icon hide-arrow"
                            href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                            data-bs-toggle="tooltip" data-bs-original-title="{{ trans('layouts/navbar.language') }}">
                            <i class="ri-global-line ri-22px"></i>
                            <span class="visually-hidden">{{ $properties['native'] }}</span>
                        </a>
                        @break
                    @endif
                @endforeach
            </li>
            <!--/ Language -->

            <!-- Style Switcher -->
            <li class="nav-item dropdown-style-switcher dropdown me-2 me-xl-0">
                <a class="nav-link btn btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow me-sm-4"
                    href="javascript:void(0);" data-bs-toggle="dropdown">
                    <i class="ri-22px text-heading"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-styles">
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" data-theme="light">
                            <span class="align-middle"><i class="ri-sun-line ri-22px me-3"></i>{{ trans('landing.navbar.light') }}</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" data-theme="dark">
                            <span class="align-middle"><i class="ri-moon-clear-line ri-22px me-3"></i>{{ trans('landing.navbar.dark') }}</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" data-theme="system">
                            <span class="align-middle"><i class="ri-computer-line ri-22px me-3"></i>{{ trans('landing.navbar.system') }}</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- / Style Switcher-->

            <!-- navbar button: Start -->
            <li>
                @if(isAdmin() || isTeacher() || isAssistant() || isStudent() || isParent())
                    <a href="{{ getDashboardRoute() }}" class="btn btn-primary px-2 px-sm-4 px-lg-2 px-xl-4">
                        <span class="tf-icons ri-user-line me-md-1"></span>
                        <span class="d-none d-md-block">{{ trans('landing.navbar.dashboard') }}</span>
                    </a>
                @else
                    <a href="{{ route('login.choose') }}" class="btn btn-primary px-2 px-sm-4 px-lg-2 px-xl-4">
                        <span class="tf-icons ri-user-line me-md-1"></span>
                        <span class="d-none d-md-block">{{ trans('landing.navbar.login') }}</span>
                    </a>
                @endif
            </li>
            <!-- navbar button: End -->
        </ul>
        <!-- Toolbar: End -->
    </div>
</nav>
