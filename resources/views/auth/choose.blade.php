@extends('layouts.auth.master')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />
@endsection

@section('title', 'Choose')

@section('content')
    <h4 class="mb-1">Welcome to Materialize! 👋</h4>
    <p class="mb-5">Please choose your login way to start the adventure</p>

    <div class="row justify-content-center">
        <!-- Teacher Login -->
        <div class="col-6 mb-4">
            <a href="{{ route('login', 'teacher') }}" class="card text-center text-decoration-none">
                <div class="card-body">
                    <i class="tf-icons ri-presentation-line ri-2x mb-3"></i>
                    <h5 class="card-title">Teacher</h5>
                </div>
            </a>
        </div>

        <!-- Assistant Login -->
        <div class="col-6 mb-4">
            <a href="{{ route('login', 'assistant') }}" class="card text-center text-decoration-none">
                <div class="card-body">
                    <i class="tf-icons ri-user-star-line ri-2x mb-3"></i>
                    <h5 class="card-title">Assistant</h5>
                </div>
            </a>
        </div>

        <!-- Student Login -->
        <div class="col-6 mb-4">
            <a href="{{ route('login', 'student') }}" class="card text-center text-decoration-none">
                <div class="card-body">
                    <i class="tf-icons ri-graduation-cap-line ri-2x mb-3"></i>
                    <h5 class="card-title">Student</h5>
                </div>
            </a>
        </div>

        <!-- Parent Login -->
        <div class="col-6 mb-4">
            <a href="{{ route('login', 'parent') }}" class="card text-center text-decoration-none">
                <div class="card-body">
                    <i class="tf-icons ri-parent-line ri-2x mb-3"></i>
                    <h5 class="card-title">Parent</h5>
                </div>
            </a>
        </div>
    </div>

    <p class="text-center mt-5">
        <span>New on our platform?</span>
        <a href="auth-register-basic.html">
            <span>Create an account</span>
        </a>
    </p>
@endsection

@section('page-js')
    <script src="{{ asset('assets/js/pages-auth.js') }}"></script>
@endsection
