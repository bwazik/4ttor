@extends('errors.master')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-misc.css') }}" />
@endsection

@section('title', trans('errors.401.heading'))

@section('content')
    <!-- Not Authorized -->
    <div class="misc-wrapper">
        <h1 class="mb-2 mx-2" style="font-size: 6rem; line-height: 6rem">401</h1>
        <h4 class="mb-2">{{ trans('errors.401.heading') }}</h4>
        <p class="mb-3 mx-2">{{ trans('errors.401.description') }}</p>
        <div class="d-flex justify-content-center mt-12">
            <img src="{{ asset('assets/img/illustrations/misc-not-authorized-object.png') }}" alt="misc-not-authorized"
                class="img-fluid misc-object d-none d-lg-inline-block" width="190" />
            <img src="{{ asset('assets/img/illustrations/misc-bg-light.png') }}" alt="misc-not-authorized"
                class="misc-bg d-none d-lg-inline-block" data-app-light-img="illustrations/misc-bg-light.png"
                data-app-dark-img="illustrations/misc-bg-dark.png" />
            <div class="d-flex flex-column align-items-center">
                <img src="{{ asset('assets/img/illustrations/misc-not-authorized-illustration.png') }}"
                    alt="misc-not-authorized" class="img-fluid z-1" width="160" />
                <div>
                    <a href="{{ getDashboardRoute() }}"
                        class="btn btn-primary text-center my-10">{{ trans('errors.button') }}</a>
                </div>
            </div>
        </div>
    </div>
    <!-- /Not Authorized -->
@endsection

@section('page-js')

@endsection
