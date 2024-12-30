@extends('layouts.auth.master')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />
@endsection

@section('title', 'login')

@section('content')
    <h4 class="mb-1">Welcome to Materialize! 👋</h4>
    <p class="mb-5">Please sign-in to your account and start the adventure</p>

    <form id="loginForm" class="mb-5" method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-floating form-floating-outline mb-5">
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                placeholder="Enter your email" autofocus />
            <label for="email">Email</label>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-5">
            <div class="form-password-toggle">
                <div class="input-group input-group-merge">
                    <div class="form-floating form-floating-outline">
                        <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password"
                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                            aria-describedby="password" />
                        <label for="password">Password</label>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <span class="input-group-text cursor-pointer"><i class="ri-eye-off-line"></i></span>
                </div>
            </div>
        </div>
        <div class="mb-5 d-flex justify-content-between mt-5">
            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" id="remember" name="remember"/>
                <label class="form-check-label" for="remember"> Remember Me </label>
            </div>
            <a href="auth-forgot-password-basic.html" class="float-end mb-1 mt-2">
                <span>Forgot Password?</span>
            </a>
        </div>
        <div class="mb-5">
            <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
        </div>
    </form>

    <p class="text-center">
        <span>New on our platform?</span>
        <a href="auth-register-basic.html">
            <span>Create an account</span>
        </a>
    </p>
@endsection

@section('page-js')
    <script src="{{ asset('assets/js/pages-auth.js') }}"></script>
@endsection
