@extends('errors.master')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-misc.css') }}" />
@endsection

@section('title', trans('errors.419.heading'))

@section('content')
    <!-- Error -->
    <div class="misc-wrapper">
        <h1 class="mb-2 mx-2" style="font-size: 6rem; line-height: 6rem">419</h1>
        <h4 class="mb-2">{{ trans('errors.419.heading') }}</h4>
        <p class="mb-6 mx-2">{{ trans('errors.419.description') }}</p>
        <div class="d-flex justify-content-center mt-9">
            <img src="{{ asset('assets/img/illustrations/misc-error-object.png') }}" alt="misc-error"
                class="img-fluid misc-object d-none d-lg-inline-block" width="160" />
            <img src="{{ asset('assets/img/illustrations/misc-bg-light.png') }}" alt="misc-error"
                class="misc-bg d-none d-lg-inline-block" data-app-light-img="illustrations/misc-bg-light.png"
                data-app-dark-img="illustrations/misc-bg-dark.png" />
            <div class="d-flex flex-column align-items-center">
                <img src="{{ asset('assets/img/illustrations/misc-error-illustration.png') }}" alt="misc-error"
                    class="img-fluid z-1" width="190" />
                <div>
                    <a href="{{ getDashboardRoute() }}"
                        class="btn btn-primary text-center my-10">{{ trans('errors.button') }}</a>
                </div>
            </div>
        </div>
    </div>
    <!-- /Error -->
@endsection

@section('page-js')

@endsection
