@extends('errors.master')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-misc.css') }}" />
@endsection

@section('title', trans('errors.503.heading'))

@section('content')
    <!--Under Maintenance -->
    <div class="misc-wrapper">
        <h4 class="mb-2 mx-2">{{ trans('errors.503.heading') }}</h4>
        <p class="mb-6 mx-sm-2 text-center">{{ trans('errors.503.description') }}</p>
        <div class="d-flex justify-content-center mt-9">
            <img src="{{ asset('assets/img/illustrations/misc-under-maintenance-object.png') }}" alt="misc-under-maintenance"
                class="img-fluid misc-object d-none d-lg-inline-block" width="170" />
            <img src="{{ asset('assets/img/illustrations/misc-bg-light.png') }}" alt="misc-under-maintenance"
                class="misc-bg d-none d-lg-inline-block" data-app-light-img="illustrations/misc-bg-light.png"
                data-app-dark-img="illustrations/misc-bg-dark.png" />
            <div class="d-flex flex-column align-items-center">
                <img src="{{ asset('assets/img/illustrations/misc-under-maintenance-illustration.png') }}"
                    alt="misc-under-maintenance" class="img-fluid z-1" width="290" />
                <div>
                    <a href="{{ getDashboardRoute() }}"
                        class="btn btn-primary text-center my-10">{{ trans('errors.button') }}</a>
                </div>
            </div>
        </div>
    </div>
    <!-- /Under Maintenance -->
@endsection

@section('page-js')

@endsection
