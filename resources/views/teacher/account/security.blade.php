@extends('layouts.teacher.master')

@section('page-css')

@endsection

@section('title', pageTitle('account.security'))

@section('content')
    <div class="row">
        <div class="col-md-12">
            @include('teacher.account.navbar')
            <!-- Change Password -->
            <x-account.change-password action="{{ route('teacher.account.password.update') }}" />
            <!-- Change Password -->

            <!-- Zoom Account -->
            <div class="card mb-6">
                <h5 class="card-header mb-1">{{ trans('account.linkZoomAccount') }}</h5>
                <div class="row row-gap-1">
                    <div class="col-xl-5 col-md-7">
                        <div class="card-body">
                            <x-alert type="info" :dismissible=false icon="error-warning" :message="trans('account.zoomAccountAlert')"/>
                            <form id="zoom-account-form" action="{{ route('teacher.account.zoom.update') }}" method="POST" autocomplete="off">
                                @csrf
                                <div class="row gy-5">
                                    <x-basic-input context="offcanvas" type="text" name="accountId" label="{{ trans('account.accountId') }}" placeholder="{{ trans('account.placeholders.accountId') }}" value="{{ $zoomAccount['accountId'] ?? '' }}" required/>
                                    <x-basic-input context="offcanvas" type="text" name="clientId" label="{{ trans('account.clientId') }}" placeholder="{{ trans('account.placeholders.clientId') }}" value="{{ $zoomAccount['clientId'] ?? '' }}" required/>
                                    <x-basic-input context="offcanvas" type="password" name="clientSecret" label="{{ trans('account.clientSecret') }}" value="{{ $zoomAccount['clientSecret'] ?? '' }}" required />
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary me-2 w-100">{{ trans('main.submit') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-xl-7 col-md-5">
                        <div class="text-center">
                            <img src="{{ asset('assets/img/illustrations/account-settings-security-illustration.png') }}"
                                class="img-fluid" alt="Zoom Account Image" width="143" />
                        </div>
                    </div>
                </div>
            </div>
            <!-- Zoom Account -->

            <!-- Sessions -->
            <x-account.recent-sessions :sessions="$sessions" />
            <!-- Sessions -->

            <!-- Authorized Devices -->
            <x-account.authorized-devices :devices="$devices" />
            <!-- Authorized Devices -->
        </div>
    </div>
@endsection

@section('page-js')
    <script>
        let fields = ['currentPassword', 'newPassword', 'confirmNewPassword'];
        let zoomFields = ['accountId', 'clientId', 'clientSecret'];
        handleFormSubmit('#change-password-form', fields);
        handleFormSubmit('#zoom-account-form', zoomFields);
    </script>
@endsection
