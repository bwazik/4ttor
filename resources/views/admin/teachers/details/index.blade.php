@extends('layouts.admin.master')

@section('page-css')

@endsection

@section('title', pageTitle('admin/teachers.teachers'))

@section('content')
    <div class="row gy-6 gy-md-0">
        <!-- User Sidebar -->
        <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
            <!-- User Card -->
            <div class="card mb-6">
                <div class="card-body">
                    <div class="user-avatar-section">
                        <div class="d-flex align-items-center flex-column">
                            <div class="button-wrapper">
                                <form id="update-profile-form"
                                    action="{{ route('admin.teachers.updateProfilePic', $teacher->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="d-flex align-items-center justify-content-center gap-2 flex-wrap">
                                        <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                            <span class="d-none d-sm-block">Upload</span>
                                            <i class="ri-upload-2-line d-block d-sm-none"></i>
                                            <input type="file" id="upload" name="profile" class="account-file-input"
                                                hidden accept="image/png, image/jpeg, image/jpg" />
                                        </label>
                                        <button type="submit" class="btn btn-primary me-2 mb-4">
                                            <i class="ri-file-check-line d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Save</span>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger account-image-reset mb-4">
                                            <i class="ri-refresh-line d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Reset</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <img class="img-fluid rounded-3 mb-4"
                                src="{{ asset($teacher->profile_pic ? 'storage/profiles/teachers/' . $teacher->profile_pic : 'assets/img/avatars/default.jpg') }}"
                                height="120" width="120" alt="Profile Picture" id="uploadedAvatar" />
                            <div class="user-info text-center">
                                <h5>{{ $teacher->name }}</h5>
                                <span class="badge bg-label-{{ $teacher->is_active ? 'success' : 'secondary' }} rounded-pill">
                                    {{ trans('main.' . ($teacher->is_active ? 'active' : 'inactive')) }}
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
                                <h5 class="mb-0">{{ $teacher->students()->count() }}</h5>
                                <span>الطلاب</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center me-5 gap-4">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-primary rounded-3">
                                    <i class="ri-money-dollar-circle-line ri-24px"></i>
                                </div>
                            </div>
                            <div>
                                <h5 class="mb-0">{{ $teacher->balance }}</h5>
                                <span>الرصيد</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-4">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-primary rounded-3">
                                    <i class="ri-star-smile-line ri-24px"></i>
                                </div>
                            </div>
                            <div>
                                <h5 class="mb-0">{{ $teacher->average_rating }}</h5>
                                <span>التقييم</span>
                            </div>
                        </div>
                    </div>
                    <small class="card-text text-uppercase text-muted small">المعلومات الشخصية</small>
                    <ul class="list-unstyled my-3 py-1">
                        <li class="d-flex align-items-center mb-4">
                            <i class="ri-user-3-line ri-24px"></i>
                            <span class="fw-medium mx-2">{{ trans('main.realName_ar') }}:</span>
                            <span>{{ $teacher->getTranslation('name', 'ar') }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-4">
                            <i class="ri-user-3-line ri-24px"></i>
                            <span class="fw-medium mx-2">{{ trans('main.realName_en') }}:</span>
                            <span>{{ $teacher->getTranslation('name', 'en') }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-4">
                            <i class="ri-at-line ri-24px"></i>
                            <span class="fw-medium mx-2">{{ trans('main.username') }}:</span>
                            <span>{{ $teacher->username }}</span>
                        </li>
                    </ul>
                    <small class="card-text text-uppercase text-muted small">التواصل</small>
                    <ul class="list-unstyled my-3 py-1">
                        <li class="d-flex align-items-center mb-4">
                            <i class="ri-phone-line ri-24px"></i>
                            <span class="fw-medium mx-2">{{ trans('main.phone') }}:</span>
                            <span>{{ $teacher->phone }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-4">
                            <i class="ri-mail-line ri-24px"></i>
                            <span class="fw-medium mx-2">{{ trans('main.email') }}:</span>
                            <span>{{ $teacher->email }}</span>
                        </li>
                    </ul>
                    <small class="card-text text-uppercase text-muted small">المعلومات</small>
                    <ul class="list-unstyled my-3 py-1">
                        <li class="d-flex align-items-center mb-4">
                            <i class="ri-book-shelf-line ri-24px"></i>
                            <span class="fw-medium mx-2">{{ trans('main.subject') }}:</span>
                            <span>{{ $teacher->subject->name }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-4">
                            <i class="ri-money-dollar-circle-line ri-24px"></i>
                            <span class="fw-medium mx-2">{{ trans('main.plan') }}:</span>
                            <span>{{ $teacher->plan->name }}</span>
                        </li>
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
        });

        handleProfilePicSubmit('#update-profile-form');
    </script>
@endsection
