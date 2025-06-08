{{-- layout.blade.php --}}
@extends('layouts.base.index')

@section('html-classes', 'light-style layout-wide customizer-hide')
@section('data-template', 'vertical-menu-template')

@section('head')
    @include('layouts.auth.head')
@endsection

@section('body')
    <!-- Sections:Start -->
    <div class="position-relative">
        <div class="authentication-wrapper authentication-basic container-p-y p-4 p-sm-0">
            <div class="authentication-inner py-6">
                <!-- Card -->
                <div class="card p-md-7 p-1">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center mt-5">
                        <a href="{{ route('landing') }}" class="app-brand-link gap-2">
                            <span class="app-brand-logo demo">
                                <img width="80" height="80" src="{{ asset('assets/img/brand/navbar.png') }}"
                                    alt="Shattor">
                            </span>
                            <span
                                class="app-brand-text demo text-heading fw-semibold">{{ trans('layouts/sidebar.platformName') }}</span>
                        </a>
                    </div>
                    <!-- /Logo -->
                    <div class="card-body mt-1">
                        @yield('content')

                        <div class="divider my-5">
                            <div class="divider-text">{{ trans('layouts/login.or_divider_text') }}</div>
                        </div>

                        <div class="d-flex justify-content-center align-items-center gap-2 mb-4">
                            {{-- Conditional Back Button --}}
                            @if (isActiveRoute('login.choose'))
                                <a href="{{ route('landing') }}"
                                    class="btn btn-sm btn-outline-secondary waves-effect waves-light text-nowrap"
                                    aria-label="{{ trans('layouts/auth.back_to_landing') }}">
                                    <i class="ri-arrow-left-line me-1"></i>
                                    <span>{{ trans('layouts/login.back_to_landing') }}</span>
                                </a>
                            @elseif (isActiveRoute('login'))
                                <a href="{{ route('login.choose') }}"
                                    class="btn btn-sm btn-outline-secondary waves-effect waves-light text-nowrap"
                                    aria-label="{{ trans('layouts/auth.back_to_selection') }}">
                                    <i class="ri-arrow-left-line me-1"></i>
                                    <span>{{ trans('layouts/login.back_to_selection') }}</span>
                                </a>
                            @endif
                            {{-- /Conditional Back Button --}}

                            {{-- Language Switcher --}}
                            @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                @if (App::getLocale() != $localeCode)
                                    <a href="{{ LaravelLocalization::getLocalizedURL($localeCode) }}"
                                        class="btn btn-sm btn-outline-secondary waves-effect waves-light"
                                        data-bs-toggle="tooltip" data-bs-original-title="{{ trans('layouts/navbar.language') }}">
                                        <i class="ri-global-line me-1"></i>
                                        <span>{{ $properties['native'] }}</span>
                                    </a>
                                    @break
                                @endif
                            @endforeach
                            {{-- <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle waves-effect waves-light" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ri-global-line me-1"></i>
                                    <span>{{ LaravelLocalization::getCurrentLocaleNative() }}</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                                    @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                        <li>
                                            <a class="dropdown-item @if (App::getLocale() == $localeCode) active @endif" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                                <span class="align-middle">{{ $properties['native'] }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div> --}}
                            {{-- /Language Switcher --}}
                        </div>
                    </div>
                </div>
                <!-- /Card -->
                <img alt="mask" src="{{ asset('assets/img/illustrations/auth-basic-login-mask-light.png') }}"
                    class="authentication-image d-none d-lg-block"
                    data-app-light-img="illustrations/auth-basic-login-mask-light.png"
                    data-app-dark-img="illustrations/auth-basic-login-mask-dark.png" />
            </div>
        </div>
    </div>
    <!-- / Sections:End -->

    <div class="buy-now">
        <a href="https://wa.me/+201098617164" target="_blank" class="btn btn-success btn-buy-now waves-effect waves-light">
            <i class="ri-whatsapp-line me-1"></i>{{ trans('layouts/login.whatsapp') }}</a>
    </div>
@endsection

@section('scripts')
    @include('layouts.auth.scripts')
@endsection
