@extends('layouts.base.index')

@section('html-classes', 'light-style layout-wide customizer-hide')
@section('data-template', 'vertical-menu-template')

@section('head')
    @include('layouts.admin.head')
@endsection

@section('body')
    @yield('content')
@endsection

@section('scripts')
    @include('layouts.admin.scripts')
@endsection
