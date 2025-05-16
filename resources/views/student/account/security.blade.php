@extends('layouts.student.master')

@section('page-css')

@endsection

@section('title', pageTitle('admin/plans.plans'))

@section('content')
    <div class="row">
        <div class="col-md-12">
            @include('student.account.navbar')
            <!-- Change Password -->
            <x-account.change-password action="{{ route('student.account.password.update') }}" />

            <!--/ Change Password -->

            <!-- Sessions -->
            <x-account.recent-sessions :sessions="$sessions"/>
            <!-- Sessions -->

            <!-- Authorized Devices -->
            <x-account.authorized-devices :devices="$devices"/>
            <!-- Authorized Devices -->
        </div>
    </div>
@endsection

@section('page-js')
    <script>
        let fields = ['currentPassword', 'newPassword', 'confirmNewPassword'];
        handleFormSubmit('#change-password-form', fields);
    </script>
@endsection
