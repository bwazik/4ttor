@extends('errors.master')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-misc.css') }}" />
@endsection

@section('title', pageTitle('errors.419.heading'))

@section('content')
    <!-- Server Error -->
    <div class="misc-wrapper">
        <h1 class="mb-2 mx-2" style="font-size: 6rem; line-height: 6rem">429</h1>
        <h4 class="mb-2">{{ trans('errors.429.heading') }}</h4>
        <p class="mb-3 mx-2">{{ trans('errors.429.description') }}</p>
        <div class="d-flex justify-content-center mt-12">
            <img src="{{ asset('assets/img/illustrations/misc-error-object.png') }}" alt="misc-server-error"
                class="img-fluid misc-object d-none d-lg-inline-block" width="160" />
            <img src="{{ asset('assets/img/illustrations/misc-bg-light.png') }}" alt="misc-server-error"
                class="misc-bg d-none d-lg-inline-block z-n1" data-app-light-img="illustrations/misc-bg-light.png"
                data-app-dark-img="illustrations/misc-bg-dark.png" />
            <div class="d-flex flex-column align-items-center">
                <img src="{{ asset('assets/img/illustrations/misc-server-error-illustration.png') }}"
                    alt="misc-server-error" class="img-fluid z-1" width="230" />
                <div>
                    <a href="{{ getDashboardRoute() }}"
                        class="btn btn-primary text-center my-10">{{ trans('errors.button') }}</a>
                </div>
            </div>
        </div>
    </div>
    <!-- /Server Error -->
@endsection

@section('page-js')

@endsection
