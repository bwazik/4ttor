@extends('layouts.admin.master')

@section('page-css')

@endsection

@section('title', pageTitle('admin/parents.parents'))

@section('content')
    <div class="row gy-6 gy-md-0">
        <!-- User Sidebar -->
        <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
            <!-- User Card -->
            <div class="card mb-6">
                <div class="card-body">
                    <div class="user-avatar-section">
                        <div class="d-flex align-items-center flex-column">
                            <img class="img-fluid rounded-3 mb-4" src="{{ asset('assets/img/avatars/default.jpg') }}" height="120"
                                width="120" alt="User avatar" />
                            <div class="user-info text-center">
                                <h5>{{ $parent->name }}</h5>
                                <span class="badge bg-label-{{ $parent->is_active ? 'success' : 'secondary' }} rounded-pill">
                                    {{ trans('main.' . ($parent->is_active ? 'active' : 'inactive')) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-around flex-wrap my-6 gap-0 gap-md-3 gap-lg-4">
                        <div class="d-flex align-items-center me-5 gap-4">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-primary rounded-3">
                                    <i class="ri-graduation-cap-line ri-24px"></i>
                                </div>
                            </div>
                            <div>
                                <h5 class="mb-0">{{ $parent->students()->count() }}</h5>
                                <span>عدد الأبناء</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-4">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-primary rounded-3">
                                    <i class="ri-money-dollar-circle-line ri-24px"></i>
                                </div>
                            </div>
                            <div>
                                <h5 class="mb-0">0.00</h5>
                                <span>الرصيد</span>
                            </div>
                        </div>
                    </div>
                    <small class="card-text text-uppercase text-muted small">المعلومات الشخصية</small>
                    <ul class="list-unstyled my-3 py-1">
                        <li class="d-flex align-items-center mb-4">
                            <i class="ri-user-3-line ri-24px"></i>
                            <span class="fw-medium mx-2">{{ trans('main.realName_ar') }}:</span>
                            <span>{{ $parent->getTranslation('name', 'ar') }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-4">
                            <i class="ri-user-3-line ri-24px"></i>
                            <span class="fw-medium mx-2">{{ trans('main.realName_en') }}:</span>
                            <span>{{ $parent->getTranslation('name', 'en') }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-4">
                            <i class="ri-at-line ri-24px"></i>
                            <span class="fw-medium mx-2">{{ trans('main.username') }}:</span>
                            <span>{{ $parent->username }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-4">
                            <i class="ri-men-line ri-24px"></i>
                            <span class="fw-medium mx-2">{{ trans('main.gender') }}:</span>
                            <span>{{ trans('main.' . ($parent->gender == 1 ? 'male' : 'female')) }}</span>
                        </li>
                    </ul>
                    <small class="card-text text-uppercase text-muted small">التواصل</small>
                    <ul class="list-unstyled my-3 py-1">
                        <li class="d-flex align-items-center mb-4">
                            <i class="ri-phone-line ri-24px"></i>
                            <span class="fw-medium mx-2">{{ trans('main.phone') }}:</span>
                            <span>{{ $parent->phone }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-4">
                            <i class="ri-mail-line ri-24px"></i>
                            <span class="fw-medium mx-2">{{ trans('main.email') }}:</span>
                            <span>{{ $parent->email }}</span>
                        </li>
                    </ul>
                    <small class="card-text text-uppercase text-muted small">المعلومات</small>
                    <ul class="list-unstyled my-3 py-1">

                    </ul>
                </div>
            </div>
            <!-- /User Card -->
        </div>
        <!--/ User Sidebar -->

        <!-- User Content -->
        <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
            <!-- User Tabs -->
            <div class="nav-align-top">
                <ul class="nav nav-pills flex-column flex-md-row mb-6 row-gap-2">
                    <li class="nav-item">
                        <a class="nav-link active" href="javascript:void(0);"><i class="ri-group-line me-2"></i>Account</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="app-user-view-security.html"><i
                                class="ri-lock-2-line me-2"></i>Security</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="app-user-view-billing.html"><i class="ri-bookmark-line me-2"></i>Billing &
                            Plans</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="app-user-view-notifications.html"><i
                                class="ri-notification-4-line me-2"></i>Notifications</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="app-user-view-connections.html"><i
                                class="ri-link-m me-2"></i>Connections</a>
                    </li>
                </ul>
            </div>
            <!--/ User Tabs -->
        </div>
        <!--/ User Content -->
    </div>
@endsection

@section('page-js')
    <script>
        document.addEventListener('DOMContentLoaded', function(e) {
            (function() {
                let accountUserImage = document.getElementById('uploadedAvatar');
                const fileInput = document.querySelector('.account-file-input'),
                    resetFileInput = document.querySelector('.account-image-reset');

                if (accountUserImage) {
                    const resetImage = accountUserImage.src;
                    fileInput.onchange = () => {
                        if (fileInput.files[0]) {
                            accountUserImage.src = window.URL.createObjectURL(fileInput.files[0]);
                        }
                    };
                    resetFileInput.onclick = () => {
                        fileInput.value = '';
                        accountUserImage.src = resetImage;
                    };
                }
            })();

            initializeSelect2('select2Container', 'gender', {{ $parent->gender }}, true);
            initializeSelect2('select2Container', 'is_active', {{ $parent->is_active }}, true);
        });
    </script>
@endsection
