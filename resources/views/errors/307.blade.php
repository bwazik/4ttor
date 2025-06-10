@extends('errors.master')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-misc.css') }}" />
@endsection

@section('title', trans('errors.307.heading'))

@section('content')
    <!-- We are Coming soon -->
    <div class="misc-wrapper">
        <h4 class="mb-2 mx-2">{{ trans('errors.307.heading') }}</h4>
        <p class="mb-6 mx-2">{{ trans('errors.307.description') }}</p>
        <div class="d-flex justify-content-center mt-9">
            <img src="{{ asset('assets/img/illustrations/misc-coming-soon-object.png') }}" alt="misc-coming-soon"
                class="img-fluid misc-object d-none d-lg-inline-block" width="170" />
            <img src="{{ asset('assets/img/illustrations/misc-bg-light.png') }}" alt="misc-coming-soon"
                class="misc-bg d-none d-lg-inline-block" data-app-light-img="illustrations/misc-bg-light.png"
                data-app-dark-img="illustrations/misc-bg-dark.png" />
            <img src="{{ asset('assets/img/illustrations/misc-coming-soon-illustration.png') }}" alt="misc-coming-soon"
                class="img-fluid z-1" width="190" />
        </div>
        <div class="d-flex flex-column align-items-center">
            <div>
                <a href="{{ getDashboardRoute() }}"
                    class="btn btn-primary text-center my-10">{{ trans('errors.button') }}</a>
            </div>
        </div>
    </div>
    <!-- /We are Coming soon -->
@endsection

@section('page-js')

@endsection
