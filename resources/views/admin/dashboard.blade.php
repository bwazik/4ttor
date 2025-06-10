@extends('layouts.admin.master')

@section('vendors-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/swiper/swiper.css') }}" />
@endsection

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/cards-statistics.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/cards-analytics.css') }}" />
@endsection

@section('title', pageTitle('layouts/sidebar.dashboard'))

@section('content')
    <div class="text-center">
        <h1>{{ trans('main.soon') }} 🚀</h1>
        <img src="{{ asset('assets/img/illustrations/misc-coming-soon-illustration.png') }}" alt="misc-coming-soon"
            class="img-fluid z-1" width="190" />
    </div>
@endsection

@section('vendors-js')
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/swiper/swiper.js') }}"></script>
@endsection

@section('page-js')
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>
@endsection
