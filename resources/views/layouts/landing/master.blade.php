@extends('layouts.base.index')

@section('html-classes', 'light-style layout-navbar-fixed layout-wide')
@section('data-template', 'front-pages')

@section('head')
    @include('layouts.landing.head')
@endsection

@section('body')
    <script src="{{ asset('assets/vendor/js/dropdown-hover.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/mega-dropdown.js') }}"></script>

    <!-- Navbar: Start -->
    @include('layouts.landing.navbar')
    <!-- Navbar: End -->

    <!-- Sections:Start -->
    <div data-bs-spy="scroll" class="scrollspy-example">
        @yield('content')
    </div>
    <!-- / Sections:End -->

    <!-- Footer: Start -->
    @include('layouts.base.footer')
    <!-- Footer: End -->
@endsection

@section('scripts')
    @include('layouts.landing.scripts')
@endsection
