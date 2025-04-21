<!DOCTYPE html>

<html
    lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"
    class="@yield('html-classes')" data-theme="theme-default" data-assets-path="{{ asset('assets') }}/"
    data-template="@yield('data-template')" data-style="light">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="description"
        content="Shattor Platform - Empowering teachers with tools to manage students and create engaging educational experiences." />
    <meta name="author" content="Abdullah Mohamed (Bazoka), Developer and Founder of Shattor Platform" />
    <meta name="keywords" content="Shattor, education platform, teacher tools, student management, online learning" />
    <!-- Title -->
    <title>@yield("title")</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@100..900&display=swap" rel="stylesheet">
    <!-- Vendor Fonts -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/remixicon/remixicon.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/flag-icons.css') }}" />
    <!-- Menu waves for no-customizer fix -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />
    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    @yield('head')
    <style>
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Hide arrows in Firefox */
        input[type="number"] {
            -moz-appearance: textfield;
        }

        /* Hide arrows in other browsers */
        input[type="number"] {
            appearance: textfield;
        }

        textarea {
            resize: none;
        }
    </style>
</head>

<body>
    @yield('body')

    @yield('scripts')
</body>

</html>
